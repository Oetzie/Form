<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class DateFormValidator extends DefaultFormValidator
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
                return preg_match('/^([0-9\-\/]+)$/', $value) && strtotime($value) !== false;
            }

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    if (!preg_match('/^([0-9\-\/]+)$/', $subValue) && strtotime($subValue) !== false) {
                        return false;
                    }
                }

                return true;
            }

            return false;
        }

        return true;
    }
}
