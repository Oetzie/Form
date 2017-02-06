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
		 * @param String $event.
		 * @return Array.
		 */
		public function invokeExtensions($event = 'onAfterPost') {
			$this->clearOutput();
			
			foreach ($this->form->properties['extensions'] as $extension) {
				$properties = array();
						
				if (isset($this->form->properties[$extension])) {
					$properties = $this->modx->fromJSON($this->form->properties[$extension]);
				}
						
				if (method_exists($this, $extension)) {
					$this->{$extension}($event, $properties);
				} else {
					$criterea = array(
						'name' => $extension
					);
					
					if (null !== ($object = $this->modx->getObject('modSnippet', $criterea))) {
						$object->process(array_merge($properties, array(
							'event'			=> $event,
							'extensions' 	=> &$this,
							'form'			=> &$this->form
						)));
					}
				}
			}
			
			return $this->getOutput();
		}
		
		/**
		 * @acces public.
		 * @param String $event.
		 * @return Boolean.
		 */
		public function invokeExtension($event, $extension, $properties = null) {
			if (method_exists($this, $extension)) {
				return $this->{$extension}($event, $properties);
			}
			
			return false;
		}
		
		/**
		 * @acces protected.
		 * @param String $event.
		 * @param Array $properties.
		 * @return Boolean.
		 */
		protected function recaptcha($event, $properties = null) {
			switch ($event) {
				case 'onBeforePost':
					$this->setOutput('recaptcha', '<div class="g-recaptcha" data-sitekey="'.$this->modx->getOption('form.recaptcha_site_key').'"></div>');
					
					break;
				case 'onValidatePost':
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
						$this->form->validator->setError('recaptcha', 'recaptcha');
					}
		
					if (null === ($output = $this->modx->fromJSON($response))) {
						$this->form->validator->setError('recaptcha', 'recaptcha');
					} else {
						if (!isset($output['success']) || !$output['success']) {
							$this->form->validator->setError('recaptcha', 'recaptcha');
						}
					}
					
					curl_close($curl);
			
					break;
			}
		}
		
		/**
		 * @acces protected.
		 * @param String $event.
		 * @param Array $options.
		 * @return Boolean.
		 */
		protected function save($event, $options = null) {
			switch ($event) {
				case 'onSuccessPost':
				case 'onFailurePost':
					if (null !== $options) {
						if (isset($options['elements'])) {
							if (isset($options['name'])) {
								$name = $options['name'];
							} else {
								$name = $this->modx->resource->pagetitle;
							}
						
							$data = array();
							
							foreach ($options['elements'] as $key => $value) {
								if (is_string($value)) {
									$value = array(
										'label'	=> $value	
									);
								}
							
								$data[$key] = array_merge(array(
									'type'		=> 'textfield',
									'value'		=> $this->form->getValue($key, ''),
									'valid'		=> (bool) $this->form->validator->getError($key),
									'error'		=> $this->form->validator->getError($key, ''),
									'values'	=> array()
								), $value);
							}

							if (null !== ($object = $this->modx->newObject('FormForms'))) {
								if ((bool) $this->modx->getOption('form.encrypt', null, true)) {
									$data = $object->encrypt($this->modx->toJSON($data));
								} else {
									$data = $this->modx->toJSON($data);
								}
							
								$object->fromArray(array(
									'name'			=> $name,
									'resource_id'	=> $this->modx->resource->id,
									'ip'			=> $_SERVER['REMOTE_ADDR'],
									'data'			=> $data,
									'active'		=> $this->form->validator->isValid() ? 1 : 0,
									'editedon'		=> date('Y-m-d H:i:s')
								));
								
								if ($object->save()) {
									return true;
								}
							}
						}
					} else {
						$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not save form (save extension properties not set).');
					}
					
					break;
			}
			
			return false;
		}
		
		/**
		 * @acces protected.
		 * @param String $event.
		 * @param Array $options.
		 * @return Boolean.
		 */
		protected function email($event, $options = null) {
			switch ($event) {
				case 'onSuccessPost':
					if (null !== $options) {
						if (false === ($email = $this->handleEmail($options))) {
							$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email (email extension).');
							
							$this->form->validator->setError('bulk', 'sendemail');
						}
						
						return $email;
					} else {
						$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not use email (email extension properties not set).');
					}
					
					break;
			}
			
			return false;
		}
		
		/**
		 * @acces protected.
		 * @param String $event.
		 * @param Array $options.
		 * @return Boolean.
		 */
		protected function respondEmail($event, $options = null) {
			switch ($event) {
				case 'onSuccessPost':
					if (null !== $options) {	
						if (false === ($email = $this->handleEmail($options))) {
							$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email (respond email extension).');
							
							$this->form->validator->setError('bulk', 'sendemail');
						}
						
						return $email;
					} else {
						$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email (respond email extension properties not set).');
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @param Array $options.
		 * @return Boolean.
		 */
		protected function handleEmail($options) {
			if (isset($options['tpl'])) {
				if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
					$emails = array(
						'to' 	=> array(
							'alias'		=> 'to',
							'emails'	=> array()
						),
						'reply' => array(
							'alias'		=> 'reply-to',
							'emails'	=> array()
						),
						'CC' 	=> array(
							'alias'		=> 'cc',
							'emails'	=> array()
						), 
						'BCC' 	=> array(
							'alias'		=> 'bcc',
							'emails'	=> array()
						),
						'from'	=> array(
							'emails'	=> array()
						)
					);
					
					foreach ($emails as $key => $value) {
						if (isset($options[$key])) {
							foreach ($options[$key] as $email => $name) {
								if (is_array($name)) {
									$name = implode(' ', $name);
								}
								
								$prefix = $this->form->properties['prefix'];

								if (preg_match('/'.$prefix.'([\w\_]+)/si', $email, $matches)) {
									$matches = array_filter($matches);
									
									if (isset($matches[1])) {
										$email = str_replace($matches[0], $this->form->getValue($matches[1]), $email);
									}
								}
								
								if (preg_match('/'.$prefix.'([\w\_]+)/si', $name, $matches)) {
									$matches = array_filter($matches);
									
									if (isset($matches[1])) {
										$name = str_replace($matches[0], $this->form->getValue($matches[1]), $name);
									}
								}

								$emails[$key]['emails'][$email] = $name;
							}
						}
					}
					
					$placeholders = array();
					
					foreach ($this->form->getValues(true) as $key => $value) {
						$placeholders[$prefix.$key] = $value;
					}
					
					if (isset($options['subject'])) {
						$subject = $options['subject'];
					} else {
						$subject = 'Form subject not provided';
					}
					
					$mail->reset();
					$mail->setHTML(isset($options['html']) ? (bool) $options['html'] : true);
					
					if (0 == count($emails['from']['emails'])) {
						$mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
						$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
					} else {
						foreach ($emails['from']['emails'] as $key => $value) {
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
					
					$mail->set(modMail::MAIL_SUBJECT, $this->form->getTemplate('@INLINE:'.$subject, $placeholders));
					
					if (null !== ($tplWrapper = $options['tplWrapper'])) {
						$mail->set(modMail::MAIL_BODY, $this->form->getTemplate($tplWrapper, array(
							'output' => $this->form->getTemplate($options['tpl'], $placeholders)
						)));
					} else {
						$mail->set(modMail::MAIL_BODY, $this->form->getTemplate($options['tpl'], $placeholders));
					}
					
					if (isset($options['attachments'])) {
						foreach ($options['attachments'] as $key => $value) {
							if (is_string($value)) {
								$value = array(
									'file' 	=> $value
								);
							}
							
							if (isset($value['file'])) {
								if (preg_match('/'.$prefix.'([\w\_]+)/si', $value['file'], $matches)) {
									$matches = array_filter($matches);
									
									if (isset($matches[1])) {
										$value = $this->form->getValue($matches[1]);
										
										if (isset($value['tmp_name'])) {
											$mail->mailer->addAttachment($value['tmp_name'], $value['name'], 'base64', $value['type']);
										}
									}
								} else {
									$value = array_merge(array(
										'name' 		=> substr($value['file'], strrpos($value['file'], '/') + 1, strlen($value['file'])),
										'encoding'	=> 'base64',
										'type'		=> 'application/octet-stream'
									), $value);
									
									$mail->mailer->addAttachment($this->modx->getOption('base_path').ltrim($value['file'], '/'), $value['name'], $value['encoding'], $value['type']);
								}
							}
						}
					}

					if ($mail->send()) {
						return true;
					} else {
						$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email (email extension \''.$mail->mailer->ErrorInfo.'\').');
					}
				} else {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email (email extension \'mail.modPHPMailer\' was not set).');
				}
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not send email (email extension tpl not set).');
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param String $key.
		 * @param String $value.
		 */
		public function setOutput($key, $value) {
			$this->output[$key] = $value;
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
		 * @return Array.
		 */
		public function clearOutput() {
			return $this->output = array();
		}
	}

?>