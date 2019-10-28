<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FormValidator
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
     * @param String  $message.
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

                if (method_exists($this, $type)) {
                    if (!$this->{$type}($value, $properties, $values)) {
                        $this->setError($key, $type, $properties);
                    }
                } else {
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
        }

        return $this->isValid();
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function required($value)
    {
        if (is_string($value)) {
            return $value !== '';
        }

        if (is_array($value)) {
            return count($value) !== 0;
        }

        return false;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Mixed $properties.
     * @param Array $values.
     * @return Boolean.
     */
    public function requiredWhen($value, $properties, array $values = [])
    {
        $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.validator.requiredWhen] not supported.');

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function blank($value)
    {
        if (is_string($value)) {
            return $value === '';
        }

        if (is_array($value)) {
            return count($value) === 0;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Mixed $properties.
     * @return Boolean.
     */
    public function equals($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return $value === $properties;
            }

            if (is_array($value)) {
                return $value == (array) $properties;
            }
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param String $properties.
     * @param Array $values.
     * @return Boolean.
     */
    public function equalsTo($value, &$properties, array $values = [])
    {
        if (!empty($value)) {
            $properties = $values[$properties] ?: '';

            if (is_string($value)) {
                return $value === $properties;
            }

            if (is_array($value)) {
                return $value == (array) $properties;
            }
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param String $properties.
     * @return Boolean.
     */
    public function contains($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return preg_match('/' . $properties . '/i', $value);
            }

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    if (preg_match('/' . $properties . '/i', $subValue)) {
                        return true;
                    }
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Integer $properties.
     * @return Boolean.
     */
    public function minLength($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return strlen($value) >= (int) $properties;
            }

            if (is_array($value)) {
                return count($value) >= (int) $properties;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Integer $properties.
     * @return Boolean.
     */
    public function maxLength($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return strlen($value) <= (int) $properties;
            }

            if (is_array($value)) {
                return count($value) <= (int) $properties;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Array $properties.
     * @return Boolean.
     */
    public function betweenLength($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return $this->minLength($value, $properties['min']) && $this->maxLength($value, $properties['max']);
            }

            if (is_array($value)) {
                return $this->minLength($value, $properties['min']) && $this->maxLength($value, $properties['max']);
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Integer $properties.
     * @return Boolean.
     */
    public function minValue($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return (int) $value >= (int) $properties;
            }

            if (is_array($value)) {
                return count($value) >= (int) $properties;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Integer $properties.
     * @return Boolean.
     */
    public function maxValue($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return (int) $value <= (int) $properties;
            }

            if (is_array($value)) {
                return count($value) <= (int) $properties;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Array $properties.
     * @return Boolean.
     */
    public function betweenValue($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return $this->minValue($value, $properties['min']) && $this->minValue($value, $properties['max']);
            }

            if (is_array($value)) {
                return $this->minValue($value, $properties['min']) && $this->minValue($value, $properties['max']);
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param String $properties.
     * @return Boolean.
     */
    public function regex($value, $properties)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return preg_match($properties, $value);
            }

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    if (!preg_match($properties, $subValue)) {
                        return false;
                    }
                }

                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param String $filter.
     * @return Boolean.
     */
    public function filter($value, $filter)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return filter_var($value, $filter);
            }

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    if (!filter_var($subValue, $filter)) {
                        return false;
                    }
                }

                return true;
            }
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function email($value)
    {
        return $this->filter($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function domain($value)
    {
        return $this->regex($value, '/(http:\/\/|https:\/\/|ftp:\/\/|www\.)[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?$/i');
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function url($value)
    {
        return $this->regex($value, '/(http:\/\/|https:\/\/|ftp:\/\/|www\.)[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i');
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function ip($value)
    {
        return $this->filter($value, FILTER_VALIDATE_IP);
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function iban($value)
    {
        return $this->regex($value, '/[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/i');
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function phone($value)
    {
        return $this->regex(str_replace(' ', '', $value), '/^\+?([\d\-?]){10,11}$/');
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function number($value)
    {
        return $this->regex($value, '/^([0-9,\.]+)$/');
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function alpha($value)
    {
        return $this->regex($value, '/^([a-z]+)$/');
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function alphaNumeric($value)
    {
        return $this->regex($value, '/^([a-z0-9,\.]+)$/');
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @return Boolean.
     */
    public function date($value)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                return preg_match('/^([0-9\-\/]+)$/', $value) && strtotime($value) !== false;
            }

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    if (!$this->date($subValue)) {
                        return false;
                    }
                }

                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param String $properties.
     * @return Boolean.
     */
    public function minDate($value, $properties)
    {
        if (!empty($value)) {
            if ($this->date($value)) {
                if (is_string($value)) {
                    return strtotime($value) >= strtotime($properties);
                }

                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        if (strtotime($subValue) < strtotime($properties)) {
                            return false;
                        }
                    }

                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param String $properties.
     * @return Boolean.
     */
    public function maxDate($value, $properties)
    {
        if (!empty($value)) {
            if ($this->date($value)) {
                if (is_string($value)) {
                    return strtotime($value) <= strtotime($properties);
                }

                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        if (strtotime($subValue) > strtotime($properties)) {
                            return false;
                        }
                    }

                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Array $properties.
     * @return Boolean.
     */
    public function betweenDate($value, $properties)
    {
        if (!empty($value)) {
            if ($this->date($value)) {
                if (is_string($value)) {
                    return $this->minDate($value, $properties['min']) && $this->maxDate($value, $properties['max']);
                }

                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        if (!$this->minDate($value, $properties['min']) || !$this->maxDate($value, $properties['max'])) {
                            return false;
                        }
                    }

                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Array $value.
     * @return Boolean.
     */
    public function file($value)
    {
        if (!empty($value)) {
            if (isset($value['name'], $value['tmp_name'], $value['error']) && $value['error'] === UPLOAD_ERR_OK) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Mixed $properties.
     * @return Boolean.
     */
    public function fileExtension($value, &$properties)
    {
        if (!empty($value)) {
            if (is_string($properties)) {
                $extensions = array_map('trim', explode(',', $properties));
            } else {
                $extensions = array_map('trim', $properties);
            }

            $properties = implode(', ', $extensions);

            if ($this->file($value)) {
                if (!in_array(strtolower(substr($value['name'], strrpos($value['name'], '.') + 1, strlen($value['name']))), $extensions, true)) {
                    return false;
                }

                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param String $properties.
     * @return Boolean.
     */
    public function fileSize($value, &$properties)
    {
        if (!empty($value)) {
            $units = ['B', 'KB', 'MB', 'GB'];

            if (preg_match('/([\d\.]+)\s?(B|KB|MB|GB)/', $properties, $matches)) {
                $size = (float) $matches[1] * (1024 ** array_flip($units)[$matches[2]]);
            } else {
                $size = (int) $properties;
            }

            $power      = $size > 0 ? floor(log($size, 1024)) : 0;
            $properties = number_format($size / pow(1024, $power), 2) . ' ' . $units[$power];

            if ($this->file($value)) {
                if ((int) $value['size'] > $size) {
                    return false;
                }

                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @access public.
     * @param Mixed $value.
     * @param Integer $properties.
     * @return Boolean.
     */
    public function age($value, $properties)
    {
        if (!empty($value)) {
            if ($this->date($value)) {
                if (is_string($value)) {
                    $age = date('Y') - date('Y', strtotime($value));

                    return $age >= (int) $properties;
                }

                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        $age = date('Y') - date('Y', strtotime($subValue));

                        if ($age < (int) $properties) {
                            return false;
                        }
                    }

                    return true;
                }
            }

            return false;
        }

        return true;
    }
}
