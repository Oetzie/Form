<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class BetweendateFormValidator extends DefaultFormValidator
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
            $minDate = new MindateFormValidator($this->form, $this->config);
            $maxDate = new MaxdateFormValidator($this->form, $this->config);

            if (is_string($value)) {
                return $minDate->validate($value, $properties['min']) && $maxDate->validate($value, $properties['max']);
            }

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    if (!$minDate->validate($subValue, $properties['min']) && $maxDate->validate($subValue, $properties['max'])) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
