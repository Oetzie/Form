<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FileextensionFormValidator extends DefaultFormValidator
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
            $validator = new FileFormValidator($this->form, $this->config);

            if ($validator->validate($value)) {
                if (is_string($properties)) {
                    $extensions = array_map('trim', explode(',', $properties));
                } else {
                    $extensions = array_map('trim', $properties);
                }

                $properties = implode(', ', $extensions);

                if (!in_array(strtolower(substr($value['name'], strrpos($value['name'], '.') + 1, strlen($value['name']))), $extensions, true)) {
                    return false;
                }

                return true;
            }

            return false;
        }

        return true;
    }
}
