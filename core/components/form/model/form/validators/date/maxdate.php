<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class MaxdateFormValidator extends DefaultFormValidator
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
            $validator = new DateFormValidator($this->form, $this->config);

            if ($validator->validate($value)) {
                if (is_string($value)) {
                    return strtotime($value) <= strtotime($properties);
                }

                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        if (strtotime($subValue) > strtotime($properties)) {
                            return false;
                        }
                    }

                    return true;
                }
            }

            return false;
        }

        return true;
    }
}
