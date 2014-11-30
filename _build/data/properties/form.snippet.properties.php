<?php

	return array(
		array(
	        'name' 		=> 'placeholderKey',
	        'desc' 		=> 'form_snippet_placeholderkey_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'form',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'tplBulkError',
	        'desc' 		=> 'form_snippet_tplbulkerror_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> '@INLINE:<li>[[+error]]</li>',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'tplBulkWrapper',
	        'desc' 		=> 'form_snippet_tplbulkwrapper_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> '@INLINE:<p class="error-notices">[[+error]]</p>',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'tplError',
	        'desc' 		=> 'form_snippet_tplerror_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> '@INLINE:<span class="error-notice-desc"><span class="error-notice-desc-inner">[[+error]]</span></span>',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    )
	);

?>