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

	class FormHomeManagerController extends FormManagerController {
		/**
		 * @acces public.
		 */
		public function loadCustomCssJs() {
			$this->addJavascript($this->modx->getOption('js_url', $this->form->config).'mgr/widgets/home.panel.js');
			$this->addJavascript($this->modx->getOption('js_url', $this->form->config).'mgr/widgets/formsave.grid.js');
			$this->addLastJavascript($this->modx->getOption('js_url', $this->form->config).'mgr/sections/home.js');
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getPageTitle() {
			return $this->modx->lexicon('form');
		}
		
		/**
		* @acces public.
		* @return String.
		*/
		public function getTemplateFile() {
			return $this->modx->getOption('templates_path', $this->form->config).'home.tpl';
		}
	}

?>