<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modx->addPackage('form', $modx->getOption('form.core_path', null, $modx->getOption('core_path') . 'components/form/') . 'model/');

            $manager = $modx->getManager();

            $manager->createObjectContainer('FormForm');

            break;
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modx->addPackage('form', $modx->getOption('form.core_path', null, $modx->getOption('core_path') . 'components/form/') . 'model/');

            $manager = $modx->getManager();

            $manager->addField('FormForm', 'formbuilder_id', [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'null'          => false,

                'after'         => 'resource_id'
            ]);

            $manager->addField('FormForm', 'encryption', [
                'dbtype'        => 'varchar',
                'precision'     => '15',
                'phptype'       => 'string',
                'null'          => false,
                'default'       => '',

                'after'         => 'ip'
            ]);

            break;
    }
}

return true;
