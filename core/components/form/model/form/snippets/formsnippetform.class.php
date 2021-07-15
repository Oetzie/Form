<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(__DIR__) . '/formsnippets.class.php';
require_once dirname(__DIR__) . '/formcollection.class.php';
require_once dirname(__DIR__) . '/formvalidator.class.php';
require_once dirname(__DIR__) . '/formevents.class.php';

class FormSnippetForm extends FormSnippets
{
    /**
     * @access public.
     * @var Object.
     */
    public $collection = null;

    /**
     * @access public.
     * @var Object.
     */
    public $validator = null;

    /**
     * @access public.
     * @var Object.
     */
    public $events = null;

    /**
     * @access public.
     * @var Array.
     */
    public $properties = [
        'action'                => 'resource',
        'method'                => 'post',
        'submit'                => 'submit',
        'prefix'                => 'form',
        'retriever'             => '',

        'validator'             => [],
        'validatorMessages'     => [],

        'errorMessage'          => '',
        'successMessage'        => '',

        'plugins'               => [],

        'success'               => '',
        'failure'               => '',

        'tpl'                   => '',
        'tplSuccess'            => '',
        'tplFailure'            => '',
        'tplError'              => '@INLINE <p class="error">[[+error]]</p>',
        'tplErrorMessage'       => '@INLINE <div class="form-group form-group--error">
            <p class="error">[[+error]]</p>
        </div>',
        'tplSuccessMessage'     => '',

        'usePdoTools'           => false,
        'usePdoElementsPath'    => false
    ];

