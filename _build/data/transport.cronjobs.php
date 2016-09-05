<?php

	$cronjobs = array();
	
	foreach (glob($sources['cronjobs'].'/*.php') as $key => $value) {
		$name = str_replace('.cronjob.php', '', substr($value, strrpos($value, '/') + 1, strlen($value)));
		
		$cronjobs[$name] = $modx->newObject('modSnippet');
		$cronjobs[$name]->fromArray(array(
			'id' 			=> 1,
			'name'			=> ucfirst($name),
			'description'	=> PKG_NAME.' '.PKG_VERSION.'-'.PKG_RELEASE.' cronjob snippet for MODx Revolution',
			'content'		=> getSnippetContent($value)
		));
		
		if (file_exists(__DIR__.'/properties/'.$name.'.cronjob.properties.php')) {
			$cronjobs[$name]->setProperties(include_once __DIR__.'/properties/'.$name.'.cronjob.properties.php');
		}
	}
	
	return $cronjobs;

?>