<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$xpdo_meta_map['FormForm'] = [
    'package'       => 'form',
    'version'       => '1.0',
    'table'         => 'form_form',
    'extends'       => 'xPDOSimpleObject',
    'tableMeta'     => [
        'engine'        => 'InnoDB'
    ],
    'fields'        => [
        'id'            => null,
        'resource_id'   => null,
        'formbuilder_id' => null,
        'name'          => null,
        'ip'            => null,
        'encryption'    => null,
        'data'          => null,
        'active'        => null,
        'editedon'      => null
    ],
    'fieldMeta'     => [
        'id'            => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false,
            'index'         => 'pk',
            'generated'     => 'native'
        ],
        'resource_id'   => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false
        ],
        'formbuilder_id' => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false
        ],
        'name'          => [
            'dbtype'        => 'varchar',
            'precision'     => '75',
            'phptype'       => 'string',
            'null'          => false,
            'default'       => ''
        ],
        'ip'            => [
            'dbtype'        => 'varchar',
            'precision'     => '15',
            'phptype'       => 'string',
            'null'          => false,
            'default'       => ''
        ],
        'encryption'    => [
            'dbtype'        => 'varchar',
            'precision'     => '15',
            'phptype'       => 'string',
            'null'          => false,
            'default'       => ''
        ],
        'data'          => [
            'dbtype'        => 'text',
            'phptype'       => 'string',
            'null'          => false
        ],
        'active'        => [
            'dbtype'        => 'int',
            'precision'     => '1',
            'phptype'       => 'integer',
            'null'          => false,
            'default'       => 1
        ],
        'editedon'      => [
            'dbtype'        => 'timestamp',
            'phptype'       => 'timestamp',
            'null'          => false
        ]
    ],
    'indexes'       => [
        'PRIMARY'       => [
            'alias'         => 'PRIMARY',
            'primary'       => true,
            'unique'        => true,
            'columns'       => [
                'id'            => [
                    'collation'     => 'A',
                    'null'          => false
                ]
            ]
        ]
    ],
    'aggregates'    => [
        'modResource'   => [
            'local'         => 'resource_id',
            'class'         => 'modResource',
            'foreign'       => 'id',
            'owner'         => 'foreign',
            'cardinality'   => 'one'
        ]
    ]
];
