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

	class FormHomeManagerController extends FormManagerController {
		/**
		 * @access public.
		 */
		public function loadCustomCssJs() {
			$this->addCss($this->form->config['css_url'].'mgr/form.css');
			
			$this->addJavascript($this->form->config['js_url'].'mgr/widgets/home.panel.js');
			
			$this->addJavascript($this->form->config['js_url'].'mgr/widgets/forms.grid.js');
			
			$this->addLastJavascript($this->form->config['js_url'].'mgr/sections/home.js');
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getPageTitle() {
			return $this->modx->lexicon('form');
		}
		
		/**
		* @access public.
		* @return String.
		*/
		public function getTemplateFile() {
			return $this->form->config['templates_path'].'home.tpl';
		}
	}

?>