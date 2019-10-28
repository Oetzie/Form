<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FormCollection
{
    /**
     * @access public.
     * @var Array.
     */
    public $values = [];

    /**
     * @access public.
     * @param String $key.
     * @param Mixed $value.
     */
    public function setValue($key, $value = '')
    {
        $this->values[$key] = $value;
    }

    /**
     * @access public.
     * @param String $key.
     * @param Mixed $default.
     * @return Mixed,
     */
    public function getValue($key, $default = '')
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        return $default;
    }

    /**
     * @access public.
     * @param Array $values.
     */
    public function setValues(array $values = [])
    {
        foreach ($values as $key => $value) {
            $this->setValue($key, $value);
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
     * @param String $key.
     */
    public function unsetValue($key)
    {
        if (isset($this->values[$key])) {
            unset($this->values[$key]);
        }
    }

    /**
     * @access public.
     * @param Array $values.
     */
    public function unsetValues(array $values = [])
    {
        foreach ($values as $key) {
            $this->unsetValue($key);
        }
    }

    /**
     * @access public.
     */
    public function reset()
    {
        $this->values = [];
    }
}
