<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FormFormsResetProcessor extends modObjectProcessor
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
        $this->modx->removeCollection($this->classKey, []);

        return $this->outputArray([]);
    }
}

return 'FormFormsResetProcessor';
