<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class UploadsFormPlugin extends DefaultFormPlugin
{
    /**
     * @access public.
     * @return Boolean.
     */
    public function onValidateSuccess()
    {
        $status = true;

        foreach ((array) $this->config as $field => $file) {
            if (isset($file['path'])) {
                $value = $this->form->getCollection()->getValue($field);

                if (isset($value['name'], $value['tmp_name'], $value['error'])) {
                    if ($value['error'] === UPLOAD_ERR_OK) {
                        $path = $this->form->getMediaSourceBasePath() . trim($file['path'], '/') . '/';

                        if (!is_dir($path)) {
                            if (!mkdir($path, 0755, true)) {
                                $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.uploads.onValidateSuccess] could not create upload path "' . $path . '".');
                            }
                        }

                        if ($path) {
                            $name       = substr($value['name'], 0, strrpos($value['name'], '.'));
                            $extension  = substr($value['name'], strrpos($value['name'], '.') + 1, strlen($value['name']));

                            $file       = str_replace([' ', '-'], '_', strtolower($name)) . '-' . date('Y_m_d_H_i') . '.' . $extension;

                            if (!move_uploaded_file($value['tmp_name'], $path . $file)) {
                                $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.uploads.onValidateSuccess] could not move upload file "' . $path . $file . '".');

                                $status = false;
                            }

                            $this->form->getCollection()->setValue($field, array_merge($value, [
                                'tmp_name' => $path . $file
                            ]));
                        }
                    } else if ($value['error'] === UPLOAD_ERR_NO_FILE) {
                        $status = false;
                    }
                }
            }
        }

        return $status;
    }
}
