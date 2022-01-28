<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class BetweenlengthFormValidator extends DefaultFormValidator
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
            $minLength = new MinlengthFormValidator($this->form, $this->config);
            $maxLength = new MaxlengthFormValidator($this->form, $this->config);

            if (is_string($value)) {
                return $minLength->validate($value, $properties['min']) && $maxLength->validate($value, $properties['max']);
            }

            if (is_array($value)) {
                return $minLength->validate($value, $properties['min']) && $maxLength->validate($value, $properties['max']);
            }

            return false;
        }

        return true;
    }
}
