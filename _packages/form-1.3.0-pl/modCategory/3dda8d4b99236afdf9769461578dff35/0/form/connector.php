<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(dirname(dirname(__DIR__))) . '/config.core.php';

require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$modx->getService('form', 'Form', $modx->getOption('form.core_path', null, $modx->getOption('core_path') . 'components/form/') . 'model/form/');

if ($modx->form instanceof Form) {
    $modx->request->handleRequest([
        'processors_path'   => $modx->form->config['processors_path'],
        'location'          => ''
    ]);
}
