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

	class FormSaveGetListProcessor extends modObjectGetListProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'FormSave';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('form:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortField = 'id';
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortDirection = 'DESC';
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'form.formsave';
		
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $form;
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->form = $this->modx->getService('form', 'Form', $this->modx->getOption('form.core_path', null, $this->modx->getOption('core_path').'components/form/').'model/form/');
			
			$this->setDefaultProperties(array(
				'dateFormat' => $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format')
			));
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @param Object $c.
		 * @return Object.
		 */
		public function prepareQueryBeforeCount(xPDOQuery $c) {
			$c->innerjoin('modResource', 'modResource', array('modResource.id = FormSave.resource_id'));
			$c->select($this->modx->getSelectColumns('FormSave', 'FormSave'));
			$c->select($this->modx->getSelectColumns('modResource', 'modResource', 'resource_', array('pagetitle', 'longtitle', 'context_key')));
			
			$context = $this->getProperty('context');
			
			if (!empty($context)) {
				$c->where(array('modResource.context_key' => $context));
			}
			
			$status = $this->getProperty('status');
			
			if ('' != $status) {
				$c->where(array(
					'FormSave.active' => $status
				));
			}
			
			$query = $this->getProperty('query');
			
			if (!empty($query)) {
				$c->where(array(
					'FormSave.name:LIKE' 			=> '%'.$query.'%',
					'OR:FormSave.data:LIKE' 		=> '%'.$query.'%',
					'OR:modResource.pagetitle:LIKE' => '%'.$query.'%',
					'OR:modResource.longtitle:LIKE' => '%'.$query.'%'
				));
			}
			
			return $c;
		}
		
		/**
		 * @acces public.
		 * @param Object $query.
		 * @return Array.
		 */
		public function prepareRow(xPDOObject $object) {
			$array = array_merge($object->toArray(), array(
				'resource_url'			=> $this->modx->makeUrl($object->resource_id, '', '', 'full'),
				'resource_name' 		=> empty($object->resource_longtitle) ? $object->resource_pagetitle : $object->resource_longtitle,
				'resource_name_alias' 	=> (empty($object->resource_longtitle) ? $object->resource_pagetitle : $object->resource_longtitle).' ('.$object->resource_id.')',
				'data'					=> unserialize($object->data),
				'data_formatted'		=> implode(', ', array_map(function($value) {
					if (is_array($value['value'])) {
						$output = array();
						
						foreach ($value['value'] as $key) {
							if (isset($value['values'][$key])) {
								$output[] = $value['values'][$key];
							}
						}
						
						$output = implode(', ', $output);
					} else if (isset($value['values'][$value['value']])) {
						$output = $value['values'][$value['value']];
					} else {
						$output = $value['value'];
					}
					
					return sprintf('<strong>%s</strong>: %s', $value['label'], $output);
				}, unserialize($object->data)))
			));

			if (in_array($array['editedon'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
				$array['editedon'] = '';
			} else {
				$array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
			}
			
			return $array;	
		}
	}

	return 'FormSaveGetListProcessor';
	
?>