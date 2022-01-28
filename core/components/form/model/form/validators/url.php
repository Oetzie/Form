<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class UrlFormValidator extends DefaultFormValidator
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
        $regex      = $this->form->modx->getOption('form.validator.url.regex', null, '/(http:\/\/|https:\/\/|ftp:\/\/|www\.)[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i');

        return $validator->validate($value, $regex);
    }
}
