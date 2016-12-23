<?php

	/**
	 * Form
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
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
		 * @var Array.
		 */
		public $output;

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
		 * @return Array.
		 */
		public function setExtentions($prefix = 'After') {
			$this->clearOutput();
			
			if (is_array($this->form->properties['extensions'])) {
				foreach ($this->form->properties['extensions'] as $extension) {
					if (method_exists($this, '_'.$extension.ucfirst($prefix))) {
						$this->{'_'.$extension.ucfirst($prefix)}();
					} else {
						$extension = array(
							'name' => $extension
						);
						
						if (null !== ($extension = $this->modx->getObject('modSnippet', $extension))) {
							$extension->process(array(
								'prefix'	=> $prefix,
								'form'		=> &$this->form
							));
						}
					}
				}
			}

			return $this->getOutput();
		}
		
		
		/**
		 * @acces public.
		 * @param String $extension.
		 * @param Array $properties.
		 * @param String $prefix.
		 * @return Boolean.
		 */
		public function setExtension($extension, $properties = array(), $prefix = 'After') {
			$this->form->setScriptProperties($properties);
			
			if (method_exists($this, '_'.$extension.ucfirst($prefix))) {
				return $this->{'_'.$extension.ucfirst($prefix)}();
			} else {
				$extension = array(
					'name' => $extension
				);
				
				if (null !== ($extension = $this->modx->getObject('modSnippet', $extension))) {
					return $extension->process(array(
						'prefix'	=> $prefix,
						'form'		=> &$this->form
					));
				}
			}
			
			return false;
		}
		
		/**
		 * @acces protected.
		 * @return Boolean.
		 */
		protected function _recaptchaBefore() {
			$this->setOutput('recaptcha', '<div class="g-recaptcha" data-sitekey="'.$this->modx->getOption('form.recaptcha_site_key').'"></div>');
		
			return true;
		}
		
		/**
		 * @acces protected.
		 * @return Boolean.
		 */
		protected function _recaptchaAfter() {
			$curl = curl_init();
		    
		    $response = false;

			curl_setopt_array($curl, array(
				CURLOPT_URL 			=> $this->modx->getOption('form.recaptcha_url'),
				CURLOPT_RETURNTRANSFER	=> true,
				CURLOPT_CONNECTTIMEOUT	=> 10,
				CURLOPT_POSTFIELDS		=> http_build_query(array(
					'secret'				=> $this->modx->getOption('form.recaptcha_secret_key'),
					'response'				=> $this->form->getValue('g-recaptcha-response')
				))
			));
			
			$response 	= curl_exec($curl);
			$info		= curl_getinfo($curl);
			
			if (!isset($info['http_code']) || '200' != $info['http_code']) {
				$this->form->validator->setOutput('recaptcha', 'recaptcha');
			}

			if (null === ($output = $this->modx->fromJSON($response))) {
				$this->form->validator->setOutput('recaptcha', 'recaptcha');
			} else {
				if (!isset($output['success']) || !$output['success']) {
					$this->form->validator->setOutput('recaptcha', 'recaptcha');
				}
			}
			
			curl_close($curl);
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		protected function _emailAfter() {
			if ($this->form->validator->isValid()) {
				if (false === ($email = $this->sendEmail('email'))) {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email (email extension).');
					
					$this->form->validator->setOutput('sendemail', 'sendemail');
				}
				
				return $email;
			}
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		protected function _respondEmailAfter() {
			if ($this->form->validator->isValid()) {
				if (false === ($email = $this->sendEmail('respondEmail'))) {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email (respondEmail extension).');
					
					$this->form->validator->setOutput('sendrespondemail', 'sendrespondemail');
				}
				
				return $email;
			}
		}
		
		/**
		 * @acces protected.
		 * @return Boolean.
		 */
		protected function _saveAfter() {
			if (isset($this->form->properties['saveName'])) {
				if (isset($this->form->properties['saveElements'])) {
					$elements = $this->modx->fromJSON($this->form->properties['saveElements']);
					
					foreach ($elements as $key => $value) {
						if (is_string($value)) {
							$value = array(
								'label'	=> $value	
							);
						}
						
						$elements[$key] = array_merge(array(
							'type'		=> 'textfield',
							'value'		=> $this->form->getValue($key),
							'valid'		=> $this->form->validator->isValid($key),
							'error'		=> $this->form->validator->getOutput($key),
							'values'	=> array()	
						), $value);
					}
					
					if (null !== ($object = $this->modx->newObject('FormFormSave'))) {
						$object->fromArray(array(
							'name'			=> $this->form->properties['saveName'],
							'resource_id'	=> $this->modx->resource->id,
							'ip'			=> $_SERVER['REMOTE_ADDR'],
							'data'			=> serialize($elements),
							'active'		=> $this->form->validator->isValid() ? 1 : 0,
							'editedon'		=> date('Y-m-d H:i:s')
						));
						
						if ($object->save()) {
							return true;
						}
					}
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param String $type.
		 * @return Boolean.
		 */
		protected function sendEmail($type) {
			if (null !== ($tpl = $this->form->properties[$type.'Tpl'])) {
				if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
					$emails = array(
						$type.'To' 		=> array(
							'alias'			=> 'to',
							'emails'		=> array()
						),
						$type.'ReplyTo' 	=> array(
							'alias'			=> 'reply-to',
							'emails'		=> array()
						),
						$type.'CC' 		=> array(
							'alias'			=> 'cc',
							'emails'		=> array()
						), 
						$type.'BCC' 		=> array(
							'alias'			=> 'bcc',
							'emails'		=> array()
						),
						$type.'From'		=> array(
							'emails'		=> array()
						)
					);
					
					foreach ($emails as $key => $value) {
						if (isset($this->form->properties[$key])) {
							if (null !== ($properties = $this->modx->fromJSON($this->form->properties[$key]))) {
								foreach ($properties as $email => $name) {
									if (is_array($name)) {
										$name = implode(' ', $name);
									}
									
									if (preg_match('/form.([\w]+)/si', $email, $matches)) {
										$matches = array_filter($matches);
										
										if (isset($matches[1])) {
											$email = str_replace($matches[0], $this->form->getValue($matches[1]), $email);
										}
									}
									
									if (preg_match_all('/form.([\w]+)/si', $name, $matches)) {
										$matches = array_filter($matches);
										
										if (isset($matches[1])) {
											foreach ($matches[1] as $matchKey => $matchValue) {
												$name = str_replace($matches[0][$matchKey], $this->form->getValue($matchValue), $name);
											}
										}
									}
	
									$emails[$key]['emails'][$this->form->getTemplate('@INLINE:'.$email, array())] = $this->form->getTemplate('@INLINE:'.$name, array());
								}
							}
						}
					}
					
					$output = array();

					foreach ($this->form->getValues() as $key => $value) {
						if (is_array($value)) {
							$output[rtrim($this->form->properties['placeholder'], '.').'.'.$key] = implode(',', $value);
						} else {
							$output[rtrim($this->form->properties['placeholder'], '.').'.'.$key] = $value;
						}
					}
					
					$subject = '';
					
					if (isset($this->form->properties[$type.'Subject'])) {
						$subject = $this->form->properties[$type.'Subject'];
					}
					
					$mail->reset();
					
					$mail->setHTML(isset($this->form->properties[$type.'HTML']) ? false : true);
					
					if (0 == count($emails[$type.'From']['emails'])) {
						$mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
						$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
					} else {
						foreach ($emails[$type.'From']['emails'] as $key => $value) {
							$mail->set(modMail::MAIL_FROM, $key);
							$mail->set(modMail::MAIL_FROM_NAME, $value);
						}
					}
					
					foreach ($emails as $key => $value) {
						if (isset($value['alias'])) {
							foreach ($value['emails'] as $email => $name) {
								$mail->address($value['alias'], $email, $name);
							}
						}
					}
					
					$mail->set(modMail::MAIL_SUBJECT, $this->form->getTemplate('@INLINE:'.$subject, $output));
					
					if (null !== ($tplWrapper = $this->form->properties[$type.'TplWrapper'])) {
						$mail->set(modMail::MAIL_BODY, $this->form->getTemplate($tplWrapper, array(
							'output' => $this->form->getTemplate($tpl, $output)
						)));
					} else {
						$mail->set(modMail::MAIL_BODY, $this->form->getTemplate($tpl, $output));
					}
					
					if ($mail->send()) {
						return true;
					} else {
						$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email because \''.$mail->mailer->ErrorInfo.'\' (email extension).');
					}
				} else {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email because \'mail.modPHPMailer\' was not set (email extension).');
				}
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email because no tpl was set (email extension).');
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param String $key.
		 * @param String $output.
		 */
		public function setOutput($key, $output) {
			$this->output[$key] = $output;
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getOutput() {
			return $this->output;
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function clearOutput() {
			$this->output = array();
			
			return true;
		}
	}

?>