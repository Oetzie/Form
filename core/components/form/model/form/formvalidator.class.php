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

	class FormValidator {
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
		public $errors = array();
		
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
		 * @return Boolean.
		 */
		public function isValid() {
			return 0 == count($this->errors);
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function validate() {
			if (false !== ($validate = $this->modx->getOption('validate', $this->form->scriptProperties, false))) {
				foreach (explode(',', $validate) as $validateElement) {
					$validators = explode(':', $validateElement);
					$validateElement = array_shift($validators);
					
					foreach ($validators as $validator) {
						$validator = explode('=', $validator, 2);
						
						$param = 1 == count($validator) ? '' : trim($validator[1], '^');
						
						if (method_exists($this, '_'.$validator[0])) {
							$this->{'_'.$validator[0]}($validateElement, $param);
						} else {
							$validator = $this->modx->getObject('modSnippet', array('name' => $validator[0]));
							
							if (null !== $validator) {
								$validator->process(array(
									'element'	=> $validateElement,
									'param'		=> $param,
									'form'		=> &$this->form
								));
							}
						}
					}
				}
				
				return $this->isValid();
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @return Boolean.
		 */
		protected function _required($element) {
			$value = $this->form->getValue($element);
			
			switch (gettype($value)) {
				case 'array':
					if (0 == count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'));
					}
					break;
				case 'string':
					if ('' == $value) {
						return $this->setError($element, trim(__FUNCTION__, '_'));
					}
					break;
				case 'boolean':
					return $this->setError($element, trim(__FUNCTION__, '_'));
					break;
			}

			return true;
		}
		
				/**
		 * @acces protected.
		 * @param String $element.
		 * @return Boolean.
		 */
		protected function _blank($element) {
			$value = $this->form->getValue($element);
			
			switch (gettype($value)) {
				case 'array':
					if (0 != count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'));
					}
					break;
				case 'string':
					if ('' != $value) {
						return $this->setError($element, trim(__FUNCTION__, '_'));
					}
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _equals($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'equals' => $param
			);
			
			switch (gettype($value)) {
				case 'array':
					if ($param != count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
				case 'string':
					if ($param != $value) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _equalsElement($element, $param = null) {
			$value = $this->form->getValue($element);

			$properties = array(
				'equals' 		=> $this->form->getValue($param),
				'equalsElement'	=> $param
			);
			
			if ('string' == gettype($value)) {
				if ($properties['equals'] != $value) {
					return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _contains($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'contains' => $param
			);
			
			if ('string' == gettype($value)) {
				 if (!preg_match('/'.$param.'/i', $value)) {
					return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _minLength($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'minLength' => $param
			);

			switch (gettype($value)) {
				case 'array':
					if ($param > count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
				case 'string':
					if ($param > strlen($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _maxLength($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'maxLength' => $param
			);
			
			switch (gettype($value)) {
				case 'array':
					if ($param < count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
				case 'string':
					if ($param < strlen($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _betweenLength($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$param = explode('|', $param);

			$properties = array(
				'minLength' => trim($param[0]),
				'maxLength' => trim($param[(1 == count($param) ? 0 : 1)])
			);

			switch (gettype($value)) {
				case 'array':
					if ($properties['minLength'] > count($value) || $properties['maxLength'] < count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
				case 'string':
					if ($properties['minLength'] > strlen($value) || $properties['maxLength'] < strlen($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _minValue($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'minValue' => $param
			);
			
			switch (gettype($value)) {
				case 'array':
					if ((int) $param > count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
				case 'string':
					if ((int) $param > (int) $value) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _maxValue($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'maxValue' => $param
			);
			
			switch (gettype($value)) {
				case 'array':
					if ((int) $param < count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
				case 'string':
					if ((int) $param < (int) $value) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _betweenValue($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$param = explode('|', $param);

			$properties = array(
				'minValue' => (int) trim($param[0]),
				'maxValue' => (int) trim($param[(1 == count($param) ? 0 : 1)])
			);

			switch (gettype($value)) {
				case 'array':
					if ($properties['minValue'] > count($value) || $properties['maxValue'] < count($value)) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
				case 'string':
					if ($properties['minValue'] > (int) $value || $properties['maxValue'] < (int) $value) {
						return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
					}
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _regex($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'regex' => $param
			);

			if ('string' == gettype($value)) {
				if (!preg_match($param, $value)) {
					return $this->setError($element, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @return Boolean.
		 */
		protected function _email($element) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			if ('string' == gettype($value)) {
				if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
					return $this->setError($element, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @return Boolean.
		 */
		protected function _ip($element) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			if ('string' == gettype($value)) {
				if (!filter_var($value, FILTER_VALIDATE_IP)) {
					return $this->setError($element, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @return Boolean.
		 */
		protected function _url($element) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			if ('string' == gettype($value)) {
				if (!preg_match('/(http:\/\/|https:\/\/|ftp:\/\/|www\.)[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $value)) {
					return $this->setError($element, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @return Boolean.
		 */
		protected function _number($element) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}

			if ('string' == gettype($value)) {
				if (!is_numeric(str_replace(',', '.', $value))) {
					return $this->setError($element, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @return Boolean.
		 */
		protected function _string($element) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}

			if ('string' == gettype($value)) {
				if (!preg_match('/^([a-z]+)$/si', $value)) {
					return $this->setError($element, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _date($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'format' => '' == $param ? $this->form->config['dateFormat'] : $param
			);

			if ('string' == gettype($value)) {
				$value = strtotime($value);

				if ('' == $value || false == $value) {
					return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
				}
	
				$this->form->setValue($element, strftime($properties['format'], $value));
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _minDate($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'format'	=> $this->form->config['dateFormat'],
				'minDate' 	=> strftime($this->form->config['dateFormat'], strtotime($param))
			);

			if ('string' == gettype($value)) {
				$value = strtotime($value);
				
				if ('' == $value || false == $value) {
					return $this->setError($element, 'date', $properties);
				} else if ($value < strtotime($properties['minDate'])) {
					return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
				}
				
				$this->form->setValue($element, strftime($properties['format'], $value));
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _maxDate($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$properties = array(
				'format'	=> $this->form->config['dateFormat'],
				'maxDate' 	=> strftime($this->form->config['dateFormat'], strtotime($param))
			);
			
			if ('string' == gettype($value)) {
				$value = strtotime($value);
				
				if ('' == $value || false == $value) {
					return $this->setError($element, 'date', $properties);
				} else if ($value > strtotime($properties['maxDate'])) {
					return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
				}
				
				$this->form->setValue($element, strftime($properties['format'], $value));
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $element.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _betweenDate($element, $param = null) {
			$value = $this->form->getValue($element);
			
			if (empty($value)) {
				return true;	
			}
			
			$param = explode('|', $param);

			$properties = array(
				'format'	=> $this->form->config['dateFormat'],
				'minDate' 	=> strftime($this->form->config['dateFormat'], strtotime(trim($param[0]))),
				'maxDate' 	=> strftime($this->form->config['dateFormat'], strtotime(trim($param[(1 == count($param) ? 0 : 1)])))
			);
			
			if ('string' == gettype($value)) {
				$value = strtotime($value);
				
				if ('' == $value || false == $value) {
					return $this->setError($element, 'date', $properties);
				} else if ($value < strtotime($properties['minDate']) || $value > strtotime($properties['maxDate'])) {
					return $this->setError($element, trim(__FUNCTION__, '_'), $properties);
				}
				
				$this->form->setValue($element, strftime($properties['format'], $value));
			}
			
			return true;
		}

		
		/**
		 * @acces public.
		 * @param String $key.
		 * @param String $error.
		 * @param Array $properties.
		 * @param String $errorMessage.
		 * @return false.
		 */
		public function setError($key, $error = 'required', $properties = array(), $errorMessage = null) {
			$properties = array_merge(array(
				'element'	=> $key,
				'value'		=> $this->form->getValue($key),
				'error'		=> $error
			), $properties);
			
			if (null === $errorMessage) {
				$error = $this->modx->lexicon($this->form->getPlaceholderKey().'error_'.strtolower($error), $properties);	
			} else {
				$error = $errorMessage;
			}

			$this->errors[$key] = $error;

			$this->modx->setPlaceholders(array(
				$key			=> $this->form->getTpl($this->form->scriptProperties['tplError'], array_merge($properties, array('error' => $error))),
				$key.'.message'	=> $error
			), $this->form->getPlaceholderKey().'error.');
			
			return $this->setBulkError();
		}
		
		/**
		 * @acces public.
		 * @param String $error.
		 * @param Array $properties.
		 * @param String $errorMessage.
		 * @return false.
		 */
		public function setBulkError($error = 'validate', $properties = array(), $errorMessage = null) {
			$errors = array();
			
			foreach ($this->errors as $key => $value) {
				$errors[] = $this->form->getTpl($this->form->scriptProperties['tplBulkError'], array('error' => $value)); 
			}
			
			if (null === $errorMessage) {
				$error = $this->modx->lexicon($this->form->getPlaceholderKey().'error_'.strtolower($error), $properties);
			} else {
				$error = $errorMessage;
			}
			
			$properties = array_merge(array(
				'elements'	=> count($this->errors),
				'errors'	=> implode(PHP_EOL, $errors),
				'error'		=> $error
			), $properties);
			
			$this->modx->setPlaceholders(array(
				'error'			=> $this->form->getTpl($this->form->scriptProperties['tplBulkWrapper'], array_merge($properties, array('error' => $error))),
				'error.message'	=> $error
			), $this->form->getPlaceholderKey());
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param String $key.
		 * @return Mixed.
		 */
		public function getError($key) {
			if (array_key_exists($key, $this->errors)) {
				return $this->errors[$key];
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getErrors() {
			return $this->errors;
		}
	}
	
?>