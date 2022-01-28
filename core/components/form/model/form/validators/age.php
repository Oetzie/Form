<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class AgeFormValidator extends DefaultFormValidator
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
                    $age = date('Y') - date('Y', strtotime($value));

                    return $age >= (int) $properties;
                }

                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        $age = date('Y') - date('Y', strtotime($subValue));

                        if ($age < (int) $properties) {
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
