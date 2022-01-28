<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FileFormValidator extends DefaultFormValidator
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
            if (isset($value['name'], $value['tmp_name'], $value['error']) && $value['error'] === UPLOAD_ERR_OK) {
                return true;
            }

            return false;
        }

        return true;
    }
}
