<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once __DIR__ . '/form.class.php';

class FormSnippets extends Form
{
    /**
     * @access public.
     * @var Array.
     */
    public $properties = [];

    /**
     * @access public.
     * @param String $key.
     * @param Mixed $value.
     */
    public function setProperty($key, $value)
    {
        $this->properties[$key] = $value;
    }

    /**
     * @access public.
     * @param String $key.
     * @param Mixed $default.
     * @return Mixed.
     */
    public function getProperty($key, $default = null)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }

        return $default;
    }

    /**
     * @access public.
     * @param Array $properties.
     */
    public function setProperties(array $properties = [])
    {
        foreach ($properties as $key => $value) {
            $this->setProperty($key, $value);
        }
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @access public.
     * @param Array $properties.
     * @return Array.
     */
    public function getFormattedProperties(array $properties = [])
    {
        foreach (['validator', 'validatorMessages', 'plugins'] as $key) {
            if (isset($properties[$key]) && !is_array($properties[$key])) {
                $properties[$key] = json_decode($properties[$key], true);
            }
        }

        return $properties;
    }
}
