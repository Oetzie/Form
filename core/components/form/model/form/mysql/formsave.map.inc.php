<?php

	/**
	 * Form
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Form, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * Form is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Form is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Form; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */
	 
	$xpdo_meta_map['FormSave']= array(
		'package' 	=> 'form',
		'version' 	=> '1.0',
		'table' 	=> 'form_save',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'			=> null,
			'resource_id'	=> null,
			'name'			=> null,
			'ip' 			=> null,
			'data'			=> null,
			'active'		=> null,
			'editedon'		=> null
		),
		'fieldMeta'	=> array(
			'id' 		=> array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'index' 	=> 'pk',
				'generated'	=> 'native'
			),
			'resource_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'name' 		=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '75',
				'phptype' 	=> 'string',
				'null' 		=> false,
				'default' 	=> ''
			),
			'ip' 		=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '15',
				'phptype' 	=> 'string',
				'null' 		=> false,
				'default' 	=> ''
			),
			'data' 	=> array(
				'dbtype' 	=> 'text',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'active'	=> array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'default'	=> 1
			),
			'editedon' 	=> array(
				'dbtype' 	=> 'timestamp',
				'phptype' 	=> 'timestamp',
				'null' 		=> false
			)
		),
		'indexes'	=> array(
			'PRIMARY'	=> array(
				'alias' 	=> 'PRIMARY',
				'primary' 	=> true,
				'unique' 	=> true,
				'columns' 	=> array(
					'id' 		=> array(
						'collation' => 'A',
						'null' 		=> false,
					)
				)
			)
		),
		'aggregates' => array(
			'modResource'	=> array(
				'local'			=> 'resource_id',
				'class' 		=> 'modResource',
				'foreign' 		=> 'id',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			)
		)
	);

?>