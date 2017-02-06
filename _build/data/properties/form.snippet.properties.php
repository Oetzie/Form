<?php

	return array(
		array(
	        'name' 		=> 'action',
	        'desc' 		=> 'form_snippet_action_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'self',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'extensions',
	        'desc' 		=> 'form_snippet_extensions_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> '',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'handler',
	        'desc' 		=> 'form_snippet_handler_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'submit',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'method',
	        'desc' 		=> 'form_snippet_method_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'POST',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'prefix',
	        'desc' 		=> 'form_snippet_prefix_desc',
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
	        'value'		=> '@INLINE:<li class="[[+class]]">[[+error]]</li>',
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
	        'value'		=> '@INLINE:<div class="error-notice-desc">[[+error]]</div>',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'type',
	        'desc' 		=> 'form_snippet_type_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'SET',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'validation',
	        'desc' 		=> 'form_snippet_validation_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> '{}',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    )
	);

?>