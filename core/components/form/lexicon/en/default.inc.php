<?php

	/**
	 * Form
	 *
	 * Copyright 2014 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Form, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * Form is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Form is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Form; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	$_lang['form'] 											= 'Forms';
	$_lang['form.desc'] 									= 'Show completed forms.';
		
	$_lang['area_form']										= 'Form';
	
	$_lang['form_snippet_placeholderkey_desc']				= 'The prefix of all placeholders, default is "form".';
	$_lang['form_snippet_tplerror_desc']					= 'The template of an error. This can start with @INLINE:, @CHUNK: or chunk name.';
	$_lang['form_snippet_tplbulkerror_desc']				= 'The template of an error in the bulkoverview. This can start with @INLINE:, @CHUNK: or chunk name.';
	$_lang['form_snippet_tplbulkwrapper_desc']				= 'The template of the wrapper of the bulkoverview of the errors. This can start with @INLINE:, @CHUNK: or chunk name.';
	
	$_lang['form.formsave']									= 'Form';
	$_lang['form.formsaves']								= 'Forms';
	$_lang['form.formsave_desc']							= 'Bekijk hier alle formulieren die via de website zijn ingevuld. Status "compleet" betekend dat het formulier succesvol is ingevuld (zonder fouten), status "incompleet" betekend dat het formulier met fouten is ingevuld. Als een formulier vaak de status op "incompleet" staat betekend dit dat het formulier/labels niet duidelijk zijn en eigenlijk geoptimaliseerd dient te worden.';
	$_lang['form.formsave_show']							= 'Show form';
	$_lang['form.formsave_remove']							= 'Delete form';
	$_lang['form.formsave_remove_confirm']					= 'Are you sure you want to delete this form?';
	$_lang['form.formsave_remove_selected']					= 'Delete selected forms';
	$_lang['form.formsave_remove_selected_confirm']			= 'Are you sure you want to delete the selected forms?';
	$_lang['form.formsave_reset']							= 'Delete all forms';
	$_lang['form.formsave_reset_confirm']					= 'Are you sure you want to delete all forms?';
	
	$_lang['form.label_resource']							= 'Resource';
	$_lang['form.label_resource_desc']						= 'The resource where the form is completed.';
	$_lang['form.label_name']								= 'Name';
	$_lang['form.label_name_desc']							= 'The name of the completed form.';
	$_lang['form.label_ipnumber']							= 'IP number';
	$_lang['form.label_ipnumber_desc']						= 'IP number of the submitter.';
	$_lang['form.label_data']								= 'Data';
	$_lang['form.label_data_desc']							= 'The data of the completed form.';
	$_lang['form.label_active']								= 'Status';
	$_lang['form.label_active_desc']						= 'The validation status of the completed form.';
	$_lang['form.label_date']								= 'Completed at';
	$_lang['form.label_date_desc']							= 'The date when the form was completed.';
	
	$_lang['form.filter_context']							= 'Filter at context...';
	$_lang['form.filter_status']							= 'Filter at status...';
	$_lang['form.form']										= 'Form';
	$_lang['form.valid']									= 'Complete';
	$_lang['form.notvalid']									= 'Incomplete';
	
	$_lang['form.error_validate']							= 'The\'re a few errors occurred in the form, fill out the form and try again. ';
	$_lang['form.error_required']							= 'This field is required.';
	$_lang['form.error_blank']								= 'This field can not be empty.';
	$_lang['form.error_equals']								= 'This field should equal to "[[+equals]]".';
	$_lang['form.error_equalselement']						= 'This field should equal to the field  "[[+equalsElement]]".';
	$_lang['form.error_contains']							= 'This field should contain "[[+contains]]".';
	$_lang['form.error_minlength']							= 'This field required at least [[+minLength]] characters.';
	$_lang['form.error_maxlength']							= 'This field can only have [[+maxLength]] characters.';
	$_lang['form.error_betweenlength']						= 'This field can only have between [[+minLength]] and [[+maxLength]] characters.';
	$_lang['form.error_minvalue']							= 'This field should be greater than [[+minValue]].';
	$_lang['form.error_maxvalue']							= 'This field should be less than [[+maxValue]].';
	$_lang['form.error_betweenvalue']						= 'This field can only be between [[+minValue]] and [[+maxValue]].';
	$_lang['form.error_regex']								= 'This field must equal to "[[+regex]]".';
	$_lang['form.error_email']								= 'This field is not a valid emailadress.';
	$_lang['form.error_ip']									= 'This field is not a valid IP number';
	$_lang['form.error_number']								= 'This field can only be numeric characters.';
	$_lang['form.error_string']								= 'This field can only be alphabetic characters.';
	$_lang['form.error_date']								= 'This field is not a valid date.';
	$_lang['form.error_mindate']							= 'This field must be greater than [[+minDate]].';
	$_lang['form.error_maxdate']							= 'This field must be less than [[+maxDate]].';
	$_lang['form.error_betweendate']						= 'This field can only be between [[+minDate]] and [[+maxDate]].';
	
	$_lang['form.error_extension_email']					= 'E-mail could not be send to "[[+emails]]", please try again.';
	$_lang['form.error_extension_newsletter_subscribe']				= 'An error occurred during subscribing for the newsletter, try it again.';
	$_lang['form.error_extension_newsletter_subscribe_confirm']		= 'An error occurred during confirming your subscribing for the newsletter, try again.';
	$_lang['form.error_extension_newsletter_unsubscribe']			= 'An error occurred during unsubscribing for the newsletter, try it again.';
	$_lang['form.error_extension_newsletter_unsubscribe_confirm']	= 'An error occurred during confirming your unsubscribing for the newsletter, try again.';
	
?>