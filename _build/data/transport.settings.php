<?php

	$settings = array();
	
	$settings[0] = $modx->newObject('modSystemSetting');
	$settings[0]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.encrypt',
		'value' 	=> 1,
		'xtype' 	=> 'combo-boolean',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[1] = $modx->newObject('modSystemSetting');
	$settings[1]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.encrypt_key',
		'value' 	=> '',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[2] = $modx->newObject('modSystemSetting');
	$settings[2]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.recaptcha_secret_key',
		'value' 	=> '6LdHJSMTAAAAAFjH4t2JXFKLrABY5iVcBtcAUN5O',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[3] = $modx->newObject('modSystemSetting');
	$settings[3]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.recaptcha_site_key',
		'value' 	=> '6LdHJSMTAAAAAKRkMwYL1eFVptGQWLQ6r-8_b-2X',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[4] = $modx->newObject('modSystemSetting');
	$settings[4]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.recaptcha_url',
		'value' 	=> 'https://www.google.com/recaptcha/api/siteverify',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
		
	return $settings;
	
?>