<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class MaxlengthFormValidator extends DefaultFormValidator
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
                return strlen($value) <= (int) $properties;
            }

            if (is_array($value)) {
                return count($value) <= (int) $properties;
            }

            return false;
        }

        return true;
    }
}
