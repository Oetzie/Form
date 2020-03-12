<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FormFormsGetNodesProcessor extends modObjectGetListProcessor
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
    public $defaultSortField = 'Form.name';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'ASC';

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
            'modContext.key:IN' => $this->getAvailableContexts()
        ]);

        $context = $this->getProperty('context');

        if (!empty($context)) {
            $criteria->where([
                'modResource.context_key' => $context
            ]);
        }

        $criteria->groupby('Form.name');

        return $criteria;
    }

    /**
     * @access public.
     * @param xPDOObject $object.
     * @return Array.
     */
    public function prepareRow(xPDOObject $object)
    {
        return [
            'id'            => $object->get('id'),
            'name'          => $object->get('name'),
            'context_key'   => $object->get('context_key'),
            'context_name'  => $object->get('context_name')
        ];
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

return 'FormFormsGetNodesProcessor';
