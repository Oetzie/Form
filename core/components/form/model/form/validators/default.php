<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class DefaultFormValidator
{
    /**
     * @access public.
     * @var Object.
     */
    public $form;

    /**
     * @access public.
     * @var Array.
     */
    public $config = [];

    /**
     * @access public.
     * @param Object $form.
     * @param Array $config.
     */
    public function __construct($form, array $config = [])
    {
        $this->form     = $form;
        $this->config   = $config;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Mixed $properties.
     * @param Array $values.
     * @return Boolean|String.
     */
    public function validate($value, &$properties = null, array $values = [])
    {
        return true;
    }
}
