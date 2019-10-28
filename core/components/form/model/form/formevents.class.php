<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

/**
 * @todo Custom snippet event
 */
class FormEvents
{
    const BEFORE_POST   = 'onBeforePost';
    const AFTER_POST    = 'onAfterPost';
    const VALIDATE_POST = 'onValidatePost';

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
    public $plugins = [];

    /**
     * @access public.
     *
     * @param modX   $modx .
     * @param Object $form .
     */
    public function __construct(modX &$modx, $form)
    {
        $this->modx =& $modx;
        $this->form =& $form;
    }

    /**
     * @access pubic.
     * @param Array $plugins.
     */
    public function setPlugins(array $plugins = [])
    {
        foreach ((array) $plugins as $plugin => $properties) {
            $this->setPlugin($plugin, $properties);
        }
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @access public.
     * @param String $plugin.
     * @param Mixed $properties.
     */
    public function setPlugin($plugin, $properties)
    {
        $this->plugins[$plugin] = $properties;
    }


    /**
     * @access public.
     * @param String $plugin.
     * @return Boolean|Mixed.
     */
    public function getPlugin($plugin)
    {
        if (isset($this->plugins[$plugin])) {
            return $this->plugins[$plugin];
        }

        return false;
    }

    /**
     * @access public.
     * @param String $event.
     * @return Array.
     */
    public function invokeEvent($event)
    {
        $output = [];

        foreach ($this->getPlugins() as $plugin => $properties) {
            $output[$plugin] = $this->invokePlugin($plugin, $event, $properties);
        }

        return $output;
    }

    /**
     * @access public.
     * @param String $plugin.
     * @param String $event.
     * @param Mixed $properties.
     * @return Mixed.
     */
    public function invokePlugin($plugin, $event, $properties)
    {
        if (preg_match('/^mail(.*?)$/', $plugin)) {
            $plugin = 'mail';
        }

        if (method_exists($this, $plugin)) {
            return $this->{$plugin}($event, $properties);
        }

        $snippet = $this->modx->getObject('modSnippet', [
            'name' => $plugin
        ]);

        if ($snippet) {
            return $snippet->process([
                'event'         => $event,
                'properties'    => $properties,
                'form'          => &$this->form
            ]);
        }

        return false;
    }

    /**
     * @access public.
     * @param String $event.
     * @return Mixed.
     */
    public function recaptcha($event)
    {
        if (in_array($event, [self::BEFORE_POST, self::VALIDATE_POST], true)) {
            if ($event === self::BEFORE_POST) {
                return [
                    'output' => '<div class="g-recaptcha" data-sitekey="' . $this->modx->getOption('form.recaptcha_site_key') . '"></div>'
                ];
            }

            if ($this->form->getValidator()->isValid()) {
                $publicKey  = $this->form->getOption('recaptcha_site_key');
                $secretKey  = $this->form->getOption('recaptcha_secret_key');

                if (empty($publicKey)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.recaptcha.' . $event .'] could not init recaptcha, site key not set.');

                    return false;
                }

                if (empty($publicKey)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.recaptcha.' . $event .'] could not init recaptcha, secret key not set.');

                    return false;
                }

                if ($event === self::VALIDATE_POST) {
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL             => 'https://www.google.com/recaptcha/api/siteverify',
                        CURLOPT_RETURNTRANSFER  => true,
                        CURLOPT_CONNECTTIMEOUT  => 10,
                        CURLOPT_POSTFIELDS      => http_build_query([
                            'secret'                => $this->modx->getOption('form.recaptcha_secret_key'),
                            'response'              => $this->form->getCollection()->getValue('g-recaptcha-response')
                        ])
                    ]);

                    $response       = curl_exec($curl);
                    $responseInfo   = curl_getinfo($curl);

                    if (isset($responseInfo['http_code']) && (int) $responseInfo['http_code'] === 200) {
                        $response = json_decode($response, true);

                        if ($response) {
                            if (isset($response['success']) && $response['success'] === true) {
                                return true;
                            }
                        }
                    }

                    $this->form->getValidator()->setError('recaptcha', 'recaptcha');

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @access public.
     * @param String $event.
     * @param Array $properties.
     * @return Mixed.
     */
    public function save($event, array $properties = [])
    {
        if ($event === self::AFTER_POST) {
            if ($this->form->getValidator()->isValid() || $this->form->getOption('form_save_invalid')) {
                if (!isset($properties['fields'])) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.save.' . $event .'] could not init save, fields.');

                    return false;
                }

                if (!isset($properties['name'])) {
                    $properties['name'] = $this->modx->resource->get('pagetitle');
                }

                $data = [];

                foreach ((array) $properties['fields'] as $key => $field) {
                    if (!is_array($field)) {
                        $field = [
                            'label' => $field
                        ];
                    }

                    if (!isset($field['type'])) {
                        $field['type'] = 'textfield';
                    }

                    if ($error = $this->form->getValidator()->getError($key)) {
                        $field['valid'] = false;
                        $field['error'] = $error[0]['error'];
                    } else {
                        $field['valid'] = true;
                        $field['error'] = '';
                    }

                    $field['value'] = $this->form->getCollection()->getValue($key);

                    $data[$key] = $field;
                }

                $object = $this->modx->newObject('FormForm');

                if ($object) {
                    $object->setField($data);

                    $object->fromArray([
                        'name'          => $properties['name'],
                        'resource_id'   => $this->modx->resource->get('id'),
                        'ip'            => $_SERVER['REMOTE_ADDR'],
                        'active'        => $this->form->getValidator()->isValid(),
                        'editedon'      => date('Y-m-d H:i:s')
                    ]);

                    $object->save();
                }
            }
        }

