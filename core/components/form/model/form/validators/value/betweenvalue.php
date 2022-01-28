<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class BetweenvalueFormValidator extends DefaultFormValidator
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
            $minValue = new MinvalueFormValidator($this->form, $this->config);
            $maxValue = new MaxvalueFormValidator($this->form, $this->config);

            if (is_string($value)) {
                return $minValue->validate($value, $properties['min']) && $maxValue->validate($value, $properties['max']);
            }

            if (is_array($value)) {
                return $minValue->validate($value, $properties['min']) && $maxValue->validate($value, $properties['max']);
            }

            return false;
        }

        return true;
    }
}
