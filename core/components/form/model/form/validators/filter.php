<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FilterFormValidator extends DefaultFormValidator
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
                return filter_var($value, $properties);
            }

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    if (!filter_var($subValue, $properties)) {
                        return false;
                    }
                }

                return true;
            }
        }

        return true;
    }
}
