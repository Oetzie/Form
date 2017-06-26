<?php

	/**
	 * Form
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
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
		 * @access public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $config = array();
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $properties = array();
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $extensions = null;
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $validator = null;
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $values = array();

		/**
		 * @access public.
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
				'templates_path' 		=> $corePath.'templates/',
				'assets_path' 			=> $assetsPath,
				'js_url' 				=> $assetsUrl.'js/',
				'css_url' 				=> $assetsUrl.'css/',
				'assets_url' 			=> $assetsUrl,
				'connector_url'			=> $assetsUrl.'connector.php',
				'version'				=> '1.2.0',
				'branding'				=> (boolean) $this->modx->getOption('form.branding', null, true),
				'branding_url'			=> 'http://www.oetzie.nl',
				'branding_help_url'		=> 'http://www.werkvanoetzie.nl/extras/form',
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
		 * @access public.
		 * @return String.
		 */
		public function getHelpUrl() {
			return $this->config['branding_help_url'].'?v='.$this->config['version'];
		}
		
		/**
		 * @access private.
		 * @return Boolean.
		 */
		private function getContexts() {
			return 1 == $this->modx->getCount('modContext', array(
				'key:!=' => 'mgr'
			));
		}
				
		/**
		 * @access protected.
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
		 * @access public.
		 * @param Array $scriptProperties.
		 * @return Boolean.
		 */
		public function setScriptProperties($scriptProperties = array()) {
			$this->properties = array_merge(array(
				'action'			=> 'self',
				'extensions'		=> '',
				'handler'			=> 'submit',
				'method'			=> 'POST',
				'prefix'			=> 'form',
				'tplBulkError'		=> '@INLINE:<li class="[[+class]]">[[+error]]</li>',
				'tplBulkWrapper'	=> '@INLINE:<p class="error-notices">[[+error]]</p>',
				'tplError'			=> '@INLINE:<div class="error-notice-desc">[[+error]]</div>',
				'type'				=> 'SET',
				'validation'		=> '{}'
			), $this->properties, $scriptProperties);
			
			return $this->setDefaultProperties();
		}
		
		/**
		 * @access protected.
		 * @return Boolean.
		 */
		protected function setDefaultProperties() {
			$this->properties['prefix'] = rtrim($this->properties['prefix'], '.').'.';
			
			$this->properties['method'] = strtoupper($this->properties['method']);
			
			if (is_string($this->properties['extensions'])) {
				$this->properties['extensions'] = explode(',', $this->properties['extensions']);
			}
			
			if (is_string($this->properties['validation'])) {
				$this->properties['validation'] = $this->modx->fromJSON($this->properties['validation']);
			}
			
			$this->properties['type'] = strtoupper($this->properties['type']);
			
			return true;
		}
		
		/**
		 * @access public.
		 * @param Array $properties.
		 * @return String.
		 */
		public function run($properties = array()) {
			$this->setScriptProperties($properties);
			
			if ('GET' == $this->properties['type']) {
				if (null !== ($values = $this->getCacheForm())) {
					$this->modx->setPlaceholders($values, $this->properties['prefix']);
				} else {
					if (isset($this->properties['failure'])) {
						$this->modx->sendRedirect($this->modx->makeUrl($this->properties['failure']));
					} else {
						$this->modx->sendRedirect($this->modx->makeUrl($this->modx->resource->parent));
					}
				}
			} else {
				$prefix = $this->properties['prefix'];
				
				$placeholders = array(
					$prefix.'method' 	=> 'POST',
					$prefix.'handler'	=> $this->properties['handler'],
					$prefix.'action'	=> $this->modx->makeUrl($this->modx->resource->id, null, $this->modx->request->getParameters())
				);
				
				if (!in_array($this->properties['action'], array('self', 'this', 'reload'))) {
					if (is_numeric($this->properties['action'])) {
						$placeholders[$prefix.'action'] = $this->modx->makeUrl($this->properties['action'], null, $this->modx->request->getParameters());
					} else {
						$placeholders[$prefix.'action'] = $this->properties['action'];
					}
				}
				
				foreach($this->getFormExtensions()->invokeExtensions('onBeforePost') as $key => $extension) {
					$placeholders[$prefix.'extension.'.$key] = $extension;
				}
				
				foreach ($this->getValues(true) as $key => $value) {
					$placeholders[$prefix.$key] = $value;
				}
				
				if ($this->isMethod('POST', $this->modx->request->getParameters(array(), 'POST'))) {
					$placeholders[$prefix.'state'] = 'active';
					
					$this->getFormValidator()->validate();
					
					foreach($this->getFormExtensions()->invokeExtensions('onValidatePost') as $key => $extension) {
						$placeholders[$prefix.'extensions.'.$key] = $extension;
					}
					
					foreach ($this->getValues(true) as $key => $value) {
						$placeholders[$prefix.$key] = $value;
					}
					
					if ($this->getFormValidator()->isValid()) {
						foreach($this->getFormExtensions()->invokeExtensions('onSuccessPost') as $key => $extension) {
							$placeholders[$prefix.'extensions.'.$key] = $extension;
						}
					} else {
						foreach($this->getFormExtensions()->invokeExtensions('onFailurePost') as $key => $extension) {
							$placeholders[$prefix.'extensions.'.$key] = $extension;
						}
					}
					
					if ($this->getFormValidator()->isValid()) {
						$this->setCacheForm($this->getValues());
						
						if (isset($this->properties['success'])) {
							if (in_array($this->properties['success'], array('self', 'this', 'reload'))) {
								if ('GET' == $this->properties['method']) {
									$this->modx->sendRedirect($this->modx->makeUrl($this->modx->resource->id, null, array_merge($this->modx->request->getParameters(), $this->getValues())));
								} else{
									$this->modx->sendRedirect($this->modx->makeUrl($this->modx->resource->id, null, $this->modx->request->getParameters()));
								}
							} else {
								if (is_numeric($this->properties['success'])) {
									if ('GET' == $this->properties['method']) {
										$this->modx->sendRedirect($this->modx->makeUrl($this->properties['success'], null, $this->getValues()));
									} else {
										$this->modx->sendRedirect($this->modx->makeUrl($this->properties['success']));
									}
								} else {
									$this->modx->sendRedirect($this->properties['success']);
								}
							}
						}
						
						if (isset($this->properties['tplSuccess'])) {
							$this->properties['tpl'] = $this->properties['tplSuccess'];
						}
					} else {
						if (null !== ($bulk = $this->getFormValidator()->getBulkError())) {
							$placeholders[$prefix.'error'] = $bulk;
								
							foreach ($this->getFormValidator()->getErrors() as $key => $error) {
								$placeholders[$prefix.'error.'.$key] = $this->getTemplate($this->properties['tplError'], $error);
							}
						}
						
						if (isset($this->properties['tplFailure'])) {
							$this->properties['tpl'] = $this->properties['tplFailure'];
						}
					}
				}
				
				if (isset($this->properties['tplWrapper'])) {
					return $this->getTemplate($this->properties['tplWrapper'], array_merge($placeholders, array(
						'output' => $this->getTemplate($this->properties['tpl'], $placeholders)
					)));
				} else {
					return $this->getTemplate($this->properties['tpl'], $placeholders);
				}
			}
			
			return '';
		}
		
		/**
		 * @access private.
		 * @return Boolean|Object.
		 */
		private function setFormExtensions() {
			if ($this->modx->loadClass('FormExtensions', $this->modx->getOption('form.core_path', null, $this->modx->getOption('core_path').'components/form/').'model/form/', true, true)) {
		        $extensions = new FormExtensions($this->modx, $this);
		        
		        if ($extensions instanceOf FormExtensions) {
			    	return $this->extensions = $extensions;
				}
			}
			
			return false;
		}
		
		/**
		 * @access private.
		 * @return Null|Object.
		 */
		private function getFormExtensions() {
			if (null === $this->extensions) {
				$this->setFormExtensions();
			}
			
			return $this->extensions;
		}
		
		/**
		 * @access private.
		 * @return Boolean|Object.
		 */
		private function setFormValidator() {
			if ($this->modx->loadClass('FormValidator', $this->modx->getOption('form.core_path', null, $this->modx->getOption('core_path').'components/form/').'model/form/', true, true)) {
		        $validator = new FormValidator($this->modx, $this);
		        
		        if ($validator instanceOf FormValidator) {
			    	return $this->validator = $validator;
				}
			}
			
			return false;
		}
		
		/**
		 * @access private.
		 * @return Null|Object.
		 */
		private function getFormValidator() {
			if (null === $this->validator) {
				$this->setFormValidator();
			}
			
			return $this->validator;
		}
		
		/**
		 * @access private.
		 * @param String $method.
		 * @param Array $values.
		 * @return Boolean.
		 */
		private function isMethod($method, $values = array()) {
			$state = strtoupper($method) == $_SERVER['REQUEST_METHOD'] && isset($values[$this->properties['handler']]);
			
			if ($state) {
				unset($values[$this->properties['handler']]);
				
				$this->setValues($values);
				$this->setValues($_FILES);
			}
			
			return $state;
		}
		
		/**
		 * @access public.
		 * @param String $key.
		 * @param String|Array $value.
		 */
		public function setValue($key, $value) {
			switch (gettype($value)) {
				case 'array':
					$this->values[$key] = array_filter($value);
					
					break;
				case 'string':
					$this->values[$key] = trim($value);
					
					break;
			}
		}
		
		/**
		 * @access public.
		 * @param String $key.
		 * @param Mixed $default.
		 * @return Mixed.
		 */
		public function getValue($key, $default = null) {
			if (isset($this->values[$key])) {
				return $this->values[$key];
			}
			
			return $default;
		}
		
		
		/**
		 * @access public.
		 * @param Array $values.
		 */
		public function setValues($values) {
			foreach ($values as $key => $value) {
				$this->setValue($key, $value);
			}
		}
		
		/**
		 * @access public.
		 * @param Boolean $string.
		 * @return Array.
		 */
		public function getValues($string = false) {
			$values = $this->values;
			
			foreach ($values as $key => $value) {
				if ($string && is_array($value)) {
					if (isset($value['name'])) {
						$values[$key] = $value['name'];
					} else {
						$values[$key] = implode(',', $value);
					}
				}
			}
			
			return $values;
		}
		
		/**
		 * @access public.
		 * @param String $option.
		 * @return Array.
		 */
		public function getCacheOptions($option = null) {
			$options = array(
				xPDO::OPT_CACHE_KEY 	=> rtrim($this->properties['prefix'], '.').'/'.$this->properties['handler'].'/'.md5(session_id()),
				xPDO::OPT_CACHE_HANDLER => $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache'),
				xPDO::OPT_CACHE_EXPIRES => 60
			);
			
			if (isset($options[$option])) {
				return $options[$option];
			}
			
			return $options;
		}
		
		/**
		 * @access public.
		 * @return Boolean.
		 */
		protected function setCacheForm($output) {			
			return $this->modx->cacheManager->set($this->getCacheOptions(xPDO::OPT_CACHE_KEY), $output, $this->getCacheOptions(xPDO::OPT_CACHE_EXPIRES));
		}
		
		/**
		 * @access public.
		 * @return Boolean.
		 */
		protected function getCacheForm() {
			if (null !== ($output = $this->modx->cacheManager->get($this->getCacheOptions(xPDO::OPT_CACHE_KEY)))) {
				$this->modx->cacheManager->delete($this->getCacheOptions(xPDO::OPT_CACHE_KEY));
				
				return $output;
			}
			
			return null;
		}
	}

?>