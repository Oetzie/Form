<?php

	$mtime 	= explode(' ', microtime());
	$tstart = $mtime[1] + $mtime[0];
	
	set_time_limit(0);

	define('PKG_NAME',			'Form');
	define('PKG_NAME_LOWER', 	strtolower(PKG_NAME));
	define('PKG_NAMESPACE', 	strtolower(PKG_NAME));
	define('PKG_VERSION',		'1.0.0');
	define('PKG_RELEASE',		'pl');

	$root = dirname(dirname(__FILE__)).'/';
	
	$sources = array(
	    'root' 			=> $root,
	    'core' 			=> $root.'core/components/'.PKG_NAME_LOWER,
	    'assets' 		=> $root.'assets/components/'.PKG_NAME_LOWER,
	    'model' 		=> $root.'core/components/'.PKG_NAME_LOWER.'/model/',
	);

	require_once $sources['build'].'/build.config.php';
	require_once MODX_CORE_PATH.'model/modx/modx.class.php';
	
	$modx = new modX();
	$modx->initialize('mgr');
	$modx->setLogLevel(modX::LOG_LEVEL_INFO);
	$modx->setLogTarget('ECHO');
	
	echo XPDO_CLI_MODE ? '' : '<pre>';

	$modx->loadClass('transport.modPackageBuilder', '', false, true);

	$manager = $modx->getManager();
	
	$generator= $manager->getGenerator();

	$generator->classTemplate = <<<EOD
		<?php
			/**
			 * [+phpdoc-package+]
			 */
			 
			class [+class+] extends [+extends+] {}
		?>
	EOD;
	
	$generator->platformTemplate = <<<EOD
		<?php
			/**
			 * [+phpdoc-package+]
			 */
			
			require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\\\', '/') . '/[+class-lowercase+].class.php');
			class [+class+]_[+platform+] extends [+class+] {}
		?>
	EOD;
	
	$generator->mapHeader= <<<EOD
		<?php
			/**
			 * [+phpdoc-package+]
			 */
	EOD;
		
	$generator->parseSchema($sources['model'].'schema/'.PKG_NAME_LOWER.'.mysql.schema.xml', $sources['model']);

	$mtime		= explode(' ', microtime());
	$tend		= $mtime[1] + $mtime[0];
	$totalTime	= ($tend - $tstart);
	$totalTime	= sprintf("%2.4f s", $totalTime);

	$modx->log(modX::LOG_LEVEL_INFO, 'Package Built: Execution time: {'.$totalTime.'}');

	echo XPDO_CLI_MODE ? '' : '</pre>';
	
	exit();
	
?>