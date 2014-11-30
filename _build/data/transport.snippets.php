<?php

	$snippets = array();
	
	foreach (glob($sources['snippets'].'/*.php') as $key => $value) {
		$name = str_replace('.snippet.php', '', substr($value, strrpos($value, '/') + 1, strlen($value)));
		
		$snippets[$name] = $modx->newObject('modSnippet');
		$snippets[$name]->fromArray(array(
			'id' 			=> 1,
			'name'			=> ucfirst($name),
			'description'	=> PKG_NAME.' '.PKG_VERSION.'-'.PKG_RELEASE.' snippet for MODx Revolution',
			'content'		=> getSnippetContent($value)
		));
		
		if (file_exists(__DIR__.'/properties/'.$name.'.snippet.properties.php')) {
			$snippets[$name]->setProperties(include_once __DIR__.'/properties/'.$name.'.snippet.properties.php');
		}
	}
	
	return $snippets;

?>