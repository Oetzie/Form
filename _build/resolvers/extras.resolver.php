<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$package = 'Form';

$url     = 'https://modx.werkvanoetzie.nl/api/v1/package';

$params  = [];

$modx =& $object->xpdo;

$criteria = $modx->newQuery('transport.modTransportPackage');

$criteria->where([
    'workspace' => 1,
    "(SELECT
        `signature`
        FROM {$modx->getTableName('modTransportPackage')} AS `latestPackage`
        WHERE `latestPackage`.`package_name` = `modTransportPackage`.`package_name`
        ORDER BY
            `latestPackage`.`version_major` DESC,
            `latestPackage`.`version_minor` DESC,
            `latestPackage`.`version_patch` DESC,
            IF(`release` = '' OR `release` = 'ga' OR `release` = 'pl','z',`release`) DESC,
            `latestPackage`.`release_index` DESC
            LIMIT 1,1) = `modTransportPackage`.`signature`",
]);

$criteria->where([
    [
        'modTransportPackage.package_name' => strtolower($package)
    ],
    'installed:IS NOT' => null
]);

$criteria->limit(1);

$packageVersion     = '';
$packagePrevVersion = '';
$managerVersion     = $modx->getOption('settings_version');
$managerLanguage    = $modx->getOption('manager_language');

if ($prevPackage = $modx->getObject('transport.modTransportPackage', $criteria)) {
    $packagePrevVersion = $prevPackage->get('version_major') . '.' . $prevPackage->get('version_minor');
    $packagePrevVersion .= '.' . $prevPackage->get('version_patch');
    $packagePrevVersion .= '-' . $prevPackage->get('release');
}

if ($options['topic']) {
    $topic          = trim($options['topic'], '/');
    $topic          = explode('/', $topic);
    $signature      = end($topic);

    $packageVersion = str_replace(strtolower($package) . '-', '', $signature);
}

$action = '';

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $action = 'install';

        break;
    case xPDOTransport::ACTION_UPGRADE:
        $action = 'update';

        break;
    case xPDOTransport::ACTION_UNINSTALL:
        $action = 'uninstall';

        $version                = $packagePrevVersion;
        $setupOptionsPath       = explode('/', $options['setup-options']);
        $signature              = $setupOptionsPath[0];

        $packagePrevVersion     = str_replace(strtolower($package) . '-', '', $signature);

        break;
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: OETZIE-A64XHC7PNY8G61L79E'
]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
    'package'               => $options['namespace'],
    'package_version'       => $packageVersion,
    'package_prev_version'  => $packagePrevVersion,
    'php_version'           => PHP_VERSION,
    'manager_version'       => $managerVersion,
    'manager_language'      => $managerLanguage,
    'type'                  => $action,
    'domain'                => $_SERVER['SERVER_NAME'],
]));

curl_setopt($curl, CURLOPT_TIMEOUT, 120);

$response     = curl_exec($curl);

curl_close($curl);

return true;