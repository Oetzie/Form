<?php

	$chunks = array();
	
	foreach (glob($sources['chunks'].'/*.tpl') as $key => $value) {
		$name = str_replace('.chunk.tpl', '', substr($value, strrpos($value, '/') + 1, strlen($value)));
		
		$chunks[$name] = $modx->newObject('modChunk');
		$chunks[$name]->fromArray(array(
			'id' 			=> 1,
			'name'			=> ucfirst($name),
			'description'	=> PKG_NAME.' '.PKG_VERSION.'-'.PKG_RELEASE.' chunk for MODx Revolution',
			'content'		=> getSnippetContent($value)
		));
		
		if (file_exists(__DIR__.'/properties/'.$name.'.chunk.properties.php')) {
			$chunks[$name]->setProperties(include_once __DIR__.'/properties/'.$name.'.chunk.properties.php');
		}
	}
	
	return $chunks;

?>