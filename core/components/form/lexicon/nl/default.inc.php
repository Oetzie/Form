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

	$_lang['form'] 													= 'Formulieren';
	$_lang['form.desc']												= 'Bekijk alle ingevulde formulieren.';
		
	$_lang['area_form']												= 'Formulieren';
	
	$_lang['setting_form.encrypt']									= 'Formulieren versleutelen';
	$_lang['setting_form.encrypt_desc']								= 'Indien ja zullen de formulieren versleuteld opgeslagen worden. Standaard is "Ja".';
	$_lang['setting_form.encrypt_key']								= 'Formulieren sleutel';
	$_lang['setting_form.encrypt_key_desc']							= 'De sleutel om de formulieren te versleutelen indien de instelling "versleutelen" op "Ja" ingesteld is.';
	$_lang['setting_form.recaptcha_site_key']						= 'Google reCAPTCHA API site sleutel';
	$_lang['setting_form.recaptcha_site_key_desc']					= 'De website sleutel voor de Google reCAPTCHA API, deze is te verkrijgen via https://www.google.com/recaptcha/admin.';
	$_lang['setting_form.recaptcha_secret_key']						= 'Google reCAPTCHA API geheime sleutel';
	$_lang['setting_form.recaptcha_secret_key_desc']				= 'De geheime sleutel voor de Google reCAPTCHA API, deze is te verkrijgen via https://www.google.com/recaptcha/admin.';
	$_lang['setting_form.recaptcha_url']							= 'Google reCAPTCHA API URL';
	$_lang['setting_form.recaptcha_url_desc']						= 'De URL voor de Google reCAPTCHA API.';
	
	$_lang['form_snippet_action_desc']								= 'De actie van het formulier. Dit kan een ID van een pagina zijn, of "self". Standaard is "self".';
	$_lang['form_snippet_extensions_desc']							= 'De extensies die uitgevoerd moeten worden tijdens het afhandelen van het formulier. Meerdere extensies scheiden met een komma.';
	$_lang['form_snippet_handler_desc']								= 'De naam van de submit om het formulier te verzenden. Standaard is "submit".';
	$_lang['form_snippet_method_desc']								= 'De methode van het formulier. Dit kan "POST" of "GET" zijn, standaard is "POST".';
	$_lang['form_snippet_prefix_desc']								= 'De prefix voor de placeholders. Standaard is "form".';
	$_lang['form_snippet_tplbulkerror_desc']						= 'De template van een error in de algemene error. Deze kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	$_lang['form_snippet_tplbulkwrapper_desc']						= 'De template wrapper van de algemene error. Deze kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	$_lang['form_snippet_tplerror_desc']							= 'De template voor een error. Deze kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	$_lang['form_snippet_type_desc']								= 'Het type van het formulier. Dit kan "SET" of "GET" zijn, standaard is "SET".';
	$_lang['form_snippet_validation_desc']							= 'De validatie regels die uitgevoerd moeten worden tijdens het afhandelen van het formulier. Dit moet een geldig JSON formaat zijn.';	
	
	$_lang['form.form']												= 'Formulieren';
	$_lang['form.forms']											= 'Formulieren';
	$_lang['form.forms_desc']										= 'Bekijk hier alle formulieren die via de website zijn ingevuld. Status "<span class="green">compleet</span>" betekent dat het formulier zonder fouten is ingevuld en afgehandeld, status "<span class="red">incompleet</span>" betekent dat het formulier met fouten is ingevuld en daardoor niet afgehandeld is. Als een formulier te vaak de status "incompleet" heeft betekent dit dat het formulier onduidelijk is en dat de labels geoptimaliseerd dienen te worden.';
	$_lang['form.form_show']										= 'Formulier bekijken';
	$_lang['form.form_remove']										= 'Formulier verwijderen';
	$_lang['form.form_remove_confirm']								= 'Weet je zeker dat je dit formulier wilt verwijderen?';
	$_lang['form.forms_reset']										= 'Alle formulieren verwijderen';
	$_lang['form.forms_reset_confirm']								= 'Weet je zeker dat je alle formulieren wilt verwijderen?';
	
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
	$_lang['form.filter_names']										= 'Filter op naam...';
	$_lang['form.filter_status']									= 'Filter op status...';
	$_lang['form.auto_refresh_grid']								= 'Automatisch vernieuwen';
	$_lang['form.form']												= 'Formulier';
	$_lang['form.valid']											= 'Compleet';
	$_lang['form.notvalid']											= 'Incompleet';
	$_lang['form.empty']											= 'Niet ingevuld';
	
	$_lang['form.is_required']										= 'Dit veld is niet ingevuld.';
	$_lang['form.is_blank']											= 'Dit veld is niet leeg.';
	$_lang['form.is_equals']										= 'Dit veld is niet gelijk aan "[[+equals]]".';
	$_lang['form.is_equalsto']										= 'Dit veld was is gelijk aan het veld "[[+equalsTo]]".';
	$_lang['form.is_contains']										= 'Dit veld bevat niet "[[+contains]]".';
	$_lang['form.is_minlength']										= 'Dit veld is geen [[+minLength]] karakters lang.';
	$_lang['form.is_maxlength']										= 'Dit veld is langer dan [[+maxLength]] karakters.';
	$_lang['form.is_betweenlength']									= 'Dit veld is niet tussen [[+minLength]] en [[+maxLength]] karakters lang.';
	$_lang['form.is_minvalue']										= 'Dit veld is niet groter dan [[+minValue]].';
	$_lang['form.is_maxvalue']										= 'Dit veld is groter dan [[+maxValue]].';
	$_lang['form.is_betweenvalue']									= 'Dit veld is niet tussen [[+minValue]] en [[+maxValue]].';
	$_lang['form.is_regex']											= 'Dit veld voldoet niet aan "[[+regex]]".';
	$_lang['form.is_email']											= 'Dit veld is geen geldig e-mailadres.';
	$_lang['form.is_ip']											= 'Dit veld is geen geldig IP nummer.';
	$_lang['form.is_url']											= 'Dit veld is geen geldig webadres.';
	$_lang['form.is_iban']											= 'Dit veld is geen geldig IBAN nummer.';
	$_lang['form.is_phone']											= 'Dit veld is geen geldig telefoonnummer.';
	$_lang['form.is_number']										= 'Dit veld bevat niet alleen cijfers.';
	$_lang['form.is_alpha']											= 'Dit veld bevat niet alleen letters.';
	$_lang['form.is_alphanumeric']									= 'Dit veld bevat niet alleen letters en cijfers.';
	$_lang['form.is_date']											= 'Dit veld is geen geldige datum.';
	$_lang['form.is_mindate']										= 'Dit veld is niet later dan [[+minDate]].';
	$_lang['form.is_maxdate']										= 'Dit veld is niet eerder dan [[+maxDate]].';
	$_lang['form.is_betweendate']									= 'Dit veld is niet tussen [[+minDate]] en [[+maxDate]].';
	$_lang['form.is_extension']										= 'Dit bestand heeft geen geldige extensie.';
	$_lang['form.is_recaptcha']										= 'Vakje met "Ik ben geen robot" niet aangevinkt.';
	
?>