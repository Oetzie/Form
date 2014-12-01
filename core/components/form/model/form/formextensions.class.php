<?php

	/**
	 * Form
	 *
	 * Copyright 2014 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Form, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * Form is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Form is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Form; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	class FormExtensions {
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $form;

		/**
		 * @acces public.
		 * @param Object $modx.
		 * @param Object $form.
		 */
		function __construct(modX &$modx, &$form) {
			$this->modx =& $modx;
			$this->form =& $form;
		}
		
		/**
		 * @acces public.
		 * @param String $prefix.
		 * @return Boolean.
		 */
		public function setExtensions($prefix = 'After') {
			if (false !== ($extensions = $this->modx->getOption('extensions', $this->form->scriptProperties, false))) {
				foreach (explode(',', $extensions) as $extension) {
					
					if (method_exists($this, '_'.$extension.$prefix)) {
						$this->{'_'.$extension.$prefix}();
					} else {
						$extension = $this->modx->getObject('modSnippet', array('name' => $extension));
							
						if (null !== $extension) {
							$extension->process(array(
								'prefix'	=> $prefix,
								'form'		=> &$this->form
							));
						}
					}
				}
				
				return $this->form->isValid();
			}
			
			return true;
		}

		/**
		 * @acces protected.
		 * @return Boolean.
		 */
		protected function _emailAfter() {
			if ($this->form->isValid()) {
				if (false === ($mail = $this->sendMail('email'))) {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send form email (email).');
				}
			
				return $mail;
			}
		}
		
		/**
		 * @acces protected.
		 * @return Boolean.
		 */
		protected function _autoRespondEmailAfter() {
			if ($this->form->isValid()) {
				if (false === ($mail = $this->sendMail('autoRespondEmail'))) {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send form email (autoRespondEmail).');
				}
			
				return $mail;
			}
		}
		
		/**
		 * @acces protected.
		 * @param String $prefix.
		 * @return Boolean.
		 */
		protected function sendMail($prefix = 'email') {
			if (false !== ($tpl = $this->modx->getOption($prefix.'Tpl', $this->form->extensionScriptProperties, false))) {
				$emails = array($prefix.'To' => array(), $prefix.'ReplyTo' => array(), $prefix.'CC' => array(), $prefix.'BCC' => array());
				
				foreach (array($prefix.'To', $prefix.'ReplyTo', $prefix.'CC', $prefix.'BCC') as $emailType) {
					$emailTypes = explode(',', $this->modx->getOption($emailType, $this->form->extensionScriptProperties, ''));
					
					foreach ($emailTypes as $emailTypeValue) {
						$emailTypeValue = explode('=', $emailTypeValue);
						
						if (0 === strpos($emailTypeValue[0], 'form.')) {
							$emailTypeValue[0] = $this->form->getValue(str_replace('form.', '', $emailTypeValue[0]));
						}
					
						if ('' != $emailTypeValue[0]) {
							$emails[$emailType][$emailTypeValue[0]] = array(
								'email'	=> str_replace('form.', '', $emailTypeValue[0]),
								'name'	=> 1 == count($emailTypeValue) ? $emailTypeValue[0] : $emailTypeValue[1]
							);	
						}
					}
				}

				$fromEmail = array(
					'email'	=> $this->modx->getOption($prefix.'From', $this->form->extensionScriptProperties, ''),
					'name'	=> ''
				);
				
				if (false !== strpos($fromEmail['email'], '=')) {
					$fromEmailNameArray = array();
					
					list($fromEmail, $fromEmailName) = explode('=', $fromEmail['email']);
						
					foreach (explode('+', $fromEmailName) as $fromEmailNameKey => $fromEmailNameValue) {
						if (0 === strpos($fromEmailNameValue, 'form.')) {
							$fromEmailNameValue = $this->form->getValue(str_replace('form.', '', $fromEmailNameValue));
						}
		
						$fromEmailNameArray[] = str_replace('form.', '', $fromEmailNameValue);
					}
					
					if (0 === strpos($fromEmail, 'form.')) {
						$fromEmail = $this->form->getValue(str_replace('form.', '', $fromEmail));
					}

					$fromEmail = array(
						'email'	=> str_replace('form.', '', $fromEmail),
						'name'	=> implode(' ', $fromEmailNameArray)
					);
				}
				
				if ('' == $fromEmail['email']) {
					$fromEmail = array_merge($formEmail, array('email' => $this->modx->getOption('emailsender')));
				}
				
				if ('' == $fromEmail['name']) {
					$fromEmail = array_merge($formEmail, array('name' => $fromEmail['email']));
				}
				
				$subject = $this->modx->getOption($prefix.'Subject', $this->form->extensionScriptProperties, '');
				
				if (0 === strpos($subject, 'form.')) {
					$subject = $this->form->getValue(str_replace('form.', '', $subject));
				}

				if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
					$mail->reset();
					
					$mail->setHTML('1' == $this->modx->getOption($prefix.'HTML', $this->form->extensionScriptProperties, '1') ? true : false);
					
					$mail->set(modMail::MAIL_FROM, $fromEmail['email']);
					$mail->set(modMail::MAIL_FROM_NAME, $fromEmail['name']);
					$mail->set(modMail::MAIL_SUBJECT, $this->form->getTpl('@INLINE:'.$subject));
					$mail->set(modMail::MAIL_BODY, $this->form->getTpl($tpl, $this->form->getValues()));

					$types = array($prefix.'To' => 'to', $prefix.'ReplyTo' => 'reply-to', $prefix.'CC' => 'cc', $prefix.'BCC' => 'bcc');
					
					foreach ($emails as $emailType => $emailTypes) {
						foreach ($emailTypes as $emailKey => $emailValue) {
							$mail->address($types[$emailType], $emailValue['email'], $emailValue['name']);
						}
					}
					
					if ($mail->send()) {
						return true;
					} else {
						$this->form->getValidator()->setBulkError('extension_email', array('emails' => implode(', ', array_filter(array_values(array_map(function($value) {
							return implode(', ', array_keys($value));
						}, $emails))))));
						
						$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send form email ('.$prefix.') because "'.$mail->mailer->ErrorInfo.'"');
					}
				} else {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send form email ('.$prefix.') because class "mail.modPHPMailer" could not be loaded.');
				}
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send form email ('.$prefix.') because "'.$prefix.'Tpl" is not set.');
			}
			
			return false;
		}

		/**
		 * @acces protected.
		 * @return Boolean.
		 */
		protected function _saveAfter() {
			if (false !== ($name = $this->modx->getOption('saveName', $this->form->extensionScriptProperties, false))) {
				$elements = explode(',', $this->modx->getOption('saveElements', $this->form->extensionScriptProperties, ''));
				
				foreach ($elements as $key => $element) {
					$element = explode('=', $element, 2);
					
					if (1 == count($element)) {
						$element[1] = $element[0];
					}
					
					$value = $this->form->getValue($element[0]);
					
					$elements[$key] = array(
						'label'	=> $element[1],
						'value'	=> is_array($value) ? implode(',', $value) : $value,
						'valid'	=> false === $this->form->getValidator()->getError($element[0]) ? true : false
					);
				}
				
				if (null !== ($save = $this->modx->newObject('FormSave'))) {
					$save->fromArray(array(
						'name'			=> ucfirst($name),
						'resource_id'	=> $this->modx->resource->id,
						'ip'			=> $_SERVER['REMOTE_ADDR'],
						'data'			=> serialize($elements),
						'active'		=> $this->form->isValid() ? 1 : 0,
						'editedon'		=> date('Y-m-d H:i:s')
					));
					
					if ($save->save()) {
						return true;
					}
				}
			}
			
			return false;
		}
	}
	
?>