    /**
     * @access public.
     * @param Array $properties.
     * @return Array|String.
     */
    public function run(array $properties = [])
    {
        $this->setProperties($this->getFormattedProperties($properties));

        $tpl            = $this->getProperty('tpl');
        $placeholders   = [];

        if (!empty($this->getProperty('retriever'))) {
            $values = $this->getFormCache();

            if ($values) {
                $placeholders['values'] = $values;
            } else {
                if (!$this->handleFailure()) {
                    if (!empty($this->getProperty('tplFailure'))) {
                        $tpl = $this->getProperty('tplFailure');
                    }
                }
            }
        } else {
            $this->getEvents()->setPlugins($this->getProperty('plugins'));
            $this->getValidator()->setRules($this->getProperty('validator'));

            $this->getEvents()->invokeEvent('onBeforePost');

            $placeholders = [
                'method'    => $this->getProperty('method'),
                'action'    => $this->getAction(),
                'submit'    => $this->getProperty('submit'),
                'plugins'   => $this->getEvents()->getValues()
            ];

            $this->getCollection()->setValues($this->getFileValues());
            $this->getCollection()->setValues($this->getRequestValues());

            if ($this->isMethod($this->getProperty('method'))) {
                $placeholders['state'] = 'active';

                $this->getValidator()->validate($this->getCollection()->getValues());

                $this->getEvents()->invokeEvent('onValidatePost');

                if ($this->getValidator()->isValid()) {
                    $this->getEvents()->invokeEvent('onValidateSuccess');
                } else {
                    $this->getEvents()->invokeEvent('onValidateFailed');
                }

                $this->getEvents()->invokeEvent('onAfterPost');

                $placeholders['values'] = $this->getCollection()->getValues();
                $placeholders['plugins'] = $this->getEvents()->getValues();

                if ($this->getValidator()->isValid()) {
                    $placeholders['valid'] = true;

                    $this->setFormCache($this->getCollection()->getValues());

                    if (!$this->handleSuccess()) {
                        if (!empty($this->getProperty('tplSuccess'))) {
                            $tpl = $this->getProperty('tplSuccess');
                        }
                    }

                    if (!empty($this->getProperty('tplSuccess'))) {
                        $tpl = $this->getProperty('tplSuccess');
                    }

                    if (!empty($this->getProperty('successMessage'))) {
                        $message = $this->getProperty('successMessage');

                        if (!empty($this->getProperty('tplSuccessMessage'))) {
                            $placeholders['success_message'] = $this->getChunk($this->getProperty('tplSuccessMessage'), [
                                'message' => $message
                            ]);
                        } else {
                            $placeholders['success_message'] = $message;
                        }
                    }
                } else {
                    $placeholders['valid'] = false;

                    $placeholders['errors'] = $this->formatValidationErrors($this->getValidator()->getErrors());

                    $message = $this->getProperty('errorMessage');

                    if ($error = $this->getValidator()->getError('error_message')) {
                        $message = $error[0]['error'];
                    }

                    if (!empty($this->getProperty('tplErrorMessage'))) {
                        $placeholders['error_message'] = $this->getChunk($this->getProperty('tplErrorMessage'), [
                            'error' => $message ?: $this->modx->lexicon('form.form_invalid')
                        ]);
                    } else {
                        $placeholders['error_message'] = $message ?: $this->modx->lexicon('form.form_invalid');
                    }

                    if (!empty($this->getProperty('tplFailure'))) {
                        $tpl = $this->getProperty('tplFailure');
                    }
                }
            } else {
                $placeholders['values'] = $this->getCollection()->getValues();

                if (!$this->getValidator()->isValid()) {
                    $placeholders['errors'] = $this->formatValidationErrors($this->getValidator()->getErrors());

                    $message = $this->getProperty('errorMessage');

                    if ($error = $this->getValidator()->getError('error_message')) {
                        $message = $error[0]['error'];
                    }

                    if (!empty($this->getProperty('tplErrorMessage'))) {
                        $placeholders['error_message'] = $this->getChunk($this->getProperty('tplErrorMessage'), [
                            'error' => $message ?: $this->modx->lexicon('form.form_invalid')
                        ]);
                    } else {
                        $placeholders['error_message'] = $message ?: $this->modx->lexicon('form.form_invalid');
                    }
                }
            }

            $this->modx->toPlaceholders($placeholders, rtrim($this->getProperty('prefix'), '.'));
        }

        if (!empty($tpl)) {
            return $this->getChunk($tpl, $placeholders);
        }

        return '';
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getFileValues()
    {
        return $_FILES;
    }

    /**
     * @access public.
     * @param String $method.
     * @return Array.
     */
    public function getRequestValues($method = 'POST')
    {
        return $this->modx->request->getParameters([], $method);
    }

    /**
     * @access public.
     */
    public function setCollection()
    {
        $this->collection = new FormCollection();
    }

    /**
     * @access public.
     * @return Null|Object.
     */
    public function getCollection()
    {
        if ($this->collection === null) {
            $this->setCollection();
        }

        return $this->collection;
    }

    /**
     * @access public.
     */
    public function setValidator()
    {
        $this->validator = new FormValidator($this->modx, $this);
    }

    /**
     * @access public.
     * @return Null|Object.
     */
    public function getValidator()
    {
        if ($this->validator === null) {
            $this->setValidator();
        }

        return $this->validator;
    }

    /**
     * @access public.
     */
    public function setEvents()
    {
        $this->events = new FormEvents($this->modx, $this);
    }

    /**
     * @access public.
     * @return Null|Object.
     */
    public function getEvents()
    {
        if ($this->events === null) {
            $this->setEvents();
        }

        return $this->events;
    }

    /**
     * @access public.
     * @param String $method.
     * @return Boolean.
     */
    public function isMethod($method)
    {
        if ($_SERVER['REQUEST_METHOD'] === strtoupper($method)) {
            $values = $this->getCollection()->getValues();

            if (count($values) >= 1) {
                if (isset($values[$this->getProperty('submit')])) {
                    unset($values[$this->getProperty('submit')]);

                    return true;
                }

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @access public.
     * @return String
     */
    public function getAction()
    {
        $action = $this->getProperty('action');

        if (!empty($action)) {
            if (is_numeric($action)) {
                return $this->modx->makeUrl($action, null, $this->getRequestValues('GET'));
            }

            if ($action !== 'resource') {
                return $action;
            }
        }

        return $this->modx->makeUrl($this->modx->resource->get('id'), null, $this->getRequestValues('GET'));
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function handleSuccess()
    {
        $success = $this->getSuccess();

        if ($success) {
            $this->modx->sendRedirect($success);

            return true;
        }

        return false;
    }

    /**
     * @access public.
     * @return Null|String
     */
    public function getSuccess()
    {
        $success = $this->getProperty('success');

        if (!empty($success)) {
            if (is_numeric($success)) {
                if ($this->getProperty('method') === 'get') {
                    return $this->modx->makeUrl($success, null, array_merge($this->getRequestValues('GET'), $this->getCollection()->getValues()));
                }

                return $this->modx->makeUrl($success);
            }

            if ($success === 'resource') {
                if ($this->getProperty('method') === 'get') {
                    return $this->modx->makeUrl($this->modx->resource->get('id'), null, array_merge($this->getRequestValues('GET'), $this->getCollection()->getValues()));
                }

                return $this->modx->makeUrl($this->modx->resource->get('id'));
            }

            return $success;
        }

        return null;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function handleFailure()
    {
        $failure = $this->getFailure();

        if ($failure) {
            $this->modx->sendRedirect($failure);

            return true;
        }

        return false;
    }

    /**
     * @access public.
     * @return Null|String
     */
    public function getFailure()
    {
        $failure = $this->getProperty('failure');

        if (!empty($failure)) {
            if (is_numeric($failure)) {
                return $this->modx->makeUrl($failure);
            }

            if ($failure === 'parent') {
                return $this->modx->makeUrl($this->modx->resource->get('parent'));
            }

            return $failure;
        }

        return null;
    }

    /**
     * @access public.
     * @param String $type.
     * @param Array $properties.
     * @param String $field.
     * @return String.
     */
    public function getValidationMessage($type, array $properties = [], $field = null)
    {
        $messages = $this->getProperty('validatorMessages');

        if (count($messages) >= 1) {
            if (isset($messages[$type])) {
                $message = $messages[$type];

                if (is_array($message)) {
                    if (isset($message[$field])) {
                        return $message[$field];
                    }

                    return $this->modx->lexicon('form.validator_' . strtolower($type), $properties);
                }

                return $message;
            }
        }

        return $this->modx->lexicon('form.validator_' . strtolower($type), $properties);
    }

    /**
     * @access public.
     * @param Array $errors.
     * @return Array.
     */
    public function formatValidationError(array $errors = [])
    {
        $output = [];

        foreach ((array) $errors as $error) {
            list($key, $error, $properties, $message) = array_values($error);

            if (!is_array($properties)) {
                $properties = [
                    strtolower($error) => $properties
                ];
            }

            if (empty($message)) {
                $message = $this->getValidationMessage($error, $properties, $key);
            }

            if (empty($this->getProperty('tplError'))) {
                return $message;
            }

            $output[] = $this->getChunk($this->getProperty('tplError'), [
                'error' => $message
            ]);
        }

        return $output;
    }

    /**
     * @access public.
     * @param Array $error.
     * @return Array.
     */
    public function formatValidationErrors(array $error = [])
    {
        $output = [];

        foreach ($error as $key => $errors) {
            $errors = $this->formatValidationError($errors);

            $output[$key] = [
                'error'     => $errors[0] ?: '',
                'errors'    => implode(PHP_EOL, $errors)
            ];
        }

        return $output;
    }

    /**
     * @access public.
     * @param String $option.
     * @return Mixed.
     */
    public function getFormCacheOptions($option = null)
    {
        if ($option === xPDO::OPT_CACHE_KEY) {
            return 'form/' . $this->getProperty('submit', 'submit') . '/' . md5(session_id());
        }

        if ($option === xPDO::OPT_CACHE_EXPIRES) {
            return (int) $this->getProperty('cacheExpires', 60);
        }

        return $option;
    }

    /**
     * @access public.
     * @param Array $form.
     * @return Boolean.
     */
    public function setFormCache(array $form = [])
    {
        $cacheKey       = $this->getFormCacheOptions(xPDO::OPT_CACHE_KEY);
        $cacheExpires   = $this->getFormCacheOptions(xPDO::OPT_CACHE_EXPIRES);

        return $this->modx->cacheManager->set($cacheKey, $form, $cacheExpires);
    }

    /**
     * @access public.
     * @return Array|Boolean.
     */
    public function getFormCache()
    {
        $cacheKey   = $this->getFormCacheOptions(xPDO::OPT_CACHE_KEY);
        $form       = $this->modx->cacheManager->get($cacheKey);

        if ($form) {
            $this->modx->cacheManager->delete($cacheKey);
        }

        return $form;
    }
}
