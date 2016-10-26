<?php

	/**
	 * Form
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
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

	$_lang['form'] 													= 'Forms';
	$_lang['form.desc']												= 'View all completed forms.';
		
	$_lang['area_form']												= 'Forms';
	
	$_lang['setting_form.recaptcha_site_key']						= 'Google reCAPTCHA API site key';
	$_lang['setting_form.recaptcha_site_key_desc']					= 'The website key for the Google reCAPTCHA API, you can create this at https://www.google.com/recaptcha/admin.';
	$_lang['setting_form.recaptcha_secret_key']						= 'Google reCAPTCHA API secret key';
	$_lang['setting_form.recaptcha_secret_key_desc']				= 'The secret key for the Google reCAPTCHA API, you can create this at https://www.google.com/recaptcha/admin.';
	$_lang['setting_form.recaptcha_url']							= 'Google reCAPTCHA API URL';
	$_lang['setting_form.recaptcha_url_desc']						= 'The URL of the Google reCAPTCHA API.';
	
	$_lang['form_snippet_dateformat_desc']							= 'The format of date to check the dates. Default is "%d-%m-%Y".';
	$_lang['form_snippet_placeholder_desc']							= 'The placeholder prefix for the placeholders. Default is "form".';
	$_lang['form_snippet_submit_desc']								= 'The name of the submit to send the form. Default is "submit".';
	$_lang['form_snippet_tplbulkerror_desc']						= 'The template of an error in the bulk error. This can start with @INLINE:, @CHUNK: or chunk name.';
	$_lang['form_snippet_tplbulkwrapper_desc']						= 'The template of the bulk error wrapper. This can start with @INLINE:, @CHUNK: or chunk name.';
	$_lang['form_snippet_tplerror_desc']							= 'The template of an error. This can start with @INLINE:, @CHUNK: or chunk name.';
	$_lang['form_snippet_type_desc']								= 'The type of the form. This can be "set" or "get", default is "set".';
		
	$_lang['form.formsave']											= 'Forms';
	$_lang['form.formsaves']										= 'Forms';
	$_lang['form.formsave_desc']									= 'View all the forms filled out on the website. Status "complete" means that the form is completed without mistakes and processed, status "incomplete" means that the form is filled with errors and therefor not processed. If a form has the status "incomplete" too much means that the form is unclear and that the labels should be optimized.';
	$_lang['form.formsave_show']									= 'Show form';
	$_lang['form.formsave_remove']									= 'Delete form';
	$_lang['form.formsave_remove_confirm']							= 'Are you sure you want to delete this form?';
	$_lang['form.formsaves_remove_selected']						= 'Delete selected forms';
	$_lang['form.formsaves_remove_selected_confirm']				= 'Are you sure you want to delete the selected forms?';
	$_lang['form.formsave_reset']									= 'Delete all forms';
	$_lang['form.formsave_reset_confirm']							= 'Are you sure you want to delete all the forms?';
	
	$_lang['form.label_resource']									= 'Resource';
	$_lang['form.label_resource_desc']								= 'The resource where the form is filled.';
	$_lang['form.label_name']										= 'Name';
	$_lang['form.label_name_desc']									= 'The name of the form.';
	$_lang['form.label_ipnumber']									= 'IP number';
	$_lang['form.label_ipnumber_desc']								= 'IP number of the contributor.';
	$_lang['form.label_data']										= 'Data';
	$_lang['form.label_data_desc']									= 'The data of the form.';
	$_lang['form.label_active']										= 'Status';
	$_lang['form.label_active_desc']								= 'The validation status of the form. Status "complete" means that the form filled correctly, status "incomplete" means that the form is filled incorrectly.';
	$_lang['form.label_date']										= 'Completed at';
	$_lang['form.label_date_desc']									= 'The date when the form is completed.';
		
	$_lang['form.filter_context']									= 'Filter at context...';
	$_lang['form.filter_status']									= 'Filter at status...';
	$_lang['form.form']												= 'Form';
	$_lang['form.valid']											= 'Complete';
	$_lang['form.notvalid']											= 'Incomplete';
	$_lang['form.empty']											= 'Not filled';
	
	$_lang['form.error_required']									= 'This field is required.';
	$_lang['form.error_blank']										= 'This field should not be blank.';
	$_lang['form.error_equals']										= 'This field needs to be equal to "[[+equals]]".';
	$_lang['form.error_equalsto']									= 'This field needs to be equal to the field "[[+equalsTo]]".';
	$_lang['form.error_contains']									= 'This field needs to contain "[[+contains]]".';
	$_lang['form.error_minlength']									= 'This field should have at least [[+minLength]] characters.';
	$_lang['form.error_maxlength']									= 'This field should not have more then [[+maxLength]] characters.';
	$_lang['form.error_betweenlength']								= 'This field needs to have between [[+minLength]] and [[+maxLength]] characters.';
	$_lang['form.error_minvalue']									= 'This field needs to be bigger then [[+minValue]].';
	$_lang['form.error_maxvalue']									= 'This field needs to be smaller then [[+maxValue]].';
	$_lang['form.error_betweenvalue']								= 'This field needs to be between [[+minValue]] and [[+maxValue]].';
	$_lang['form.error_regex']										= 'This field needs to conform to "[[+regex]]".';
	$_lang['form.error_email']										= 'This field is not a valid e-mail address.';
	$_lang['form.error_ip']											= 'This field is not a valid IP nummer.';
	$_lang['form.error_url']										= 'This field is not a valid web address.';
	$_lang['form.error_iban']										= 'This field is not a valid IBAN number.';
	$_lang['form.error_phone']										= 'This field is not a valid phone number.';
	$_lang['form.error_number']										= 'This field should only contain numbers.';
	$_lang['form.error_string']										= 'This field should only contain letters.';
	$_lang['form.error_date']										= 'This field is not a valid date.';
	$_lang['form.error_mindate']									= 'This field should be later then [[+minDate]].';
	$_lang['form.error_maxdate']									= 'This field should be earlier then [[+maxDate]].';
	$_lang['form.error_betweendate']								= 'This field needs to be between [[+minDate]] and [[+maxDate]].';
	$_lang['form.error_recaptcha']									= 'This field with reCAPTCHA is not correct.';
	
	$_lang['form.error2_required']									= 'This field was not filled.';
	$_lang['form.error2_blank']										= 'This field was not empty.';
	$_lang['form.error2_equals']									= 'This field was not equal to "[[+equals]]".';
	$_lang['form.error2_equalsto']									= 'This field was not equal to the field "[[+equalsTo]]".';
	$_lang['form.error2_contains']									= 'This field did not contain "[[+contains]]".';
	$_lang['form.error2_minlength']									= 'This field did not have at least [[+minLength]] characters.';
	$_lang['form.error2_maxlength']									= 'This field had more then [[+maxLength]] characters.';
	$_lang['form.error2_betweenlength']								= 'This field did not have between [[+minLength]] and [[+maxLength]] characters.';
	$_lang['form.error2_minvalue']									= 'This field was not greater then [[+minValue]].';
	$_lang['form.error2_maxvalue']									= 'This field was not smaller then [[+maxValue]].';
	$_lang['form.error2_betweenvalue']								= 'This field was not between [[+minValue]] and [[+maxValue]].';
	$_lang['form.error2_regex']										= 'This field did not conform to "[[+regex]]".';
	$_lang['form.error2_email']										= 'This field was not a valid e-mail address.';
	$_lang['form.error2_ip']										= 'This field was not a valid IP number.';
	$_lang['form.error2_url']										= 'This field was not a valid web address.';
	$_lang['form.error2_iban']										= 'This field was not a valid IBAN number.';
	$_lang['form.error2_phone']										= 'This field was not a valid phone number.';
	$_lang['form.error2_number']									= 'This field did not contain only numbers.';
	$_lang['form.error2_string']									= 'This field did not contain only letters.';
	$_lang['form.error2_date']										= 'This field was not a valid date.';
	$_lang['form.error2_mindate']									= 'This field was not later then [[+minDate]].';
	$_lang['form.error2_maxdate']									= 'This field was not earlier then [[+maxDate]].';
	$_lang['form.error2_betweendate']								= 'This field was not between [[+minDate]] and [[+maxDate]].';
	$_lang['form.error2_recaptcha']									= 'This field with reCAPTCHA was not correct.';

	$_lang['form.error_sendemail']									= 'The background e-mail could not be sent, please try again.';
	$_lang['form.error_sendrespondemail']							= 'The background e-mail could not be sent, please try again.';
	
?>