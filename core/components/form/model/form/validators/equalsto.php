<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class EqualstoFormValidator extends DefaultFormValidator
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
            $properties = $values[$properties] ?: '';

            if (is_string($value)) {
                return $value === $properties;
            }

            if (is_array($value)) {
                return $value == (array) $properties;
            }
        }

        return true;
    }
}
