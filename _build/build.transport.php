<?php

	$mtime 	= explode(' ', microtime());
	$tstart = $mtime[1] + $mtime[0];
	
	set_time_limit(0);

	define('PKG_NAME', 			'Form');
	define('PKG_NAME_LOWER', 	strtolower(PKG_NAME));
	define('PKG_NAMESPACE', 	strtolower(PKG_NAME));
	define('PKG_VERSION',		'1.2.0');
	define('PKG_RELEASE',		'pl');

	define('PRIVATE_PATH',		dirname(dirname(dirname(__FILE__))).'/private_html/');
	define('PUBLIC_PATH',		dirname(dirname(__FILE__)).'/');

	$sources = array(
	    'root' 			=> PRIVATE_PATH,
	    'build' 		=> PUBLIC_PATH.'_build/',
	    'data' 			=> PUBLIC_PATH.'_build/data/',
	    'resolvers' 	=> PUBLIC_PATH.'_build/resolvers/',
	    'core' 			=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER,
	    'assets' 		=> PUBLIC_PATH.'assets/components/'.PKG_NAME_LOWER,
	    'chunks' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/chunks/',
	    'cronjobs' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/cronjobs/',
	    'plugins' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/plugins/',
	    'snippets' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/snippets/',
	    'widgets' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/widgets/',
	    'lexicon' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/lexicon/',
	    'docs' 			=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/docs/'
	);

	require_once $sources['build'].'/includes/functions.php';
	require_once PRIVATE_PATH.'core/config/config.inc.php';
	require_once PRIVATE_PATH.'core/model/modx/modx.class.php';
	
	$modx = new modX();
	$modx->initialize('mgr');
	$modx->setLogLevel(modX::LOG_LEVEL_INFO);
	$modx->setLogTarget('ECHO');
	
	echo XPDO_CLI_MODE ? '' : '<pre>';

	$modx->loadClass('transport.modPackageBuilder', '', false, true);
	
	$builder = new modPackageBuilder($modx);
	$builder->createPackage(PKG_NAMESPACE, PKG_VERSION, PKG_RELEASE);
	$builder->registerNamespace(PKG_NAMESPACE, false, true, '{core_path}components/'.PKG_NAMESPACE.'/');
	
	$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in category...');
	
	$category = $modx->newObject('modCategory');
	$category->fromArray(array('id' => 1, 'category' => PKG_NAME), '', true, true);
	
	if (file_exists($sources['data'].'transport.chunks.php')) {
		$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in chunk(s) into category...');
		
		$chunks = include $sources['data'].'transport.chunks.php';
	
		foreach ($chunks as $chunk) {
			$category->addMany($chunk);
		}
		
		$modx->log(modX::LOG_LEVEL_INFO, 'Packed chunk(s) '.count($chunks).' into category.');
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No chunk(s) to pack...');
	}
	
	if (file_exists($sources['data'].'transport.cronjobs.php')) {	
		$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in cronjobs(s) into category...');
	
		$cronjobs = include $sources['data'].'transport.cronjobs.php';
	
		foreach ($cronjobs as $cronjob) {
			$category->addMany($cronjob);
		}

		$modx->log(modX::LOG_LEVEL_INFO, 'Packed cronjobs(s) '.count($cronjobs).' into category.');
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No cronjobs(s) to pack...');
	}

	if (file_exists($sources['data'].'transport.plugins.php')) {	
		$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in plugins(s) into category...');
	
		$plugins = include $sources['data'].'transport.plugins.php';
	
		foreach ($plugins as $plugin) {
			$category->addMany($plugin);
		}

		$modx->log(modX::LOG_LEVEL_INFO, 'Packed plugins(s) '.count($plugins).' into category.');
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No plugins(s) to pack...');
	}
	
	if (file_exists($sources['data'].'transport.snippets.php')) {	
		$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in snippet(s) into category...');
	
		$snippets = include $sources['data'].'transport.snippets.php';
	
		foreach ($snippets as $snippet) {
			$category->addMany($snippet);
		}

		$modx->log(modX::LOG_LEVEL_INFO, 'Packed snippet(s) '.count($snippets).' into category.');
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No snippet(s) to pack...');
	}
	
	$builder->putVehicle($builder->createVehicle($category, array(
	    xPDOTransport::UNIQUE_KEY 		=> 'category',
	    xPDOTransport::PRESERVE_KEYS 	=> false,
	    xPDOTransport::UPDATE_OBJECT 	=> true,
	    xPDOTransport::RELATED_OBJECTS 	=> true,
	    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
		    'Chunks' => array(
	            xPDOTransport::PRESERVE_KEYS 	=> false,
	            xPDOTransport::UPDATE_OBJECT 	=> true,
	            xPDOTransport::UNIQUE_KEY 		=> 'name'
	        ),
	        'Plugins' => array(
	            xPDOTransport::PRESERVE_KEYS 	=> false,
	            xPDOTransport::UPDATE_OBJECT 	=> true,
	            xPDOTransport::UNIQUE_KEY 		=> 'name'
	        ),
	        'PluginEvents' => array(
	            xPDOTransport::PRESERVE_KEYS 	=> true,
	            xPDOTransport::UPDATE_OBJECT 	=> false,
	            xPDOTransport::UNIQUE_KEY 		=> array('pluginid', 'event'),
	        ),
	        'Snippets' => array(
	            xPDOTransport::PRESERVE_KEYS 	=> false,
	            xPDOTransport::UPDATE_OBJECT 	=> true,
	            xPDOTransport::UNIQUE_KEY 		=> 'name'
	        )
	    )
	)));
	
	if (file_exists($sources['data'].'transport.widgets.php')) {
		$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in widgets(s) into category...');
		
		$widgets = include $sources['data'].'transport.widgets.php';
	
		foreach ($widgets as $key => $value) {
			$builder->putVehicle($builder->createVehicle($value, array(
				xPDOTransport::UNIQUE_KEY 		=> 'name',
				xPDOTransport::PRESERVE_KEYS 	=> false,
				xPDOTransport::UPDATE_OBJECT 	=> true
			)));
		}
		
		$modx->log(modX::LOG_LEVEL_INFO, 'Packed widgets(s) '.count($widgets).' into category.');
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No widgets(s) to pack...');
	}
	
	if (file_exists($sources['data'].'transport.settings.php')) {
		$settings = include $sources['data'].'transport.settings.php';
		
		$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in systemsetting(s) into category...');
		
		foreach ($settings as $key => $value) {
			$builder->putVehicle($builder->createVehicle($value, array(
				xPDOTransport::UNIQUE_KEY => 'key',
				xPDOTransport::PRESERVE_KEYS => true,
				xPDOTransport::UPDATE_OBJECT => false
			)));
		}
		
		$modx->log(modX::LOG_LEVEL_INFO, 'Packed systemsetting(s) '.count($settings).' into category.');
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No systemsetting(s) to pack...');
	}

	$modx->log(modX::LOG_LEVEL_INFO, 'Packed category.');
	
	if (file_exists($sources['data'].'transport.menu.php')) {
		$menu = include $sources['data'].'transport.menu.php';
		
		$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in menu...');
		
		if (null === $menu) {
			$modx->log(modX::LOG_LEVEL_ERROR, 'No menu to pack.');
		} else {
			$vehicle = $builder->createVehicle($menu, array(
			    xPDOTransport::PRESERVE_KEYS 	=> true,
			    xPDOTransport::UPDATE_OBJECT 	=> true,
			    xPDOTransport::UNIQUE_KEY 		=> 'text',
			    xPDOTransport::RELATED_OBJECTS 	=> true,
			    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
			        'Action' => array(
			            xPDOTransport::PRESERVE_KEYS 	=> false,
			            xPDOTransport::UPDATE_OBJECT 	=> true,
			            xPDOTransport::UNIQUE_KEY 		=> array('namespace','controller')
			        ),
			    ),
			));
			
			$modx->log(modX::LOG_LEVEL_INFO, 'Adding in PHP resolvers...');
			
			if (is_dir($sources['assets'])) {
				$vehicle->resolve('file', array(
			    	'source' => $sources['assets'],
			    	'target' => "return MODX_ASSETS_PATH.'components/';",
			    ));
			}
			
		    if (is_dir($sources['core'])) {
				$vehicle->resolve('file', array(
				    'source' => $sources['core'],
				    'target' => "return MODX_CORE_PATH.'components/';",
				));
			}
			
			if (file_exists($sources['resolvers'].'resolve.tables.php')) {
				$vehicle->resolve('php',array(
			    	'source' => $sources['resolvers'].'resolve.tables.php',
				));
			}
			
			$builder->putVehicle($vehicle);
			
			$modx->log(modX::LOG_LEVEL_INFO, 'Packed menu.');
		}
	}
		
	$modx->log(xPDO::LOG_LEVEL_INFO, 'Setting Package Attributes...');

	if (file_exists($sources['build'].'/setup.options.php')) {
		$builder->setPackageAttributes(array(
		    'license' 		=> file_get_contents($sources['docs'].'license.txt'),
		    'readme' 		=> file_get_contents($sources['docs'].'readme.txt'),
		    'changelog' 	=> file_get_contents($sources['docs'].'changelog.txt'),
		    'setup-options' => array(
	        	'source' 		=> $sources['build'].'/setup.options.php'
			)
		));
	} else {
		$builder->setPackageAttributes(array(
		    'license' 		=> file_get_contents($sources['docs'].'license.txt'),
		    'readme' 		=> file_get_contents($sources['docs'].'readme.txt'),
		    'changelog' 	=> file_get_contents($sources['docs'].'changelog.txt')
		));
	}

	$modx->log(xPDO::LOG_LEVEL_INFO, 'Zipping up package...');

	$builder->pack();
	
	$mtime		= explode(' ', microtime());
	$tend		= $mtime[1] + $mtime[0];
	$totalTime	= ($tend - $tstart);
	$totalTime	= sprintf("%2.4f s", $totalTime);

	$modx->log(modX::LOG_LEVEL_INFO, 'Package Built: Execution time: {'.$totalTime.'}');

	echo XPDO_CLI_MODE ? '' : '</pre>';
	
	exit();
	
?>