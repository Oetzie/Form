<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class EmailFormPlugin extends DefaultFormPlugin
{
    /**
     * @access public.
     * @return Boolean.
     */
    public function onValidateSuccess()
    {
        $mailer = $this->form->modx->getService('mail', 'mail.modPHPMailer');

        if ($mailer === null) {
            $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.onValidateSuccess] could not init email, could not load mail.modPHPMailer.');
        }

        $placeholders = array_merge($this->form->getCollection()->getFormattedValues(), [
            'meta'      => $this->form->modx->lexicon('form.form_meta', [
                'ip'        => $_SERVER['REMOTE_ADDR'],
                'title'     => $this->form->modx->resource->get('pagetitle'),
                'url'       => $this->form->modx->makeUrl($this->form->modx->resource->get('id'), null, null, 'full')
            ])
        ]);

        if (isset($this->config['placeholders'])) {
            $placeholders = array_merge($placeholders, (array) $this->config['placeholders']);
        }

        if (!empty($this->config['tpl'])) {
            $this->config['body'] = $this->form->getChunk($this->config['tpl'], array_merge([
                'subject' => $this->config['subject']
            ], $placeholders));
        }

        if (!empty($this->config['tplWrapper'])) {
            $this->config['body'] = $this->form->getChunk($this->config['tplWrapper'], array_merge($placeholders, [
                'output' => $this->config['body']
            ]));
        }

        if (empty($this->config['body'])) {
            $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.onValidateSuccess] could not init email, body empty.');

            return false;
        }

        if (!isset($this->config['subject'])) {
            $this->config['subject'] = 'Form ' . $this->form->modx->resource->get('pagetitle');
        }

        if (!isset($this->config['isHtml'])) {
            $this->config['isHtml'] = true;
        }

        $addresses = [];

        foreach (['to', 'reply', 'cc', 'bcc', 'from'] as $type) {
            $key        = 'email' . ucfirst($type);
            $keyFrom    = 'email' . ucfirst($type) . 'Field';

            if (isset($this->config[$key])) {
                if (is_string($this->config[$key])) {
                    $addresses[$type][] = [
                        'name'  => $this->config[$key],
                        'email' => $this->config[$key]
                    ];
                } else {

                }
            }

            if (isset($this->config[$keyFrom])) {
                $addresses[$type][] = [
                    'email'=> $this->form->getCollection()->getValue($this->config[$keyFrom])
                ];
            }
        }

        if (empty($addresses['from'])) {
            $addresses['from'][] = [
                'name'  => $this->form->modx->getOption('emailsender'),
                'email' => $this->form->modx->getOption('site_name')
            ];
        }

        $mailer->reset();
        $mailer->setHTML((bool) $this->config['isHtml']);

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

        $mailer->set(modMail::MAIL_SUBJECT, $this->config['subject']);
        $mailer->set(modMail::MAIL_BODY, $this->config['body']);

        if (isset($this->config['attachments'])) {
            if (is_string($this->config['attachments'])) {
                $this->config['attachments'] = explode(',', $this->config['attachments']);
            }

            foreach ($this->config['attachments'] as $attachment) {
                $filename   = trim(substr($attachment, strrpos($attachment, '/') + 1, strlen($attachment)));

                if (file_exists($attachment)) {
                    $mailer->mailer->addAttachment($attachment, $filename, 'base64', 'application/octet-stream');
                } else {
                    $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.onValidateSuccess] could not find attachment "' . $attachment . '".');
                }
            }
        }

        if (isset($this->config['attachmentFields'])) {
            if (is_string($this->config['attachmentFields'])) {
                $this->config['attachmentFields'] = explode(',', $this->config['attachmentFields']);
            }

            foreach ($this->config['attachmentFields'] as $attachmentField) {
                $attachment = $this->form->getCollection()->getValue($attachmentField);

                if (isset($attachment['tmp_name'], $attachment['error']) && $attachment['error'] === UPLOAD_ERR_OK) {
                    $mailer->mailer->addAttachment($attachment['tmp_name'], $attachment['name'], 'base64', $attachment['type'] ?: 'application/octet-stream');
                } else {
                    //$this->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.' . $event . '] could not find attachment from field "' . $attachmentField . '".');
                }
            }
        }

        if (!$mailer->send()) {
            $this->form->getValidators()->setError('send_mail', 'send_mail');

            $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.email.onValidateSuccess] could not send email because "' . $mailer->mailer->ErrorInfo . '".');

            return false;
        }

        return true;
    }
}