        return true;
    }

    /**
     * @access public.
     * @param String $event.
     * @param Array $properties.
     * @return Mixed.
     */
    public function email($event, array $properties = [])
    {
        if ($event === self::AFTER_POST) {
            if ($this->form->getValidator()->isValid()) {
                $mailer = $this->modx->getService('mail', 'mail.modPHPMailer');

                if ($mailer === null) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.' . $event .'] could not init email, could not load mail.modPHPMailer.');
                }

                $placeholders = $this->form->getCollection()->getValues();

                foreach ((array) $placeholders as $key => $placeholder) {
                    if (is_array($placeholder)) {
                        $placeholders[$key . '_formatted'] = implode(', ', $placeholder);
                    } else {
                        $placeholders[$key . '_formatted'] = $placeholder;
                    }
                }

                if (isset($properties['tpl'])) {
                    $properties['body'] = $this->form->getChunk($properties['tpl'], array_merge([
                        'subject' => $properties['subject']
                    ], $placeholders));
                }

                if (isset($properties['tplWrapper'])) {
                    $properties['body'] = $this->form->getChunk($properties['tplWrapper'], array_merge($placeholders, [
                        'output' => $properties['body']
                    ]));
                }

                if (empty($properties['body'])) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.' . $event .'] could not init email, body empty.');

                    return false;
                }

                if (!isset($properties['subject'])) {
                    $properties['subject'] = 'Form ' . $this->modx->resource->get('pagetitle');
                }

                if (!isset($properties['isHtml'])) {
                    $properties['isHtml'] = true;
                }

                $addresses = [];

                foreach (['to', 'reply', 'cc', 'bcc', 'from'] as $type) {
                    $key        = 'email' . ucfirst($type);
                    $keyFrom    = 'email' . ucfirst($type) . 'Field';

                    if (isset($properties[$key])) {
                        if (is_string($properties[$key])) {
                            $addresses[$type][] = [
                                'name'  => $properties[$key],
                                'email' => $properties[$key]
                            ];
                        } else {

                        }
                    }

                    if (isset($properties[$keyFrom])) {
                        $addresses[$type][] = [
                            'email'=> $this->form->getCollection()->getValue($properties[$keyFrom])
                        ];
                    }
                }

                if (empty($addresses['from'])) {
                    $addresses['from'][] = [
                        'name'  => $this->modx->getOption('emailsender'),
                        'email' => $this->modx->getOption('site_name')
                    ];
                }

                $mailer->reset();
                $mailer->setHTML((bool) $properties['isHtml']);

                foreach ($addresses as $type => $emails) {
                    foreach ((array) $emails as $email) {
                        if ($type === 'from') {
                            $mailer->set(modMail::MAIL_FROM, $email['email']);
                            $mailer->set(modMail::MAIL_FROM_NAME, $email['name'] ?: $email['email']);
                        } else if ($type === 'reply') {
                            $mailer->address('reply-to', $email['email'], $email['name'] ?: $email['email']);
                        } else {
                            $mailer->address($type, $email['email'], $email['name'] ?: $email['email']);
                        }
                    }
                }

                $mailer->set(modMail::MAIL_SUBJECT, $properties['subject']);
                $mailer->set(modMail::MAIL_BODY, $properties['body']);

                if (isset($properties['attachments'])) {
                    if (is_string($properties['attachments'])) {
                        $properties['attachments'] = explode(',', $properties['attachments']);
                    }

                    foreach ($properties['attachments'] as $attachment) {
                        $filename   = trim(substr($attachment, strrpos($attachment, '/') + 1, strlen($attachment)));
                        $attachment = rtrim($this->modx->getOption('base_path', null, MODX_BASE_PATH), '/') . '/' . ltrim($attachment, '/');

                        if (file_exists($attachment)) {
                            $mailer->mailer->addAttachment($attachment, $filename, 'base64', 'application/octet-stream');
                        } else {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.' . $event .'] could not find attachment "' . $attachment . '".');
                        }
                    }
                }

                if (isset($properties['attachmentFields'])) {
                    if (is_string($properties['attachmentFields'])) {
                        $properties['attachmentFields'] = explode(',', $properties['attachmentFields']);
                    }

                    foreach ($properties['attachmentFields'] as $attachmentField) {
                        $attachment = $this->form->getCollection()->getValue($attachmentField);

                        if (isset($attachment['tmp_name'], $attachment['error']) && $attachment['error'] === UPLOAD_ERR_OK) {
                            $mailer->mailer->addAttachment($attachment['tmp_name'], $attachment['name'], 'base64', $attachment['type'] ?: 'application/octet-stream');
                        } else {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.' . $event .'] could not find attachment from field "' . $attachmentField . '".');
                        }
                    }
                }

                if (!$mailer->send()) {
                    $this->form->getValidator()->setError('send_mail', 'send_mail');

                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.' . $event .'] could not send email because "' . $mailer->mailer->ErrorInfo . '".');

                    return false;
                }
            }
        }

        return true;
    }
}
