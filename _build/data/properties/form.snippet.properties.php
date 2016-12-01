<?php

	return array(
		array(
	        'name' 		=> 'dateFormat',
	        'desc' 		=> 'form_snippet_dateformat_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> '%d-%m-%Y',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'placeholder',
	        'desc' 		=> 'form_snippet_placeholder_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'form',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'submit',
	        'desc' 		=> 'form_snippet_submit_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'submit',
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
	        'value'		=> '@INLINE:<div class="error-notice-desc"><span class="error-notice-desc-inner">[[+error]]</div>',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'type',
	        'desc' 		=> 'form_snippet_type_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'set',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'method',
	        'desc' 		=> 'form_snippet_method_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> 'post',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    )
	);

?>