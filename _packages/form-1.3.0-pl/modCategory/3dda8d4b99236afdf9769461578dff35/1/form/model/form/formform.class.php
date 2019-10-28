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
     * @return String.
     */
    public function encrypt($data)
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->getEncryptkey()), $data, MCRYPT_MODE_CBC, md5(md5($this->getEncryptkey()))));
    }

    /**
     * @access public.
     * @param String $data.
     * @return String.
     */
    public function decrypt($data)
    {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->getEncryptkey()), base64_decode($data), MCRYPT_MODE_CBC, md5(md5($this->getEncryptkey()))), "\0");
    }

    /**
     * @access public.
     * @param Array $data.
     * @return String.
     */
    public function setField(array $data = [])
    {
        if ((bool) $this->xpdo->getOption('form.encrypt', null, true)) {
            $this->set('data', $this->encrypt(json_encode($data)));
        } else {
            $this->set('data', json_encode($data));
        }
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getFields()
    {
        if ((bool) $this->xpdo->getOption('form.encrypt', null, true)) {
            return json_decode($this->decrypt($this->get('data')), true);
        }

        return json_decode($this->get('data'), true);
    }

    /**
     * @access public.
     * @return String.
     */
    public function getEncryptkey()
    {
        $key = $this->xpdo->getOption('form.encrypt_key', null, '');

        if (empty($key)) {
            $key = $this->xpdo->site_id;

            $setting = $this->xpdo->getObject('modSystemSetting', [
                'key' => 'form.encrypt_key'
            ]);

            if (!$setting) {
                $setting = $this->xpdo->newObject('modSystemSetting', [
                    'key'       => 'form.encrypt_key',
                    'namespace' => 'form'
                ]);
            }

            $setting->set('value', $key);

            $setting->save();
        }

        return $key;
    }
}
