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
		public $output;
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $message = null;
		
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
			$this->clearOutput();
			
			if (is_array($this->form->properties['validate'])) {
				foreach ($this->form->properties['validate'] as $key => $value) {					
					foreach ($value as $validator => $param) {
						if (is_numeric($validator)) {
							$validator  = $param;
							$param 		= 'true';
						}
						
						if (method_exists($this, '_'.$validator)) {
							$this->{'_'.$validator}($key, $param);
						} else {
							if (null !== ($validator = $this->modx->getObject('modSnippet', array('name' => $validator)))) {
								$validator->process(array(
									'element'	=> $key,
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
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _required($key) {
			$value = $this->form->getValue($key);
			
			switch (gettype($value)) {
				case 'array':
					if (0 == count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'));
					}
					
					break;
				case 'string':
					if ('' == $value) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'));
					}
					
					break;
				case 'boolean':
					return $this->setOutput($key, trim(__FUNCTION__, '_'));
					
					break;
			}

			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _requiredWhen($key, $param = null) {
			$value = $this->form->getValue($param);
			
			if ('' == $value) {
				return true;	
			}
			
			return $this->_required($key);
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _blank($key) {
			$value = $this->form->getValue($key);
			
			switch (gettype($value)) {
				case 'array':
					if (0 != count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'));
					}
					
					break;
				case 'string':
					if ('' != $value) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _equals($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			switch (gettype($value)) {
				case 'array':
					if ($param != count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'equals' => $param
						));
					}
					
					break;
				case 'string':
					if ($param != $value) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'equals' => $param
						));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _equalsTo($key, $param = null) {
			$value = $this->form->getValue($key);

			if ('string' == gettype($value)) {
				if ($this->form->getValue($param) != $value) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
						'equals' 		=> $this->form->getValue($param),
						'equalsTo'		=> $param
					));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _contains($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}

			if ('string' == gettype($value)) {
				 if (!preg_match('/'.$param.'/i', $value)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
						'contains' => $param
					));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _minLength($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}

			switch (gettype($value)) {
				case 'array':
					if ((int) $param > count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'minLength' => $param
						));
					}
					
					break;
				case 'string':
					if ((int) $param > strlen($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'minLength' => $param
						));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _maxLength($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			switch (gettype($value)) {
				case 'array':
					if ((int) $param < count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'maxLength' => $param
						));
					}
					
					break;
				case 'string':
					if ((int) $param < strlen($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'maxLength' => $param
						));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _betweenLength($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			$param = explode('|', $param);
			
			$min = (int) trim($param[0]);
			$max = (int) trim($param[(1 == count($param) ? 0 : 1)]);

			switch (gettype($value)) {
				case 'array':
					if ($min > count($value) || $max < count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'minLength' => $min,
							'maxLength' => $max
						));
					}
					
					break;
				case 'string':
					if ($min > strlen($value) || $max < strlen($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'minLength' => $min,
							'maxLength' => $max
						));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _minValue($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			switch (gettype($value)) {
				case 'array':
					if ((int) $param > count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'minValue' => $param
						));
					}
					
					break;
				case 'string':
					if ((int) $param > (int) $value) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'minValue' => $param
						));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _maxValue($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			switch (gettype($value)) {
				case 'array':
					if ((int) $param < count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'maxValue' => $param
						));
					}
					
					break;
				case 'string':
					if ((int) $param < (int) $value) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'maxValue' => $param
						));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _betweenValue($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			$param = explode('|', $param);
			
			$min = (int) trim($param[0]);
			$max = (int) trim($param[(1 == count($param) ? 0 : 1)]);

			switch (gettype($value)) {
				case 'array':
					if ($min > count($value) || $max < count($value)) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'minValue' => $min,
							'maxValue' => $max
						));
					}
					
					break;
				case 'string':
					if ($min > (int) $value || $max < (int) $value) {
						return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
							'minValue' => $min,
							'maxValue' => $max
						));
					}
					
					break;
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _regex($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			if ('string' == gettype($value)) {
				if (!preg_match($param, $value)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
						'regex' => $param
					));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _email($key) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			if ('string' == gettype($value)) {
				if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _ip($key) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			if ('string' == gettype($value)) {
				if (!filter_var($value, FILTER_VALIDATE_IP)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _url($key) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			if ('string' == gettype($value)) {
				if (!preg_match('/(http:\/\/|https:\/\/|ftp:\/\/|www\.)[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $value)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _iban($key) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			if ('string' == gettype($value)) {
				if (!preg_match('/[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/i', $value)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _number($key) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}

			if ('string' == gettype($value)) {
				if (!is_numeric(str_replace(',', '.', $value))) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _phone($key) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			if ('string' == gettype($key)) {
				if (!preg_match('/^([\d\s?\-?]){10,11}$/', $value)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @return Boolean.
		 */
		protected function _string($key) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}

			if ('string' == gettype($value)) {
				if (!preg_match('/^([a-z]+)$/si', $value)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'));
				}
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _date($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			$format = 'true' == $param ? $this->form->properties['dateFormat'] : $param;
			
			if ('string' == gettype($value)) {
				$value = strtotime($value);

				if ('' == $value || false == $value) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
						'format' => $format
					));
				}
	
				$this->form->setValue($key, strftime($format, $value));
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _minDate($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			$min = strftime($this->form->properties['dateFormat'], strtotime('now' == $param ? date('d-m-Y') : $param));
			
			if ('string' == gettype($value)) {
				$value = strtotime($value);
				
				if ('' == $value || false == $value) {
					return $this->setOutput($key, 'date', array(
						'format'	=> $this->form->properties['dateFormat'],
						'minDate' 	=> $min
					));
				} else if ($value < strtotime($min)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
						'format'	=> $this->form->properties['dateFormat'],
						'minDate' 	=> $min
					));
				}
				
				$this->form->setValue($key, strftime($this->form->properties['dateFormat'], $value));
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _maxDate($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if ('' == $value) {
				return true;	
			}
			
			$max = strftime($this->form->properties['dateFormat'], strtotime('now' == $param ? date('d-m-Y') : $param));
			
			if ('string' == gettype($value)) {
				$value = strtotime($value);
				
				if ('' == $value || false == $value) {
					return $this->setOutput($key, 'date', array(
						'format'	=> $this->form->properties['dateFormat'],
						'maxDate' 	=> $max
					));
				} else if ($value > strtotime($max)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
						'format'	=> $this->form->properties['dateFormat'],
						'maxDate' 	=> $max
					));
				}
				
				$this->form->setValue($key, strftime($this->form->properties['dateFormat'], $value));
			}
			
			return true;
		}
		
		/**
		 * @acces protected.
		 * @param String $key.
		 * @param String $param.
		 * @return Boolean.
		 */
		protected function _betweenDate($key, $param = null) {
			$value = $this->form->getValue($key);
			
			if (empty($value)) {
				return true;	
			}
			
			$param = explode('|', $param);
			
			$min = trim($param[0]);
			$max = trim($param[(1 == count($param) ? 0 : 1)]);
			
			$min = strftime($this->form->properties['dateFormat'], strtotime('now' == $min ? date('d-m-Y') : $min));
			$max = strftime($this->form->properties['dateFormat'], strtotime('now' == $max ? date('d-m-Y') : $max));

			if ('string' == gettype($value)) {
				$value = strtotime($value);
				
				if ('' == $value || false == $value) {
					return $this->setOutput($key, 'date', array(
						'format'	=> $this->form->config['dateFormat'],
						'minDate' 	=> $min,
						'maxDate' 	=> $max
					));
				} else if ($value < strtotime($min) || $value > strtotime($max)) {
					return $this->setOutput($key, trim(__FUNCTION__, '_'), array(
						'format'	=> $this->form->config['dateFormat'],
						'minDate' 	=> $min,
						'maxDate' 	=> $max
					));
				}
				
				$this->form->setValue($key, strftime($this->form->properties['dateFormat'], $value));
			}
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @param String $key.
		 * @return Boolean.
		 */
		public function isValid($key = null) {
			if (null === $key) {
				return 0 == count($this->output) && null == $this->message;
			}
			
			return !isset($this->output[$key]);
		}
		
		/**
		 * @acces public.
		 * @param String $message.
		 */
		public function setBulkOutput($message) {
			$this->message = $message;
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getBulkOutput() {
			$errors = array();
			
			$i = 0;

			foreach ($this->output as $error) {
				$class = array();
				
				if (0 == $i) {
					$class[] = 'first';
				}
				
				if (count($this->errors) - 1 == $i) {
					$class[] = 'last';
				}
				
				$class[] = 0 == $i % 2 ? 'odd' : 'even';
				
				$errors[] = $this->form->getTemplate($this->form->properties['tplBulkError'], array_merge(array(
					'class'	=> implode(' ', $class)
				), $error));
				
				$i++;
			}

			return $this->form->getTemplate($this->form->properties['tplBulkWrapper'], array(
				'error'		=> null == $this->message ? $this->modx->lexicon('form.error_bulk') : $this->message,
				'total'		=> count($this->errors),
				'output' 	=> implode(PHP_EOL, $errors)
			));
		}

		/**
		 * @acces public.
		 * @param String $key.
		 * @param String $value.
		 */
		public function setOutput($key, $error, $properties = array(), $message = null) {
			$this->output[$key] = array_merge(array(
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
		 * @return Array.
		 */
		public function getOutput($key = null) {
			if (null !== $key) {
				if (isset($this->output[$key])) {
					return $this->output[$key];
				}
				
				return null;
			}
			
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