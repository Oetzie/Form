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

	$_lang['form'] 													= 'Formulieren';
	$_lang['form.desc']												= 'Bekijk alle ingevulde formulieren.';
		
	$_lang['area_form']												= 'Formulieren';
	
	$_lang['setting_form.recaptcha_site_key']						= 'Google reCAPTCHA API site sleutel';
	$_lang['setting_form.recaptcha_site_key_desc']					= 'De website sleutel voor de Google reCAPTCHA API, deze is te verkrijgen via https://www.google.com/recaptcha/admin.';
	$_lang['setting_form.recaptcha_secret_key']						= 'Google reCAPTCHA API geheime sleutel';
	$_lang['setting_form.recaptcha_secret_key_desc']				= 'De geheime sleutel voor de Google reCAPTCHA API, deze is te verkrijgen via https://www.google.com/recaptcha/admin.';
	$_lang['setting_form.recaptcha_url']							= 'Google reCAPTCHA API URL';
	$_lang['setting_form.recaptcha_url_desc']						= 'De URL voor de Google reCAPTCHA API.';
	
	$_lang['form_snippet_dateformat_desc']							= 'Het formaat van datums om de datums te controleren. Standaard is "%d-%m-%Y".';
	$_lang['form_snippet_placeholder_desc']							= 'De placeholder prefix voor de placeholders. Standaard is "form".';
	$_lang['form_snippet_submit_desc']								= 'De naam van de submit om het formulier te verzenden. Standaard is "submit".';
	$_lang['form_snippet_tplbulkerror_desc']						= 'De template voor een error in de samenvattingserror. Deze kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	$_lang['form_snippet_tplbulkwrapper_desc']						= 'De template de samenvattingserror wrapper. Deze kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	$_lang['form_snippet_tplerror_desc']							= 'De template voor een error. Deze kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	$_lang['form_snippet_type_desc']								= 'Het type van het formulier. Dit kan "set" of "get" zijn, standaard is "set".';
		
	$_lang['form.formsave']											= 'Formulieren';
	$_lang['form.formsaves']										= 'Formulieren';
	$_lang['form.formsave_desc']									= 'Bekijk hier alle formulieren die via de website zijn ingevuld. Status "compleet" betekend dat het formulier zonder fouten is ingevuld en afgehandeld, status "incompleet" betekend dat het formulier met fouten is ingevuld en daardoor niet afgehandeld is. Als een formulier te vaak de status "incompleet" heeft betekend dit dat het formulier onduidelijk is en dat de labels geoptimaliseerd dient te worden.';
	$_lang['form.formsave_show']									= 'Formulier bekijken';
	$_lang['form.formsave_remove']									= 'Formulier verwijderen';
	$_lang['form.formsave_remove_confirm']							= 'Weet je zeker dat je dit formulier wilt verwijderen?';
	$_lang['form.formsaves_remove_selected']						= 'Geselecteerde formulieren verwijderen';
	$_lang['form.formsaves_remove_selected_confirm']				= 'Weet je zeker dat je de geselecteerde formulieren wilt verwijderen?';
	$_lang['form.formsave_reset']									= 'Alle formulieren verwijderen';
	$_lang['form.formsave_reset_confirm']							= 'Weet je zeker dat je alle formulieren wilt verwijderen?';
	
	$_lang['form.label_resource']									= 'Pagina';
	$_lang['form.label_resource_desc']								= 'De pagina waar het formulier ingevuld is.';
	$_lang['form.label_name']										= 'Naam';
	$_lang['form.label_name_desc']									= 'De naam van het ingevulde formulier.';
	$_lang['form.label_ipnumber']									= 'IP nummer';
	$_lang['form.label_ipnumber_desc']								= 'IP nummer van de inzender.';
	$_lang['form.label_data']										= 'Data';
	$_lang['form.label_data_desc']									= 'De data van het ingevulde formulier.';
	$_lang['form.label_active']										= 'Status';
	$_lang['form.label_active_desc']								= 'De validatie status van het ingevulde formulier. Status "compleet" betekend dat het formulier succesvol ingevuld is, status "incompleet" betekend dat het formulier met fouten is ingevuld.';
	$_lang['form.label_date']										= 'Ingevuld op';
	$_lang['form.label_date_desc']									= 'De datum wanneer het formulier ingevuld is.';
		
	$_lang['form.filter_context']									= 'Filter op context...';
	$_lang['form.filter_status']									= 'Filter op status...';
	$_lang['form.form']												= 'Formulier';
	$_lang['form.valid']											= 'Compleet';
	$_lang['form.notvalid']											= 'Incompleet';
	$_lang['form.empty']											= 'Niet ingevuld';
	$_lang['form.error_bulk']										= 'Er zijn een aantal fouten in het formulier opgetreden, vul het formulier aan en probeer nog een keer.';
	
	$_lang['form.error_required']									= 'Dit veld is verplicht.';
	$_lang['form.error_blank']										= 'Dit veld mag alleen leeg zijn.';
	$_lang['form.error_equals']										= 'Dit veld moet gelijk zijn aan "[[+equals]]".';
	$_lang['form.error_equalsto']									= 'Dit veld moet gelijk zijn aan het veld "[[+equalsTo]]".';
	$_lang['form.error_contains']									= 'Dit veld moet "[[+contains]]" bevatten.';
	$_lang['form.error_minlength']									= 'Dit veld moet minimaal [[+minLength]] karakters hebben.';
	$_lang['form.error_maxlength']									= 'Dit veld mag maximaal [[+maxLength]] karakters hebben.';
	$_lang['form.error_betweenlength']								= 'Dit veld moet tussen [[+minLength]] en [[+maxLength]] karakters zijn.';
	$_lang['form.error_minvalue']									= 'Dit veld moet groter zijn dan [[+minValue]].';
	$_lang['form.error_maxvalue']									= 'Dit veld moet kleiner zijn dan [[+maxValue]].';
	$_lang['form.error_betweenvalue']								= 'Dit veld moet tussen [[+minValue]] en [[+maxValue]] zijn.';
	$_lang['form.error_regex']										= 'Dit veld moet voldoen aan "[[+regex]]".';
	$_lang['form.error_email']										= 'Dit veld is geen geldig e-mailadres.';
	$_lang['form.error_ip']											= 'Dit veld is geen geldig IP nummer.';
	$_lang['form.error_url']										= 'Dit veld is geen geldig webadres.';
	$_lang['form.error_iban']										= 'Dit veld is geen geldig IBAN nummer.';
	$_lang['form.error_phone']										= 'Dit veld is geen geldig telefoonnummer.';
	$_lang['form.error_number']										= 'Dit veld mag alleen getallen bevatten.';
	$_lang['form.error_string']										= 'Dit veld mag alleen letters bevatten.';
	$_lang['form.error_date']										= 'Dit veld is geen geldige datum.';
	$_lang['form.error_mindate']									= 'Dit veld moet later zijn dan [[+minDate]].';
	$_lang['form.error_maxdate']									= 'Dit veld moet eerder zijn dan [[+maxDate]].';
	$_lang['form.error_betweendate']								= 'Dit veld moet tussen [[+minDate]] en [[+maxDate]] zijn.';
	$_lang['form.error_recaptcha']									= 'Dit veld met reCAPTCHA is niet juist.';
	
	$_lang['form.error2_required']									= 'Dit veld is niet ingevuld.';
	$_lang['form.error2_blank']										= 'Dit veld was niet leeg.';
	$_lang['form.error2_equals']									= 'Dit veld was niet gelijk aan "[[+equals]]".';
	$_lang['form.error2_equalsto']									= 'Dit veld was niet gelijk aan het veld "[[+equalsTo]]".';
	$_lang['form.error2_contains']									= 'Dit veld bevatte geen "[[+contains]]".';
	$_lang['form.error2_minlength']									= 'Dit veld had geen minimaal [[+minLength]] karakters.';
	$_lang['form.error2_maxlength']									= 'Dit veld had geen maximaal [[+maxLength]] karakters.';
	$_lang['form.error2_betweenlength']								= 'Dit veld had niet tussen [[+minLength]] en [[+maxLength]] karakters.';
	$_lang['form.error2_minvalue']									= 'Dit veld was niet groter dan [[+minValue]].';
	$_lang['form.error2_maxvalue']									= 'Dit veld was niet kleiner dan [[+maxValue]].';
	$_lang['form.error2_betweenvalue']								= 'Dit veld was niet tussen [[+minValue]] en [[+maxValue]].';
	$_lang['form.error2_regex']										= 'Dit veld voldeed niet aan "[[+regex]]".';
	$_lang['form.error2_email']										= 'Dit veld was geen geldig e-mailadres.';
	$_lang['form.error2_ip']										= 'Dit veld was geen geldig IP nummer.';
	$_lang['form.error2_url']										= 'Dit veld was geen geldig webadres.';
	$_lang['form.error2_iban']										= 'Dit veld was geen geldig IBAN nummer.';
	$_lang['form.error2_phone']										= 'Dit veld was geen geldig telefoonnummer.';
	$_lang['form.error2_number']									= 'Dit veld bevatte niet alleen getallen.';
	$_lang['form.error2_string']									= 'Dit veld bevatte niet alleen letters.';
	$_lang['form.error2_date']										= 'Dit veld was geen geldige datum.';
	$_lang['form.error2_mindate']									= 'Dit veld was niet later dan [[+minDate]].';
	$_lang['form.error2_maxdate']									= 'Dit veld was niet eerder dan [[+maxDate]].';
	$_lang['form.error2_betweendate']								= 'Dit veld was niet tussen [[+minDate]] en [[+maxDate]].';
	$_lang['form.error2_recaptcha']									= 'Dit veld met reCAPTCHA was niet juist.';

	$_lang['form.error_sendemail']									= 'De achterliggende e-mail kon niet verstuurd worden, probeer het nog een keer.';
	$_lang['form.error_sendrespondemail']							= 'De achterliggende e-mail kon niet verstuurd worden, probeer het nog een keer.';
	
?>