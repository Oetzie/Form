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
		public $scriptProperties = array();
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $extensionScriptProperties = array();
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $values = array();
		
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $validator;
		
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $extensions;
		
		/**
		 * @acces public.
		 * @param Object $modx.
		 * @param Array $config.
		*/
		function __construct(modX &$modx, array $config = array()) {
			$this->modx =& $modx;
		
			$corePath 		= $this->modx->getOption('form.core_path', $config, $this->modx->getOption('core_path').'components/form/');
			$assetsUrl 		= $this->modx->getOption('form.assets_url', $config, $this->modx->getOption('assets_url').'components/form/');
			$assetsPath 	= $this->modx->getOption('form.assets_path', $config, $this->modx->getOption('assets_path').'components/form/');
		
			$this->config = array_merge(array(
				'basePath'				=> $corePath,
				'corePath' 				=> $corePath,
				'modelPath' 			=> $corePath.'model/',
				'processorsPath' 		=> $corePath.'processors/',
				'elementsPath' 			=> $corePath.'elements/',
				'chunksPath' 			=> $corePath.'elements/chunks/',
				'snippetsPath' 			=> $corePath.'elements/snippets/',
				'templatesPath' 		=> $corePath.'templates/',
				'assetsPath' 			=> $assetsPath,
				'jsUrl' 				=> $assetsUrl.'js/',
				'cssUrl' 				=> $assetsUrl.'css/',
				'assetsUrl' 			=> $assetsUrl,
				'connectorUrl'			=> $assetsUrl.'connector.php',
				'helpurl'				=> 'form',
				'placeholderKey'		=> 'form',
				'dateFormat'			=> '%d-%m-%Y',
				'context'				=> 2 == $this->modx->getCount('modContext') ? 0 : 1
			), $config);
		
			$this->modx->addPackage('form', $this->config['modelPath']);
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getHelpUrl() {
			return $this->config['helpurl'];
		}
		
		/**
		 * @acces public.
		 * @param String $tpl.
		 * @param Array $properties.
		 * @param String $type.
		 * @return String.
		 */
		public function getTpl($tpl, $properties = array(), $type = 'chunk') {
		  	if (0 === strpos($tpl, '@')) {
			  	$type 	= substr($tpl, 1, strpos($tpl, ':') - 1);
			  	$tpl	= substr($tpl, strpos($tpl, ':') + 1, strlen($tpl));
		  	}
  
		  	switch (strtolower($type)) {
			  	case 'inline':
				  	$chunk = $this->modx->newObject('modChunk', array('name' => sprintf('form-%s', uniqid())));
  
				  	$chunk->setCacheable(false);
  
				  	$output = $chunk->process($properties, $tpl);
  
				  	break;
			  	case 'chunk':
				  	$output = $this->modx->getChunk($tpl, $properties);
  
				  	break;
		  	}
  
		  	return $output;
	  	}
		
		/**
		 * @acces public.
		 * @param Array $properties.
		 */
		public function setScriptProperties($scriptProperties = array()) {
			foreach ($scriptProperties as $key => $value) {
				if (array_key_exists($key, $this->config)) {
					$this->config[$key] = $value;
				} else if (in_array($key, array('extensions', 'validate', 'redirect', 'tplError', 'tplBulkError', 'tplBulkWrapper'))) {
					$this->scriptProperties[$key] = $value;
				} else {
					$this->extensionScriptProperties[$key] = $value;
				}
			}
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getExtensionScriptProperties() {
			return $this->extensionScriptProperties;
		}
		
		/**
		 * @acces public.
		 * @param Array $request.
		 * @return Boolean.
		 */
		public function setRequest($request, $method = 'POST') {
			$this->setValues($request);

			if (false !== ($validator = $this->setValidator())) {
				if (false !== ($extensions = $this->setExtensions())) {
					$extensions->setExtensions('Before');
					
					if ($this->getRequestMethod($method)) {
						if ($validator->validate()) {
							$this->setCacheForm();
						}
						
						if ($extensions->setExtensions('After')) {
							$this->redirect();
						}
					}
				}
			}
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function getRequest() {
			if (false !== ($request = $this->getCacheForm())) {
				$this->setValues($request);
			} else {
				$this->redirect();
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function setValidator() {
			if ($this->modx->loadClass('form.FormValidator', $this->config['modelPath'], true, true)) {
				$this->validator = new FormValidator($this->modx, $this);
				
				return $this->validator;
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not load "form.FormValidator" class.');
				
				return false;
			}
		}
		
		/**
		 * @acces public.
		 * @return Object.
		 */
		public function getValidator() {
			if (null === $this->validator) {
				$this->setValidator();
			}
			
			return $this->validator;
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function setExtensions() {
			if ($this->modx->loadClass('form.FormExtensions', $this->config['modelPath'], true, true)) {
				$this->extensions = new FormExtensions($this->modx, $this);
				
				return $this->extensions;
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not load "form.FormExtensions" class.');
				
				return false;
			}
		}
		
		/**
		 * @acces public.
		 * @return Object.
		 */
		public function getExtensions() {
			if (null === $this->extensions) {
				$this->setExtensions();
			}
			
			return $this->extensions;
		}
		
		/**
		 * @acces public.
		 * @param String $prefix.
		 * @return String.
		 */
		public function getPlaceholderKey($prefix = '') {
			return $this->config['placeholderKey'].'.'.$prefix;
		}
		
		/**
		 * @acces procted.
		 * @param String $method.
		 * @return Boolean.
		 */
		protected function getRequestMethod($method) {
			return strtoupper($method) == strtoupper($_SERVER['REQUEST_METHOD']);
		}
		
		/**
		 * @acces public.
		 * @param Array $request.
		 */
		protected function setValues($request) {
			foreach ($request as $key => $value) {
				$this->setValue($key, $value);
			}
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getValues() {
			return $this->values;
		}
		
		/**
		 * @acces public.
		 * @param String $key.
		 * @param String|Array $value.
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
			
			$this->modx->setPlaceholder($this->getPlaceholderKey().$key, is_array($value) ? implode(',', $value) : $value);
		}
		
		/**
		 * @acces public.
		 * @param String $key.
		 * @return String|Array.
		 */
		public function getValue($key) {
			if (array_key_exists($key, $this->values)) {
				return $this->values[$key];
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function isValid() {
			if (null !== ($validator = $this->getValidator())) {
				return $validator->isValid();
			}
			
			return false;
		}
		
		/**
		 * @acces protected.
		 */
		protected function redirect() {
			if (false !== ($redirect = $this->modx->getOption('redirect', $this->scriptProperties, false))) {
				$this->modx->sendRedirect($this->modx->makeUrl($redirect, null, null, 'full'));
			}
			
			return false;
		}
		
		
		/**
		 * @acces public.
		 * @param String $option.
		 * @return Array.
		 */
		public function getCacheOptions($option = null) {
			$options = array(
				xPDO::OPT_CACHE_KEY 	=> 'form',
				xPDO::OPT_CACHE_HANDLER => $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache'),
				xPDO::OPT_CACHE_EXPIRES => 60
			);
			
			if (array_key_exists($option, $options)) {
				return $options[$option];
			}
			
			return $options;
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function setCacheForm() {
			$_SESSION[$this->getCacheOptions(xPDO::OPT_CACHE_KEY)] = $cacheKey = $this->getPlaceholderKey(time());
				
			if (false !== ($cache = $this->modx->cacheManager->set($cacheKey, $this->getValues(), $this->getCacheOptions(xPDO::OPT_CACHE_EXPIRES), $this->getCacheOptions()))) {
				return $cache;
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form] Could not save form ('.$cacheKey.')');
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function getCacheForm() {
			if (array_key_exists($this->getCacheOptions(xPDO::OPT_CACHE_KEY), $_SESSION)) {
				if (null !== ($cache = $this->modx->cacheManager->get($_SESSION[$this->getCacheOptions(xPDO::OPT_CACHE_KEY)], $this->getCacheOptions()))) {
					$this->modx->cacheManager->clearCache(array($this->getCacheOptions(xPDO::OPT_CACHE_KEY)));
					
					return $cache;
				}
			}
			
			return false;
		}
	}
	
?>