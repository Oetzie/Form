<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class SaveFormPlugin extends DefaultFormPlugin
{
    /**
     * @access public.
     * @return Boolean.
     */
    public function onValidatePost()
    {
        if ($this->form->getValidators()->isValid() || $this->form->getOption('form_save_invalid')) {
            if (!isset($this->config['fields'])) {
                $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.save.onValidatePost] could not init save, fields.');

                return false;
            }

            if (!isset($this->config['name'])) {
                $this->config['name'] = $this->form->modx->resource->get('pagetitle');
            }

            $data = [];

            foreach ((array) $this->config['fields'] as $key => $field) {
                if (!is_array($field)) {
                    $field = [
                        'label' => $field
                    ];
                }

                if (!isset($field['type'])) {
                    $field['type'] = 'textfield';
                }

                if ($error = $this->form->getValidators()->getError($key)) {
                    $field['valid'] = false;
                    $field['error'] = $error[0]['error'];
                } else {
                    $field['valid'] = true;
                    $field['error'] = '';
                }

                $field['value'] = $this->form->getCollection()->getValue($key);

                $data[$key] = $field;
            }

            $object = $this->form->modx->newObject('FormForm');

            if ($object) {
                $object->setField($data);

                $object->fromArray([
                    'name'              => $this->config['name'],
                    'resource_id'       => $this->form->modx->resource->get('id'),
                    'formbuilder_id'    => $this->form->getProperty('form'),
                    'ip'                => $_SERVER['REMOTE_ADDR'],
                    'active'            => $this->form->getValidators()->isValid(),
                    'editedon'          => date('Y-m-d H:i:s')
                ]);

                $object->save();
            }
        }

        return true;
    }
}
