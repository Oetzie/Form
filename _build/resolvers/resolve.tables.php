<?php

	if ($object->xpdo) {
	    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
	        case xPDOTransport::ACTION_INSTALL:
	            $modx =& $object->xpdo;
	            $modx->addPackage('form', $modx->getOption('form.core_path', null, $modx->getOption('core_path').'components/form/').'model/');
	
	            $manager = $modx->getManager();
	
	            $manager->createObjectContainer('FormFormSave');
	
	            break;
	        case xPDOTransport::ACTION_UPGRADE:
	            break;
	    }
	}
	
	return true;