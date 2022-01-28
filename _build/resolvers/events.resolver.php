<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$package = 'Form';

$events = [[
    'name'          => 'onHandleForm',
    'service'       => 6,
    'groupname'     => 'form'
]];

$success = false;

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;

            foreach ($events as $event) {
                if (isset($event['name'])) {
                    $object = $modx->getObject('modEvent', [
                        'name' => $event['name']
                    ]);

                    if (!$object) {
                        $object = $modx->newObject('modEvent');
                    }

                    if ($object) {
                        $object->set('name', $event['name']);

                        $object->fromArray($event);

                        $object->save();
                    }
                }
            }

            $success = true;

            break;
        case xPDOTransport::ACTION_UNINSTALL:
            $success = true;

            break;
    }
}

return $success;
