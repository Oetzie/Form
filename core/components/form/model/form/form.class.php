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

	class Form {
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $config = array();
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $properties = array();
		
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $validator = null;
		
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $extensions = null;
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $values = array();

		/**
		 * @acces public.
		 * @param Object $modx.
		 * @param Array $config.
		 */
		public function __construct(modX &$modx, array $config = array()) {
			$this->modx =& $modx;

			$corePath 		= $this->modx->getOption('form.core_path', $config, $this->modx->getOption('core_path').'components/form/');
			$assetsUrl 		= $this->modx->getOption('form.assets_url', $config, $this->modx->getOption('assets_url').'components/form/');
			$assetsPath 	= $this->modx->getOption('form.assets_path', $config, $this->modx->getOption('assets_path').'components/form/');
		
			$this->config = array_merge(array(
				'namespace'				=> $this->modx->getOption('namespace', $config, 'form'),
				'helpurl'				=> $this->modx->getOption('helpurl', $config, 'form'),
				'lexicons'				=> array('form:default', 'form:site'),
				'base_path'				=> $corePath,
				'core_path' 			=> $corePath,
				'model_path' 			=> $corePath.'model/',
				'processors_path' 		=> $corePath.'processors/',
				'elements_path' 		=> $corePath.'elements/',
				'chunks_path' 			=> $corePath.'elements/chunks/',
				'cronjobs_path' 		=> $corePath.'elements/cronjobs/',
				'plugins_path' 			=> $corePath.'elements/plugins/',
				'snippets_path' 		=> $corePath.'elements/snippets/',
				'assets_path' 			=> $assetsPath,
				'js_url' 				=> $assetsUrl.'js/',
				'css_url' 				=> $assetsUrl.'css/',
				'assets_url' 			=> $assetsUrl,
				'connector_url'			=> $assetsUrl.'connector.php',
				'context'				=> $this->getContexts()
			), $config);
			
			$this->modx->addPackage('form', $this->config['model_path']);
			
			if (is_array($this->config['lexicons'])) {
				foreach ($this->config['lexicons'] as $lexicon) {
					$this->modx->lexicon->load($lexicon);
				}
			} else {
				$this->modx->lexicon->load($this->config['lexicons']);
			}
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getHelpUrl() {
			return $this->config['helpurl'];
		}
		
		/**
		 * @acces private.
		 * @return Boolean.
		 */
		private function getContexts() {
			return 1 == $this->modx->getCount('modContext', array(
				'key:!=' => 'mgr'
			));
		}
				
		/**
		 * @acces protected.
		 * @param String $tpl.
		 * @param Array $properties.
		 * @param String $type.
		 * @return String.
		 */
		public function getTemplate($template, $properties = array(), $type = 'CHUNK') {
			if (0 === strpos($template, '@')) {
				$type 		= substr($template, 1, strpos($template, ':') - 1);
				$template	= substr($template, strpos($template, ':') + 1, strlen($template));
			}
			
			switch (strtoupper($type)) {
				case 'INLINE':
					$chunk = $this->modx->newObject('modChunk', array(
						'name' => $this->config['namespace'].uniqid()
					));
				
					$chunk->setCacheable(false);
				
					$output = $chunk->process($properties, ltrim($template));
				
					break;
				case 'CHUNK':
					$output = $this->modx->getChunk(ltrim($template), $properties);
				
					break;
			}
			
			return $output;
		}
		
		/**
		 * @acces public.
		 * @param Array $scriptProperties.
		 * @return Boolean.
		 */
		public function setScriptProperties($scriptProperties = array()) {
			$this->properties = array_merge(array(
				'dateFormat'		=> '%d-%m-%Y',
				'extensions'		=> '',
				'placeholder' 		=> 'form',
				'submit'			=> 'submit',
				'method'			=> 'post',
				'handler'			=> 'reload',
				'tplBulkError'		=> '@INLINE:<li class="[[+class]]">[[+error]]</li>',
				'tplBulkWrapper'	=> '@INLINE:<p class="error-notices">[[+error]]</p>',
				'tplError'			=> '@INLINE:<div class="error-notice-desc"><span class="error-notice-desc-inner">[[+error]]</div>',
				'type'				=> 'set',
				'validate'			=> '{}'
			), $this->properties, $scriptProperties);
			
			return $this->setDefaultProperties();
		}
				
		/**
		 * @acces protected.
		 * @return Boolean.
		 */
		protected function setDefaultProperties() {
			$this->properties['placeholder'] = rtrim($this->properties['placeholder'], '.').'.';
			
			if (is_string($this->properties['validate'])) {
				$this->properties['validate'] = $this->modx->fromJSON($this->properties['validate']);
				
				foreach ($this->properties['validate'] as $key => $value) {
					if (!is_array($value)) {
						$this->properties['validate'][$key] = array(
							$value => 'true'	
						);
					}
				}
			}
			
			if (is_string($this->properties['extensions'])) {
				$this->properties['extensions'] = explode(',', $this->properties['extensions']);
			}
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @param Array $properties.
		 * @return String.
		 */
		public function run($properties = array()) {
			$this->setScriptProperties($properties);
			
			if ('set' == $this->properties['type']) {
				return $this->setForm();
			} else if ('get' == $this->properties['type']) {
				return $this->getForm();
			}
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function setForm() {
			if ($validator = $this->getValidator()) {
				if ($extensions = $this->getExtensions()) {
					$output = array();
					
					$output[$this->properties['placeholder'].'method'] = 'post';
					$output[$this->properties['placeholder'].'submit'] = $this->properties['submit'];
					
					if ('reload' != $this->properties['handler']) {
						$output[$this->properties['placeholder'].'url'] = $this->modx->makeUrl($this->properties['handler'], null, $this->modx->request->getParameters());
					} else {
						$output[$this->properties['placeholder'].'url'] = $this->modx->makeUrl($this->modx->resource->id, null, $this->modx->request->getParameters());
					}
					
					foreach ($extensions->setExtentions('Before') as $key => $value) {
						$output[$this->properties['placeholder'].'extensions.'.$key] = $value;
					}

					foreach ($this->getValuesPlaceholders() as $key => $value) {
						$output[$this->properties['placeholder'].$key] = $value;
					}
					
					if ($this->getMethod('POST', $this->modx->request->getParameters(array(), 'POST'))) {
						$this->setValues($this->modx->request->getParameters(array(), 'POST'));
						
						$output[$this->properties['placeholder'].'submitted'] = true;
						
						foreach ($this->getValuesPlaceholders() as $key => $value) {
							$output[$this->properties['placeholder'].$key] = $value;
						}
						
						$validator->validate();

						foreach ($extensions->setExtentions('After') as $key => $value) {
							$output[$this->properties['placeholder'].$key] = $value;
						}

						if (!$validator->isValid()) {
							$output[$this->properties['placeholder'].'error'] = $validator->getBulkOutput();
							
							foreach ($validator->getOutput() as $key => $value) {
								$output[$this->properties['placeholder'].'error.'.$key] = $this->getTemplate($this->properties['tplError'], $value);
							}
						} else {
							$this->setCacheForm($this->getValues());
							
							if (isset($this->properties['success'])) {
								if ('reload' == $this->properties['success']) {
									$this->modx->sendRedirect($this->modx->makeUrl($this->modx->resource->id, null, $this->modx->request->getParameters(), 'full'));
								} else {
									if ('get' == $this->properties['method']) {
										$this->modx->sendRedirect($this->modx->makeUrl($this->properties['success'], null, $this->getValues(), 'full'));
									} else {
										$this->modx->sendRedirect($this->modx->makeUrl($this->properties['success'], null, null, 'full'));
									}
								}
							}
							
							if (isset($this->properties['tplValidated'])) {
								if (isset($this->properties['tplWrapper'])) {
									return $this->getTemplate($this->properties['tplWrapper'], array_merge($output, array(
										'output' => $this->getTemplate($this->properties['tplValidated'], $output)
									)));
								} else {
									return $this->getTemplate($this->properties['tplValidated'], $output);
								}
							}
						}
					}

					if (isset($this->properties['tplWrapper'])) {
						return $this->getTemplate($this->properties['tplWrapper'], array_merge($output, array(
							'output' => $this->getTemplate($this->properties['tpl'], $output)
						)));
					} else {
						return $this->getTemplate($this->properties['tpl'], $output);
					}
				}
			}
			return '';
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getForm() {
			if ($values = $this->getCacheForm()) {
				$this->modx->setPlaceholders($values);
			} else {
				if (!isset($this->properties['redirect'])) {
					$this->modx->sendRedirect($this->modx->makeUrl($this->modx->resource->parent, null, null, 'full'));
				} else {
					$this->modx->sendRedirect($this->modx->makeUrl($this->properties['redirect'], null, null, 'full'));
				}
			}
			
			return '';
		}
		
		/**
		 * @acces protected.
		 */
		protected function setValidator() {
			if ($this->modx->loadClass('FormValidator', $this->modx->getOption('form.core_path', null, $this->modx->getOption('core_path').'components/form/').'model/form/', true, true)) {
		        $validator = new FormValidator($this->modx, $this);
		        
		        if ($validator instanceOf FormValidator) {
			    	$this->validator = $validator;
				}
			}
		}
		
		/**
		 * @acces public.
		 * @return Object.
		 */
		public function getValidator() {
			if (null == $this->validator) {
				$this->setValidator();
			}
			
			return $this->validator;
		}
		
		/**
		 * @acces protected.
		 */
		protected function setExtensions() {
			if ($this->modx->loadClass('FormExtensions', $this->modx->getOption('form.core_path', null, $this->modx->getOption('core_path').'components/form/').'model/form/', true, true)) {
		        $extensions = new FormExtensions($this->modx, $this);
		        
		        if ($extensions instanceOf FormExtensions) {
			    	$this->extensions = $extensions;
				}
			}
		}
		
		/**
		 * @acces public.
		 * @return Object.
		 */
		public function getExtensions() {
			if (null == $this->extensions) {
				$this->setExtensions();
			}
			
			return $this->extensions;
		}
		
		/**
		 * @acces protected.
		 * @param String $type.
		 * @param Array $values.
		 * @return Boolean.
		 */
		protected function getMethod($type, $values = array()) {
			return strtoupper($type) == strtoupper($_SERVER['REQUEST_METHOD']) && isset($values[$this->properties['submit']]);
		}
		
		/**
		 * @acces public.
		 * @param Array $values.
		 * @return null.
		 */
		public function setValues($values) {
			foreach ($values as $key => $value) {
				$this->setValue($key, $value);
			}
		}
		
		/**
		 * @acces public.
		 * @return Boolean $all.
		 * @return Array.
		 */
		public function getValues($all = false) {
			if ($all) {
				return $this->values;
			}
			
			$values = array();
			
			foreach ($this->values as $key => $value) {
				if ($key != $this->properties['submit']) {
					$values[$key] = $value;
				}
			}
			
			return $values;
		}
		
		/**
		 * @acces public.
		 * @return Boolean $all.
		 * @return Array.
		 */
		public function getValuesPlaceholders($all = false) {
			$values = $this->getValues();
			
			foreach ($values as $key => $value) {
				if (is_array($value)) {
					$values[$key] = implode(',', $value);
				}
			}
			
			return $values;
		}
		
		/**
		 * @acces public.
		 * @param String $key.
		 * @param String|Array $value.
		 * @return boolean.
		 */
		public function setValue($key, $value) {
			switch (gettype($value)) {
				case 'array':
					$value = array_filter($value);
					
					break;
				case 'string':
					$value = trim($value);
					
					break;	
			}
			
			$this->values[$key] = $value;
			
			return true;
		}
		
		/**
		 * @acces public.
		 * param String $key.
		 * @return String|Array.
		 */
		public function getValue($key, $default = false) {
			if (isset($this->values[$key])) {
				return $this->values[$key];
			}
			
			return $default;
		}
		
		/**
		 * @acces public.
		 * @param String $option.
		 * @return Array.
		 */
		public function getCacheOptions($option = null) {
			$options = array(
				xPDO::OPT_CACHE_KEY 	=> $this->properties['placeholder'].$this->properties['submit'],
				xPDO::OPT_CACHE_HANDLER => $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache'),
				xPDO::OPT_CACHE_EXPIRES => 60
			);
			
			if (isset($options[$option])) {
				return $options[$option];
			}
			
			return $options;
		}
		
		/**
		 * @acces protected.
		 * @return String.
		 */
		protected function setCacheKey() {
			return $_SESSION[$this->getCacheOptions(xPDO::OPT_CACHE_KEY)] = $this->properties['placeholder'].time();
		}
		
		/**
		 * @acces protected.
		 * @return String.
		 */
		protected function getCacheKey() {
			if (!isset($_SESSION[$this->getCacheOptions(xPDO::OPT_CACHE_KEY)])) {
				return $this->setCacheKey();
			}
			
			return $_SESSION[$this->getCacheOptions(xPDO::OPT_CACHE_KEY)];
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		protected function setCacheForm($output) {			
			return $this->modx->cacheManager->set($this->getCacheKey(), $output, $this->getCacheOptions(xPDO::OPT_CACHE_EXPIRES), $this->getCacheOptions());
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		protected function getCacheForm() {
			if (null !== ($cache = $this->modx->cacheManager->get($this->getCacheKey(), $this->getCacheOptions()))) {
				$this->modx->cacheManager->clearCache(array($this->getCacheOptions(xPDO::OPT_CACHE_KEY)));
				
				return $cache;
			}
			
			return false;
		}
	}

?>