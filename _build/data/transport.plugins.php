<?php

	$plugins = array();
	
	foreach (glob($sources['plugins'].'/*.php') as $key => $value) {
		$name = str_replace('.plugin.php', '', substr($value, strrpos($value, '/') + 1, strlen($value)));
		
		$plugins[$name] = $modx->newObject('modPlugin');
		$plugins[$name]->fromArray(array(
			'id' 			=> 1,
			'name'			=> ucfirst($name),
			'description'	=> PKG_NAME.' '.PKG_VERSION.'-'.PKG_RELEASE.' plugin for MODx Revolution',
			'content'		=> getSnippetContent($value)
		));
		
		if (file_exists(__DIR__.'/events/'.$name.'.events.php')) {
			$events = array();
			
			foreach (include_once __DIR__.'/events/'.$name.'.events.php' as $key => $value) {
				$events[$key]= $modx->newObject('modPluginEvent');
				$events[$key]->fromArray($value, '', true, true);
			}
			
			$plugins[$name]->addMany($events);
		}
	}
		
	return $plugins;

?>