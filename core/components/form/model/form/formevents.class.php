<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

include_once  __DIR__ . '/plugins/default.php';

include_once  __DIR__ . '/plugins/email.php';
include_once  __DIR__ . '/plugins/recaptcha.php';
include_once  __DIR__ . '/plugins/save.php';
include_once  __DIR__ . '/plugins/uploads.php';

class FormEvents
{
    const BEFORE_POST       = 'onBeforePost';
    const VALIDATE_POST     = 'onValidatePost';
    const VALIDATE_FAILED   = 'onValidateFailed';
    const VALIDATE_SUCCESS  = 'onValidateSuccess';
    const AFTER_POST        = 'onAfterPost';

    /**
     * @access public.
     * @var modX.
     */
    public $modx;

    /**
     * @access public.
     * @var Object.
     */
    public $form;

    /**
     * @access public.
     * @var Array.
     */
    public $plugins = [];

    /**
     * @access public.
     * @var Array.
     */
    public $values = [];

    /**
     * @access public.
     *
     * @param modX   $modx .
     * @param Object $form .
     */
    public function __construct(modX &$modx, $form)
    {
        $this->modx =& $modx;
        $this->form =& $form;
    }

    /**
     * @access pubic.
     * @param Array $plugins.
     */
    public function setPlugins(array $plugins = [])
    {
        foreach ((array) $plugins as $plugin => $properties) {
            $this->setPlugin($plugin, $properties);
        }
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @access public.
     * @param String $plugin.
     * @param Array $properties.
     */
    public function setPlugin($plugin, array $properties = [])
    {
        $this->plugins[$plugin] = $properties;
    }

    /**
     * @access public.
     * @param String $plugin.
     * @return Boolean|Mixed.
     */
    public function getPlugin($plugin)
    {
        if (isset($this->plugins[$plugin])) {
            return $this->plugins[$plugin];
        }

        return false;
    }

    /**
     * @access public.
     * @param String $plugin.
     */
    public function unsetPlugin($plugin)
    {
        if (isset($this->plugins[$plugin])) {
            unset($this->plugins[$plugin]);
        }
    }

    /**
     * @access public.
     * @param Array $plugins.
     */
    public function unsetPlugins(array $plugins = [])
    {
        foreach ($plugins as $plugin) {
            $this->unsetPlugin($plugin);
        }
    }

    /**
     * @access public.
     * @param String $plugin.
     * @param Array $properties.
     */
    public function updatePlugin($plugin, array $properties = [])
    {
        if (isset($this->plugins[$plugin])) {
            $this->plugins[$plugin] = array_replace_recursive($this->plugins[$plugin], $properties);
        } else {
            $this->plugins[$plugin] = $plugin;
        }
    }

    /**
     * @access pubic.
     * @param Array $plugins.
     */
    public function updatePlugins(array $plugins = [])
    {
        foreach ((array) $plugins as $plugin => $properties) {
            $this->updatePlugin($plugin, $properties);
        }
    }

    /**
     * @access pubic.
     * @param String $plugin.
     * @return Boolean.
     */
    public function hasPlugin($plugin)
    {
        return isset($this->plugins[$plugin]);
    }

    /**
     * @access public
     * @param Array $values.
     */
    public function setValues(array $values = [])
    {
        foreach ((array) $values as $plugin => $value) {
            $this->setValue($plugin, $value);
        }
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @access public.
     * @param String $plugin.
     * @param Array $value.
     */
    public function setValue($plugin, array $value = [])
    {
        if (isset($this->values[$plugin])) {
            $value = array_merge($this->values[$plugin], $value);
        }

        $this->values[$plugin] = $value;
    }

    /**
     * @access public.
     * @param String $plugin.
     * @param Array $default.
     * @return Mixed.
     */
    public function getValue($plugin, array $default = [])
    {
        if (isset($this->values[$plugin])) {
            return $this->values[$plugin];
        }

        return $default;
    }

    /**
     * @access public.
     * @param String $event.
     * @return Array.
     */
    public function invokeEvent($event)
    {
        $output = [];

        foreach ($this->getPlugins() as $plugin => $properties) {
            $output[$plugin] = $this->invokePlugin($plugin, $event, $properties);
        }

        return $output;
    }

    /**
     * @access public.
     * @param String $plugin.
     * @param String $event.
     * @param Mixed $properties.
     * @return Mixed.
     */
    public function invokePlugin($plugin, $event, $properties)
    {
        if (preg_match('/^email([0-9]+|reply)$/i', $plugin)) {
            $plugin = 'email';
        }

        $pluginName = ucfirst($plugin) . 'FormPlugin';

        if (class_exists($pluginName)) {
            $pluginClass = new $pluginName($this->form, $properties);

            if (method_exists($pluginClass, $event)) {
                return $pluginClass->{$event}();
            }

            return false;
        }

        $snippet = $this->modx->getObject('modSnippet', [
            'name' => ucfirst($plugin)
        ]);

        if ($snippet) {
            return $snippet->process([
                'event'         => $event,
                'properties'    => $properties,
                'form'          => &$this->form
            ]);
        }

        $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.' . $plugin . '.' . $event . '] could not load plugin.');

        return false;
    }
}
