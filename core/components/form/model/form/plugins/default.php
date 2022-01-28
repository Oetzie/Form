<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class DefaultFormPlugin
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
     * @return Boolean.
     */
    public function onBeforePost()
    {
        return true;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function onValidatePost()
    {
        return true;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function onValidateFailed()
    {
        return true;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function onValidateSuccess()
    {
        return true;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function onAfterPost()
    {
        return true;
    }
}
