<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FilesizeFormValidator extends DefaultFormValidator
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
                $units = ['B', 'KB', 'MB', 'GB'];

                if (preg_match('/([\d\.]+)\s?(B|KB|MB|GB)/', $properties, $matches)) {
                    $size = (float) $matches[1] * (1024 ** array_flip($units)[$matches[2]]);
                } else {
                    $size = (int) $properties;
                }

                $power      = $size > 0 ? floor(log($size, 1024)) : 0;
                $properties = number_format($size / pow(1024, $power), 2) . ' ' . $units[$power];

                if ((int) $value['size'] > $size) {
                    return false;
                }

                return true;
            }

            return false;
        }

        return true;
    }
}
