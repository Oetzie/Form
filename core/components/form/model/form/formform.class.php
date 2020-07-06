<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class FormForm extends xPDOSimpleObject
{
    /**
     * @access public.
     * @param String $data.
     * @param String $method.
     * @return String.
     */
    public function encrypt($data, $method = '')
    {
        if ($method === 'openssl') {
            if (function_exists('openssl_encrypt')) {
                return base64_encode(openssl_encrypt($data, 'AES-256-CBC', md5($this->getEncryptKey()), 0, $this->getEncryptKeyIv()));
            }

            $this->xpdo->log(modX::LOG_LEVEL_ERROR, '[Form.encrypt] openssl_encrypt is not available.');
        } else if ($method === 'mcrypt') {
            if (function_exists('mcrypt_encrypt')) {
                return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->getEncryptkey()), $data, MCRYPT_MODE_CBC, md5(md5($this->getEncryptkey()))));
            }

            $this->xpdo->log(modX::LOG_LEVEL_ERROR, '[Form.encrypt] mcrypt_encrypt is not available.');
        }

        return $data;
    }

    /**
     * @access public.
     * @param String $data.
     * @param String $method.
     * @return String.
     */
    public function decrypt($data, $method = '')
    {
        if ($method === 'openssl') {
            if (function_exists('openssl_encrypt')) {
                return openssl_decrypt(base64_decode($data), 'AES-256-CBC', md5($this->getEncryptKey()), 0, $this->getEncryptKeyIv());
            }

            $this->xpdo->log(modX::LOG_LEVEL_ERROR, '[Form.encrypt] openssl_encrypt is not available.');
        } else if ($method === 'mcrypt') {
            if (function_exists('mcrypt_encrypt')) {
                return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->getEncryptKey()), base64_decode($data), MCRYPT_MODE_CBC, md5(md5($this->getEncryptKey()))), "\0");
            }

            $this->xpdo->log(modX::LOG_LEVEL_ERROR, '[Form.encrypt] mcrypt_encrypt is not available.');
        }

        return $data;
    }

    /**
     * @access public.
     * @param Array $data.
     * @return String.
     */
    public function setField(array $data = [])
    {
        $method = $this->getEncryptMethod();

        $this->set('encryption', $method);
        $this->set('data', $this->encrypt(json_encode($data), $method));
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getFields()
    {
        return json_decode($this->decrypt($this->get('data'), $this->get('encryption')), true);
    }

    /**
     * @access public.
     * @return String.
     */
    public function getEncryptMethod()
    {
        $method = $this->xpdo->getOption('form.encrypt_method', null, '');

        if (empty($method)) {
            $method = 'openssl';

            $setting = $this->xpdo->getObject('modSystemSetting', [
                'key' => 'form.encrypt_method'
            ]);

            if (!$setting) {
                $setting = $this->xpdo->newObject('modSystemSetting', [
                    'namespace' => 'form',
                    'area'      => 'form'
                ]);
            }

            $setting->set('key', 'form.encrypt_method');
            $setting->set('value', $method);

            $setting->save();
        }

        if ((bool) $this->xpdo->getOption('form.encrypt', null, true)) {
            return $method;
        }

        return '';
    }

    /**
     * @access public.
     * @return String.
     */
    public function getEncryptKey()
    {
        $key = $this->xpdo->getOption('form.encrypt_key', null, '');

        if (empty($key)) {
            $key = $this->xpdo->site_id;

            $setting = $this->xpdo->getObject('modSystemSetting', [
                'key' => 'form.encrypt_key'
            ]);

            if (!$setting) {
                $setting = $this->xpdo->newObject('modSystemSetting', [
                    'namespace' => 'form',
                    'area'      => 'form'
                ]);
            }

            $setting->set('key', 'form.encrypt_key');
            $setting->set('value', $key);

            $setting->save();
        }

        return $key;
    }

    /**
     * @access public.
     * @return String.
     */
    public function getEncryptKeyIv()
    {
        return substr(hash('sha256', md5(hash('sha256', $this->getEncryptKey()))), 0, 16);
    }
}
