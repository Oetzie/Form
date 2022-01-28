<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once __DIR__ . '/validators/default.php';

require_once __DIR__ . '/validators/date/betweendate.php';
require_once __DIR__ . '/validators/date/date.php';
require_once __DIR__ . '/validators/date/mindate.php';
require_once __DIR__ . '/validators/date/maxdate.php';
require_once __DIR__ . '/validators/file/file.php';
require_once __DIR__ . '/validators/file/fileextension.php';
require_once __DIR__ . '/validators/file/filesize.php';
require_once __DIR__ . '/validators/length/betweenlength.php';
require_once __DIR__ . '/validators/length/minlength.php';
require_once __DIR__ . '/validators/length/maxlength.php';
require_once __DIR__ . '/validators/value/betweenvalue.php';
require_once __DIR__ . '/validators/value/minvalue.php';
require_once __DIR__ . '/validators/value/maxvalue.php';
require_once __DIR__ . '/validators/age.php';
require_once __DIR__ . '/validators/alpha.php';
require_once __DIR__ . '/validators/alphanumeric.php';
require_once __DIR__ . '/validators/blank.php';
require_once __DIR__ . '/validators/contains.php';
require_once __DIR__ . '/validators/domain.php';
require_once __DIR__ . '/validators/email.php';
require_once __DIR__ . '/validators/equals.php';
require_once __DIR__ . '/validators/equalsto.php';
require_once __DIR__ . '/validators/filter.php';
require_once __DIR__ . '/validators/iban.php';
require_once __DIR__ . '/validators/ip.php';
require_once __DIR__ . '/validators/number.php';
require_once __DIR__ . '/validators/phone.php';
require_once __DIR__ . '/validators/regex.php';
require_once __DIR__ . '/validators/required.php';
require_once __DIR__ . '/validators/url.php';

class FormValidators
{
    /**
     * @access public.
     * @var modX.
     */
    public $modx;

    /**
     * @access public.
     * @var Object.
     */
    public $form;

    /**
     * @access public.
     * @var Array.
     */
    public $rules = [];

    /**
     * @access public.
     * @var Array.
     */
    public $errors = [];

    /**
     * @access public.
     * @param modX $modx.
     * @param Object $form.
     */
    public function __construct(modX &$modx, $form)
    {
        $this->modx =& $modx;
        $this->form =& $form;
    }

    /**
     * @access public.
     * @param String $key.
     * @param String $type.
     * @param Mixed $properties.
     */
    public function setRule($key, $type, $properties)
    {
        $this->rules[$key][] = [
            'type'          => $type,
            'properties'    => $properties
        ];
    }

    /**
     * @access public.
     * @param String $key.
     * @return Null|Array.
     */
    public function getRule($key)
    {
        if (isset($this->rules[$key])) {
            return $this->rules[$key];
        }

        return null;
    }

    /**
     * @access public.
     * @param Array $rules.
     */
    public function setRules(array $rules = [])
    {
        foreach ($rules as $key => $rule) {
            if (!is_array($rule)) {
                $rule = explode(',', $rule);
            }

            foreach ($rule as $type => $properties) {
                if (is_numeric($type)) {
                    $this->setRule($key, $properties, true);
                } else {
                    $this->setRule($key, $type, $properties);
                }
            }
        }
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @access public.
     * @param String $key.
     * @param String $error.
     * @param Mixed $properties.
     * @param String $message.
     */
    public function setError($key, $error, $properties = null, $message = null)
    {
        $this->errors[$key][] = [
            'key'           => $key,
            'error'         => $error,
            'properties'    => $properties,
            'message'       => $message
        ];
    }

    /**
     * @access public.
     * @param String $key.
     * @return Null|Array.
     */
    public function getError($key)
    {
        if (isset($this->errors[$key])) {
            return $this->errors[$key];
        }

        return null;
    }

    /**
     * @access public.
     * @param Array $errors.
     */
    public function setErrors(array $errors = [])
    {
        foreach ($errors as $error) {
            $this->setError($error['key'] ?: 'unknown', $error['error'] ?: '', $error['properties'] ?: null, $error['message'] ?: null);
        }
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @access public.
     * @param String $key.
     * @return Boolean.
     */
    public function hasError($key)
    {
        return isset($this->errors[$key]);
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function isValid()
    {
        return count($this->errors) === 0;
    }

    /**
     * @access public.
     * @param Array $values.
     * @return Boolean.
     */
    public function validate(array $values = [])
    {
        foreach ($this->getRules() as $key => $rule) {
            $value = isset($values[$key]) ? $values[$key] : '';

            foreach ((array) $rule as $type) {
                list($type, $properties) = array_values($type);

                if ($type === 'validateIf') {
                    if ($this->validateIf($key, $properties, $values)) {
                        continue 2;
                    }

                    continue;
                }

                $validatorName = ucfirst($type) . 'FormValidator';

                if (class_exists($validatorName)) {
                    $validatorClass = new $validatorName($this->form);

                    if (method_exists($validatorClass, 'validate')) {
                        if (($message = $validatorClass->validate($value, $properties, $values, $key)) !== true) {
                            if (is_string($message)) {
                                $this->setError($key, $type, $properties, $message);
                            } else {
                                $this->setError($key, $type, $properties);
                            }
                        }
                    }

                    continue;
                }

                $snippet = $this->modx->getObject('modSnippet', [
                    'name' => $type
                ]);

                if ($snippet) {
                    $snippet->process([
                        'key'           => $key,
                        'value'         => $value,
                        'properties'    => $properties,
                        'values'        => $values,
                        'form'          => &$this->form
                    ]);
                } else {
                    $this->setError($key, 'unknown');
                }
            }
        }

        return $this->isValid();
    }

    /**
     * @access public.
     * @param String $key.
     * @param Array $properties.
     * @param Array $values.
     * @return Boolean.
     */
    public function validateIf($key, array $properties = [], array $values = [])
    {
        if (isset($properties['validator'])) {
            $validators = $properties['validator'];

            unset($properties['validator']);

            if (count($properties) > 0) {
                $validator = new self($this->modx, $this->form);

                $validator->setRules($properties);

                if ($validator->validate($values)) {
                    $validator = new self($this->modx, $this->form);

                    $validator->setRules([
                        $key => $validators
                    ]);

                    if (!$validator->validate($values)) {
                        if ($errors = $validator->getError($key)) {
                            foreach ($errors as $error) {
                                $this->setError($error['key'], $error['error'], $error['properties'], $error['message']);
                            }
                        }

                        return false;
                    }
                }
            }
        }

        return true;
    }
}
