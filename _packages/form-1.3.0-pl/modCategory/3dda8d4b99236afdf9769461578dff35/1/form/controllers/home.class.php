<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(__DIR__) . '/index.class.php';

class FormHomeManagerController extends FormManagerController
{
    /**
     * @access public.
     */
    public function loadCustomCssJs()
    {
        $this->addJavascript($this->modx->form->config['js_url'] . 'mgr/widgets/home.panel.js');

        $this->addJavascript($this->modx->form->config['js_url'] . 'mgr/widgets/forms.grid.js');

        $this->addLastJavascript($this->modx->form->config['js_url'] . 'mgr/sections/home.js');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('form');
    }

    /**
    * @access public.
    * @return String.
    */
    public function getTemplateFile()
    {
        return $this->modx->form->config['templates_path'] . 'home.tpl';
    }
}
