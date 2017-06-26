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
	 
	class FormFormsResetProcessor extends modObjectProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'FormForms';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('form:default');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'form.formsave';
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->form = $this->modx->getService('form', 'Form', $this->modx->getOption('form.core_path', null, $this->modx->getOption('core_path').'components/form/').'model/form/');
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function process() {
			$this->modx->removeCollection($this->classKey, array());
			
			return $this->outputArray(array());
		}
	}
	
	return 'FormFormsResetProcessor';
	
?>