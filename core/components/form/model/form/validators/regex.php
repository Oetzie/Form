<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class RegexFormValidator extends DefaultFormValidator
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
                return preg_match($properties, $value);
            }

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    if (!preg_match($properties, $subValue)) {
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
