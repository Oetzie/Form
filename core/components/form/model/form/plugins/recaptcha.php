<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class RecaptchaFormPlugin extends DefaultFormPlugin
{
    /**
     * @access public.
     * @return Boolean.
     */
    public function onBeforePost()
    {
        $publicKey = $this->form->getOption('recaptcha_site_key');

        if (empty($publicKey)) {
            $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.recaptcha.onBeforePost] could not init recaptcha, site key not set.');

            return false;
        }

        if ($this->config['version'] === 'v3') {
            $actionKey      = 'g-recaptcha-action-' . $this->form->getProperty('submit');
            $responseKey    = 'g-recaptcha-response-' . $this->form->getProperty('submit');

            $output = '<script src="https://www.google.com/recaptcha/api.js?render=' . $publicKey . '"></script>
            <input type="hidden" name="' . $actionKey . '" value="' . str_replace('-', '_', $actionKey) . '">
            <input type="hidden" name="' . $responseKey . '" id="' . $responseKey . '" />
            <script type="text/javascript">
                grecaptcha.ready(function() {
                    grecaptcha.execute(\'' . $publicKey . '\', {action: \'' . str_replace('-', '_', $actionKey) . '\'}).then(function(token) {
                        document.querySelector(\'[id="' . $responseKey . '"]\').value = token;
                    });
                });
            </script>';
        } else {
            $output = '<script src="https://www.google.com/recaptcha/api.js"></script>
            <div class="g-recaptcha" data-sitekey="' . $publicKey . '"></div>';
        }

        $this->form->getEvents()->setValue('recaptcha', [
            'output' => $output
        ]);

        return true;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function onValidatePost()
    {
        $secretKey = $this->form->getOption('recaptcha_secret_key');

        if (empty($secretKey)) {
            $this->form->modx->log(modX::LOG_LEVEL_ERROR, '[Form.recaptcha.onValidatePost] could not init recaptcha, secret key not set.');

            return false;
        }

        $responseKey = 'g-recaptcha-response';

        if ($this->config['version'] === 'v3') {
            $responseKey = 'g-recaptcha-response-' . $this->form->getProperty('submit');
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL             => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CONNECTTIMEOUT  => 10,
            CURLOPT_POSTFIELDS      => http_build_query([
                'secret'                => $secretKey,
                'response'              => $this->form->getCollection()->getValue($responseKey)
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

        $this->form->getValidators()->setError('recaptcha', 'recaptcha');

        return false;
    }
}
