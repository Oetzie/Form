<?php

	/**
	 * Form
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
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
	$_lang['form.desc']												= 'Show all submitted forms.';
		
	$_lang['area_form']												= 'Forms';
	
	$_lang['setting_form.encrypt']									= 'Encrypt forms';
	$_lang['setting_form.encrypt_desc']								= 'When yes al the forms will be saved encrypted. Default is "Yes".';
	$_lang['setting_form.encrypt_key']								= 'Forms key';
	$_lang['setting_form.encrypt_key_desc']							= 'The key to encrypt the forms when the setting "encrypt" is setup to "Yes".';
	$_lang['setting_form.recaptcha_site_key']						= 'Google reCAPTCHA API site key';
	$_lang['setting_form.recaptcha_site_key_desc']					= 'The site key of the Google reCAPTCHA API, you can get this at via https://www.google.com/recaptcha/admin.';
	$_lang['setting_form.recaptcha_secret_key']						= 'Google reCAPTCHA API secret key';
	$_lang['setting_form.recaptcha_secret_key_desc']				= 'The secret key of the Google reCAPTCHA API, you can get this at https://www.google.com/recaptcha/admin.';
	$_lang['setting_form.recaptcha_url']							= 'Google reCAPTCHA API URL';
	$_lang['setting_form.recaptcha_url_desc']						= 'The URL of the reCAPTCHA API.';
	
	$_lang['form_snippet_action_desc']								= 'The action of the form. This can be an ID of a page or "self". Default is "self".';
	$_lang['form_snippet_extensions_desc']							= 'The extensions that must be processed when processing the form. Separate extensions with a comma.';
	$_lang['form_snippet_handler_desc']								= 'The name of the submit to send the form. Default is "submit".';
	$_lang['form_snippet_method_desc']								= 'The method of the form. This can be "POST" or "GET", default is "POST".';
	$_lang['form_snippet_prefix_desc']								= 'The prefix of the placeholders. Default is "form".';
	$_lang['form_snippet_tplbulkerror_desc']						= 'The template of an error in the bulk error. This can start with @INLINE:, @CHUNK: or chunk name.';
	$_lang['form_snippet_tplbulkwrapper_desc']						= 'The template wrapper of the bulk error This can start with @INLINE:, @CHUNK: or chunk name.';
	$_lang['form_snippet_tplerror_desc']							= 'The template of an error. This can start with @INLINE:, @CHUNK: or chunk name';
	$_lang['form_snippet_type_desc']								= 'The type of the form. This can be "SET" or "GET", default is "SET".';
	$_lang['form_snippet_validation_desc']							= 'The validation rules that must be checked when processing the form. This must be a valid JSON.';	
	
	$_lang['form.form']												= 'Form';
	$_lang['form.forms']											= 'Forms';
	$_lang['form.forms_desc']										= 'Show all the forms that are submitted at the website. Status "<span class="green">complete</span>" means that the form is submitted and processed successful, status "<span class="red">incomplete</span>" means that the form is submitted with errors and not is processed.';
	$_lang['form.form_show']										= 'Show form';
	$_lang['form.form_remove']										= 'Delete form';
	$_lang['form.form_remove_confirm']								= 'Are you sure you want to delete this form?';
	$_lang['form.forms_reset']										= 'Delete all forms';
	$_lang['form.forms_reset_confirm']								= 'Are you sure you want to delete all the forms?';
	
	$_lang['form.label_resource']									= 'Page';
	$_lang['form.label_resource_desc']								= 'The page of the submitted form.';
	$_lang['form.label_name']										= 'Name';
	$_lang['form.label_name_desc']									= 'The name of the submitted form.';
	$_lang['form.label_ipnumber']									= 'IP number';
	$_lang['form.label_ipnumber_desc']								= 'IP number of the contributor.';
	$_lang['form.label_data']										= 'Data';
	$_lang['form.label_data_desc']									= 'The data of the submitted form.';
	$_lang['form.label_active']										= 'Status';
	$_lang['form.label_active_desc']								= 'The validation status of the submitted form. Status "complete" means that the form is submitted successful, status "incomplete" means that the form is submitted with errors.';
	$_lang['form.label_date']										= 'Submitted at';
	$_lang['form.label_date_desc']									= 'The date when the form is submitted.';
	
	$_lang['form.filter_context']									= 'Filter on context...';
	$_lang['form.filter_names']										= 'Filter on name...';
	$_lang['form.filter_status']									= 'Filter on status...';
	$_lang['form.auto_refresh_grid']								= 'Auto refresh';
	$_lang['form.form']												= 'Form';
	$_lang['form.valid']											= 'Complete';
	$_lang['form.notvalid']											= 'Incomplete';
	$_lang['form.empty']											= 'Not filled';
	
	$_lang['form.is_required']										= 'This field is not filled.';
	$_lang['form.is_blank']											= 'This field is not empty.';
	$_lang['form.is_equals']										= 'This field is not equal to "[[+equals]]".';
	$_lang['form.is_equalsto']										= 'This field is not equal to the field "[[+equalsTo]]".';
	$_lang['form.is_contains']										= 'This field does not contain "[[+contains]]".';
	$_lang['form.is_minlength']										= 'This field is not [[+minLength]] characters long.';
	$_lang['form.is_maxlength']										= 'This field is longer then [[+maxLength]] characters.';
	$_lang['form.is_betweenlength']									= 'This field is not between [[+minLength]] and [[+maxLength]] characters long.';
	$_lang['form.is_minvalue']										= 'This field is not greater then [[+minValue]].';
	$_lang['form.is_maxvalue']										= 'This field is greater then [[+maxValue]].';
	$_lang['form.is_betweenvalue']									= 'This field is not between [[+minValue]] and [[+maxValue]].';
	$_lang['form.is_regex']											= 'This field does not contain "[[+regex]]".';
	$_lang['form.is_email']											= 'This field is not a valid e-mail address.';
	$_lang['form.is_ip']											= 'This field is not a valid IP number.';
	$_lang['form.is_url']											= 'This field is not a valid URL.';
	$_lang['form.is_iban']											= 'This field is not a valid IBAN number.';
	$_lang['form.is_phone']											= 'This field is not a valid phone number.';
	$_lang['form.is_number']										= 'This field does not contain only numeric characters.';
	$_lang['form.is_alpha']											= 'This field does not contain only alphabetic characters.';
	$_lang['form.is_alphanumeric']									= 'This field does not contain only numeric and alphabetic characters.';
	$_lang['form.is_date']											= 'This field is not a valid date.';
	$_lang['form.is_mindate']										= 'This field is not later then [[+minDate]].';
	$_lang['form.is_maxdate']										= 'This field is not earlier then [[+maxDate]].';
	$_lang['form.is_betweendate']									= 'This field is not between [[+minDate]] and [[+maxDate]].';
	$_lang['form.is_extension']										= 'This file has not a valid extension.';
	$_lang['form.is_recaptcha']										= 'Field with "I am not a robot" not marked.';
	
?>