<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

abstract class FormManagerController extends modExtraManagerController
{
    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('form', 'Form', $this->modx->getOption('form.core_path', null, $this->modx->getOption('core_path') . 'components/form/') . 'model/form/');

        $this->addCss($this->modx->form->config['css_url'] . 'mgr/form.css');

        $this->addJavascript($this->modx->form->config['js_url'] . 'mgr/form.js');

        $this->addJavascript($this->modx->form->config['js_url'] . 'mgr/extras/extras.js');

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.config.help_url = "' . $this->modx->form->getHelpUrl() . '";
        
                Form.config = ' . $this->modx->toJSON(array_merge($this->modx->form->config, [
                    'branding_url'          => $this->modx->form->getBrandingUrl(),
                    'branding_url_help'     => $this->modx->form->getHelpUrl()
                ])) . ';
            });
        </script>');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getLanguageTopics()
    {
        return $this->modx->form->config['lexicons'];
    }

    /**
     * @access public.
     * @returns Boolean.
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('form');
    }
}

class IndexManagerController extends FormManagerController
{
    /**
     * @access public.
     * @return String.
     */
    public static function getDefaultController()
    {
        return 'home';
    }
}
