<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FormFormsGetListProcessor extends modObjectGetListProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'FormForm';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['form:default'];

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortField = 'Form.id';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'DESC';

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'form.form';

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('form', 'Form', $this->modx->getOption('form.core_path', null, $this->modx->getOption('core_path') . 'components/form/') . 'model/form/');

        $this->setDefaultProperties([
            'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format')
        ]);

        return parent::initialize();
    }

    /**
     * @access public.
     * @param xPDOQuery $criteria.
     * @return xPDOQuery.
     */
    public function prepareQueryBeforeCount(xPDOQuery $criteria)
    {
        $criteria->setClassAlias('Form');

        $criteria->select($this->modx->getSelectColumns('FormForm', 'Form'));
        $criteria->select($this->modx->getSelectColumns('modContext', 'modContext', 'context_', ['key', 'name']));

        $criteria->leftJoin('modResource', 'modResource');
        $criteria->leftJoin('modContext', 'modContext', 'modContext.key = modResource.context_key');

        $criteria->where([
            'modResource.context_key:IN'    => $this->getAvailableContexts(),
            'modResource.context_key'       => $this->getProperty('context')
        ]);

        $form = $this->getProperty('form');

        if (!empty($form)) {
            $criteria->where([
                'Form.name' => $form
            ]);
        }

        if ($this->modx->form->getOption('form_save_invalid')) {
            $status = $this->getProperty('status', '');

            if ($status !== '') {
                $criteria->where([
                    'Form.active' => $status
                ]);
            }
        } else {
            $criteria->where([
                'Form.active' => 1
            ]);
        }

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'Form.name:LIKE'                => '%' . $query . '%',
                'OR:Form.data:LIKE'             => '%' . $query . '%',
                'OR:modResource.pagetitle:LIKE' => '%' . $query . '%',
                'OR:modResource.longtitle:LIKE' => '%' . $query . '%',
                'OR:Form.ip:LIKE'               => '%' . $query . '%'
            ]);
        }

        return $criteria;
    }

    /**
     * @access public.
     * @param xPDOObject $object.
     * @return Array.
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = array_merge($object->toArray(), [
            'resource_url'      => $this->modx->makeUrl($object->get('resource_id')),
            'data'              => $object->getFields(),
            'data_formatted'    => ''
        ]);

        $array['data_formatted'] = implode(', ', array_map(function($value) {
            if (is_array($value['value'])) {
                $output = [];

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

            return '<strong>' . $value['label'] . '</strong>: ' . $output;
        }, $array['data']));

        if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null], true)) {
            $array['editedon'] = '';
        } else {
            $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
        }

        return $array;
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getAvailableContexts()
    {
        $contexts = [];

        foreach ($this->modx->getCollection('modContext') as $context) {
            $contexts[] = $context->get('key');
        }

        return $contexts;
    }
}

return 'FormFormsGetListProcessor';
