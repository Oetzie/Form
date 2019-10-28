<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FormFormsCleanProcessor extends modObjectProcessor
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
     * @return Mixed.
     */
    public function process()
    {
        $amount = 0;

        $criteria = $this->modx->newQuery('FormForm');

        $criteria->setClassAlias('Form');

        $criteria->select($this->modx->getSelectColumns('FormForm', 'Form'));
        $criteria->select($this->modx->getSelectColumns('modContext', 'modContext', 'context_', ['key', 'name']));

        $criteria->leftJoin('modResource', 'modResource');
        $criteria->leftJoin('modContext', 'modContext', 'modContext.key = modResource.context_key');

        $criteria->where([
            'modContext.key:IN' => $this->getAvailableContexts(),
            'Form.editedon:<'   => date('Y-m-d 00:00:00', strtotime('-' . $this->getProperty('days', $this->modx->form->getOption('clean_days')) . ' days'))
        ]);

        foreach ($this->modx->getCollection('FormForm', $criteria) as $form) {
            if ($form->remove()) {
                $amount++;
            }
        }

        return $this->success($this->modx->lexicon('form.forms_clean_success', [
            'amount' => $amount
        ]));
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

return 'FormFormsCleanProcessor';
