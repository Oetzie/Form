<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class ContainsFormValidator extends DefaultFormValidator
{
    /**
     * @access public.
     * @param Mixed $value.
     * @param Mixed $properties.
     * @param Array $values.
     * @return Boolean|String.
     */
    public function validate($value, &$properties = null, array $values = [])
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return preg_match('/' . $properties . '/i', $value);
            }

            if (is_array($value)) {
                return in_array($properties, $value, true);
            }

            return false;
        }

        return true;
    }
}
