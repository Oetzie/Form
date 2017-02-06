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
		public function validate() {
			foreach ($this->form->properties['validation'] as $key => $value) {
				if (is_string($value)) {
					$value = array($value);
				}
				
				foreach ($value as $validator) {
					$argument = null;
					
					if (is_array($validator)) {
						$argument = array_shift(array_values($validator));
						$validator = array_shift(array_keys($validator));
					}
					
					if (method_exists($this, $validator)) {
						$this->{$validator}($key, $argument);
					} else {
						$criterea = array(
							'name' => $validator
						);
					
						if (null !== ($object = $this->modx->getObject('modSnippet', $criterea))) {
							$object->process(array(
								'element'	=> $key,
								'argument'	=> $argument,
								'validator' => &$this,
								'form'		=> &$this->form
							));
						}
					}
				}
			}

			return $this->isValid();
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function required($key) {
			$value = $this->form->getValue($key);

			switch (gettype($value)) {
				case 'array':
					if (0 == count($value) || isset($value['error'])) {
						return $this->setError($key, __FUNCTION__);
					}
					
					break;
				case 'string':
					if ('' == $value) {
						return $this->setError($key, __FUNCTION__);
					}
					
					break;
				case 'NULL':
					return $this->setError($key, __FUNCTION__);
					
					break;
			}

			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function _requiredWhen($key, $argument = null) {
			$value = $this->form->getValue($param);
			
			if ('' != $value) {
				return $this->required($key);	
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function blank($key) {
			$value = $this->form->getValue($key);
			
			switch (gettype($value)) {
				case 'array':
					if (0 < count($value)) {
						return $this->setError($key, __FUNCTION__);
					}
					
					break;
				case 'string':
					if ('' != $value) {
						return $this->setError($key, __FUNCTION__);
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function equals($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				switch (gettype($value)) {
					case 'array':
						if (explode(',', $argument) !== $value) {
							return $this->setError($key, __FUNCTION__, array(
								'equals' => $argument
							));
						}
						
						break;
					case 'string':
						if ($argument != $value) {
							return $this->setError($key, __FUNCTION__, array(
								'equals' => $argument
							));
						}
						
						break;
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function equalsTo($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			switch (gettype($value)) {
				case 'array':
					if ($value !== ($equals = $this->form->getValue($argument, array()))) {
						return $this->setError($key, __FUNCTION__, array(
							'equals' 		=> implode(',', $equals),
							'equalsTo'		=> $argument
						));
					}
					
					break;
				case 'string':
					if ($value != ($equals = $this->form->getValue($argument))) {
						return $this->setError($key, __FUNCTION__, array(
							'equals' 		=> $equals,
							'equalsTo'		=> $argument
						));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function contains($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					 if (!preg_match('/'.$argument.'/i', $value)) {
						return $this->setError($key, __FUNCTION__, array(
							'contains' => $argument
						));
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function minLength($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				switch (gettype($value)) {
					case 'array':
						if ((int) $argument > count($value)) {
							return $this->setError($key, __FUNCTION__, array(
								'minLength' => $argument
							));
						}
						
						break;
					case 'string':
						if ((int) $argument > strlen($value)) {
							return $this->setError($key, __FUNCTION__, array(
								'minLength' => $argument
							));
						}
						
						break;
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function maxLength($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				switch (gettype($value)) {
					case 'array':
						if ((int) $argument < count($value)) {
							return $this->setError($key, __FUNCTION__, array(
								'maxLength' => $argument
							));
						}
						
						break;
					case 'string':
						if ((int) $argument < strlen($value)) {
							return $this->setError($key, __FUNCTION__, array(
								'maxLength' => $argument
							));
						}
						
						break;
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function betweenLength($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				$argument = explode('||', $argument);
				
				$min = (int) array_shift($argument);
				$max = (int) array_shift($argument);
	
				switch (gettype($value)) {
					case 'array':
						if ($min > count($value) || $max < count($value)) {
							return $this->setError($key, __FUNCTION__, array(
								'minLength' => $min,
								'maxLength' => $max
							));
						}
						
						break;
					case 'string':
						if ($min > strlen($value) || $max < strlen($value)) {
							return $this->setError($key, __FUNCTION__, array(
								'minLength' => $min,
								'maxLength' => $max
							));
						}
						
						break;
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function minValue($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				switch (gettype($value)) {
					case 'array':
						if ((int) $argument > count($value)) {
							return $this->setError($key, __FUNCTION__, array(
								'minValue' => $argument
							));
						}
						
						break;
					case 'string':
						if ((int) $argument > (int) $value) {
							return $this->setError($key, __FUNCTION__, array(
								'minValue' => $argument
							));
						}
						
						break;
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function maxValue($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				switch (gettype($value)) {
					case 'array':
						if ((int) $argument < count($value)) {
							return $this->setError($key, __FUNCTION__, array(
								'maxValue' => $argument
							));
						}
						
						break;
					case 'string':
						if ((int) $argument < (int) $value) {
							return $this->setError($key, __FUNCTION__, array(
								'maxValue' => $argument
							));
						}
						
						break;
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function betweenValue($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				$argument = explode('||', $argument);
				
				$min = (int) array_shift($argument);
				$max = (int) array_shift($argument);
	
				switch (gettype($value)) {
					case 'array':
						if ($min > count($value) || $max < count($value)) {
							return $this->setError($key,__FUNCTION__, array(
								'minValue' => $min,
								'maxValue' => $max
							));
						}
						
						break;
					case 'string':
						if ($min > (int) $value || $max < (int) $value) {
							return $this->setError($key, __FUNCTION__, array(
								'minValue' => $min,
								'maxValue' => $max
							));
						}
						
						break;
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function regex($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!preg_match($argument, $value)) {
						return $this->setError($key, __FUNCTION__, array(
							'regex' => $argument
						));
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function email($key) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
						return $this->setError($key, __FUNCTION__);
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function ip($key) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!filter_var($value, FILTER_VALIDATE_IP)) {
						return $this->setError($key, __FUNCTION__);
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function url($key) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!preg_match('/(http:\/\/|https:\/\/|ftp:\/\/|www\.)[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $value)) {
						return $this->setError($key, __FUNCTION__);
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function iban($key) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!preg_match('/[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/i', $value)) {
						return $this->setError($key, __FUNCTION__);
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function phone($key) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($key)) {
					if (!preg_match('/^([\d\s?\-?]){10,11}$/', $value)) {
						return $this->setError($key, __FUNCTION__);
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function number($key) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!is_numeric(str_replace(',', '.', $value))) {
						return $this->setError($key, __FUNCTION__);
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function alpha($key) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!preg_match('/^([a-z]+)$/si', $value)) {
						return $this->setError($key, __FUNCTION__);
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function alphaNumeric($key) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!preg_match('/^([a-z0-9]+)$/si', $value)) {
						return $this->setError($key, __FUNCTION__);
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function date($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				if ('string' == gettype($value)) {
					if (!strtotime($value)) {
						return $this->setError($key, __FUNCTION__, array(
							'format' => $argument
						));
					}
		
					$this->form->setValue($key, strftime($argument, strtotime($value)));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function minDate($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				$argument = explode('||', $argument);
				
				$format = array_shift($argument);
				$min 	= strftime($format, strtotime(array_shift($argument)));
				
				if ('string' == gettype($value)) {
					if (!strtotime($value)) {
						return $this->setError($key, 'date', array(
							'format'	=> $format,
							'minDate' 	=> $min
						));
					} else if (strtotime($value) < strtotime($min)) {
						$this->form->setValue($key, strftime($format, strtotime($value)));
						
						return $this->setError($key, __FUNCTION__, array(
							'format'	=> $format,
							'minDate' 	=> $min
						));
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function maxDate($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				$argument = explode('||', $argument);
				
				$format = array_shift($argument);
				$max 	= strftime($format, strtotime(array_shift($argument)));
			
				if ('string' == gettype($value)) {
					if (!strtotime($value)) {
						return $this->setError($key, 'date', array(
							'format'	=> $format,
							'maxDate' 	=> $max
						));
					} else if (strtotime($value) > strtotime($max)) {
						$this->form->setError($key, strftime($format, strtotime($value)));
						
						return $this->setOutput($key, __FUNCTION__, array(
							'format'	=> $format,
							'maxDate' 	=> $max
						));
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function betweenDate($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if ('' != $value) {
				$argument = explode('||', $argument);
				
				$format = array_shift($argument);
				$min	= strftime($format, strtotime(array_shift($argument)));
				$max 	= strftime($format, strtotime(array_shift($argument)));
	
				if ('string' == gettype($value)) {
					if (!strtotime($value)) {
						return $this->setError($key, 'date', array(
							'format'	=> $format,
							'minDate' 	=> $min,
							'maxDate' 	=> $max
						));
					} else if (strtotime($value) < strtotime($min) || strtotime($value) > strtotime($max)) {
						$this->form->setValue($key, strftime($format, strtotime($value)));
						
						return $this->setError($key, __FUNCTION__, array(
							'format'	=> $format,
							'minDate' 	=> $min,
							'maxDate' 	=> $max
						));
					}
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param Null|String $argument.
		 * @return Boolean.
		 */
		protected function extension($key, $argument = null) {
			$value = $this->form->getValue($key);
			
			if (!empty($value)) {
				if (isset($value['name'])) {
					$extension = substr($value['name'], strrpos($value['name'], '.') + 1, strlen($value['name']));
					
					if (!in_array($extension, explode('||', $argument))) {
						return $this->setError($key, __FUNCTION__, array(
							'extension'	=> $extension
						));
					}
				}
			}
			
			return true;
		}

		/**
		 * @acces public.
		 * @return Null|String.
		 */
		public function getBulkError() {
			$bulk	= null;
			$output = array();
			$errors = $this->getErrors();
			
			if (isset($errors['bulk'])) {
				$bulk = $errors['bulk']['error'];
				
				unset($errors['bulk']);
			}
			
			foreach (array_values($errors) as $key => $error) {
				$class = array();
				
				if (0 == $key) {
					$class[] = 'first';
				}
				
				if (count($errors) - 1 == $key) {
					$class[] = 'last';
				}
				
				$class[] = 0 == $key % 2 ? 'odd' : 'even';
				
				$output[] = $this->form->getTemplate($this->form->properties['tplBulkError'], array_merge(array(
					'class'	=> implode(' ', $class)
				), $error));
				
				$i++;
			}
			
			if (0 < count($errors) || null !== $bulk) {
				return $this->form->getTemplate($this->form->properties['tplBulkWrapper'], array(
					'error'		=> null === $bulk ? $this->modx->lexicon('form.error_bulk') : $bulk,
					'total'		=> count($output),
					'output' 	=> implode(PHP_EOL, $output)
				));
			}
			
			return null;
		}

		/**
		 * @acces public.
		 * @param String $key.
		 * @param String $error.
		 * @param Array $properties.
		 * @param String $message.
		 * @return Boolean.
		 */
		public function setError($key, $error, $properties = array(), $message = null) {
			if (null === $properties) {
				$properties = array();
			}
			
			$this->errors[$key] = array_merge(array(
				'element'	=> $key,
				'value'		=> $this->form->getValue($key),
				'type'		=> $error,
				'error'		=> null == $message ? $this->modx->lexicon('form.error_'.strtolower($error)) : $message
			), $properties);
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param String $key.
		 * @param Mixed $default.
		 * @return Array.
		 */
		public function getError($key, $default = null) {
			if (isset($this->errors[$key])) {
				return $this->errors[$key];
			}
			
			return $default;
		}
		
		/**
		 * @acces public.
		 * @param Boolean $bulk.
		 * @return Array.
		 */
		public function getErrors($bulk = false) {
			if ($bulk) {
				return $this->errors;
			}
			
			$errors = $this->errors;
			
			foreach ($errors as $key => $value) {
				if ('bulk' != $key) {
					$errors[$key] = $value;
				}
			}
			
			return $errors;
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function clearErrors() {
			return $this->errors = array();
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function isValid() {
			return 0 == count($this->errors);
		}
	}

?>