<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class AlphaFormValidator extends DefaultFormValidator
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
        $validator  = new RegexFormValidator($this->form, $this->config);
        $regex      = $this->form->modx->getOption('form.validator.alpha.regex', null, '/^([a-z]+)$/');

        return $validator->validate($value, $regex);
    }
}
