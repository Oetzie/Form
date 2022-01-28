<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class EmailFormValidator extends DefaultFormValidator
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
        $validator  = new FilterFormValidator($this->form, $this->config);
        $filter     = FILTER_VALIDATE_EMAIL;

        return $validator->validate($value, $filter);
    }
}
