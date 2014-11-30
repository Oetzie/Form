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

	$_lang['form'] 											= 'Formulieren';
	$_lang['form.desc'] 									= 'Bekijk ingevulde formulieren.';
		
	$_lang['area_form']										= 'Form';
	
	$_lang['form_snippet_placeholderkey_desc']				= 'De voorvoegsel voor alle placeholders die geset worden, standaard is "form".';
	$_lang['form_snippet_tplerror_desc']					= 'De template van een error. Dit kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	$_lang['form_snippet_tplbulkerror_desc']				= 'De template van error in het bulkoverzicht. Dit kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	$_lang['form_snippet_tplbulkwrapper_desc']				= 'De template van de wrapper van het bulkoverzicht van de errors. Dit kan beginnen met @INLINE:, @CHUNK: of chunk naam.';
	
	$_lang['form.formsave']									= 'Formulieren';
	$_lang['form.formsaves']								= 'Formulieren';
	$_lang['form.formsave_desc']							= 'Bekijk hier alle formulieren die via de website zijn ingevuld. Status "compleet" betekend dat het formulier succesvol is ingevuld (zonder fouten), status "incompleet" betekend dat het formulier met fouten is ingevuld. Als een formulier vaak de status op "incompleet" staat betekend dit dat het formulier/labels niet duidelijk zijn en eigenlijk geoptimaliseerd dient te worden.';
	$_lang['form.formsave_show']							= 'Formulier bekijken';
	$_lang['form.formsave_remove']							= 'Formulier verwijderen';
	$_lang['form.formsave_remove_confirm']					= 'Weet je zeker dat je dit formulier wilt verwijderen?';
	$_lang['form.formsave_remove_selected']					= 'Geselecteerde formulieren verwijderen';
	$_lang['form.formsave_remove_selected_confirm']			= 'Weet je zeker dat je de geselecteerde formulieren wilt verwijderen?';
	$_lang['form.formsave_reset']							= 'Alle formulieren verwijderen';
	$_lang['form.formsave_reset_confirm']					= 'Weet je zeker dat je alle formulieren wilt verwijderen?';
	
	$_lang['form.label_resource']							= 'Document';
	$_lang['form.label_resource_desc']						= 'Het document waar het formulier ingevuld is.';
	$_lang['form.label_name']								= 'Naam';
	$_lang['form.label_name_desc']							= 'De naam van het ingevulde formulier.';
	$_lang['form.label_ipnumber']							= 'IP nummer';
	$_lang['form.label_ipnumber_desc']						= 'IP nummer van de inzender.';
	$_lang['form.label_data']								= 'Data';
	$_lang['form.label_data_desc']							= 'De data van het ingevulde formulier.';
	$_lang['form.label_active']								= 'Status';
	$_lang['form.label_active_desc']						= 'De validatie status van het ingevulde formulier.';
	$_lang['form.label_date']								= 'Ingevuld op';
	$_lang['form.label_date_desc']							= 'De datum wanneer het formulier ingevuld is.';
	
	$_lang['form.filter_context']							= 'Filter op context...';
	$_lang['form.filter_status']							= 'Filter op status...';
	$_lang['form.form']										= 'Formulier';
	$_lang['form.valid']									= 'Compleet';
	$_lang['form.notvalid']									= 'Incompleet';
	
	$_lang['form.error_validate']							= 'Er zijn een aantal fouten in het formulier opgetreden, vul het formulier aan en probeer nog een keer.';
	$_lang['form.error_required']							= 'Dit veld is verplicht.';
	$_lang['form.error_blank']								= 'Dit veld mag alleen leeg zijn.';
	$_lang['form.error_equals']								= 'Dit veld moet gelijk zijn aan "[[+equals]]".';
	$_lang['form.error_equalselement']						= 'Dit veld moet gelijk zijn aan het veld "[[+equalsElement]]".';
	$_lang['form.error_contains']							= 'Dit veld moet "[[+contains]]" bevatten.';
	$_lang['form.error_minlength']							= 'Dit veld moet minimaal [[+minLength]] karakters hebben.';
	$_lang['form.error_maxlength']							= 'Dit veld mag minimaal [[+maxLength]] karakters hebben.';
	$_lang['form.error_betweenlength']						= 'Dit veld moet tussen [[+minLength]] en [[+maxLength]] karakters zijn.';
	$_lang['form.error_minvalue']							= 'Dit veld moet groter zijn dan [[+minValue]].';
	$_lang['form.error_maxvalue']							= 'Dit veld moet kleiner zijn dan [[+maxValue]].';
	$_lang['form.error_betweenvalue']						= 'Dit veld moet tussen [[+minValue]] en [[+maxValue]] zijn.';
	$_lang['form.error_regex']								= 'Dit veld moet voldoen aan "[[+regex]]".';
	$_lang['form.error_email']								= 'Dit veld is geen geldig e-mailadres.';
	$_lang['form.error_ip']									= 'Dit veld is geen geldig IP nummer.';
	$_lang['form.error_number']								= 'Dit veld mag alleen getallen bevatten.';
	$_lang['form.error_string']								= 'Dit veld mag alleen letters bevatten.';
	$_lang['form.error_date']								= 'Dit veld is geen geldige datum.';
	$_lang['form.error_mindate']							= 'Dit veld moet later zijn dan [[+minDate]].';
	$_lang['form.error_maxdate']							= 'Dit veld moet eerder zijn dan [[+maxDate]].';
	$_lang['form.error_betweendate']						= 'Dit veld moet tussen [[+minDate]] en [[+maxDate]] zijn.';
	
	$_lang['form.error_extension_email']					= 'E-mail kon niet verstuurd worden naar "[[+emails]]", probeer het nog een keer.';
	$_lang['form.error_extension_newsletter_subscribe']				= 'Er is een fout opgetreden tijdens het inschrijven voor de nieuwsbrief, probeer het nog een keer.';
	$_lang['form.error_extension_newsletter_subscribe_confirm']		= 'Er is een fout opgetreden tijdens het bevestigen van uw inschrijving voor de nieuwsbrief, probeer het nog een keer.';
	$_lang['form.error_extension_newsletter_unsubscribe']			= 'Er is een fout opgetreden tijdens het uitschrijven voor de nieuwsbrief, probeer het nog een keer.';
	$_lang['form.error_extension_newsletter_unsubscribe_confirm']	= 'Er is een fout opgetreden tijdens het bevestigen van uw uitschrijving voor de nieuwsbrief, probeer het nog een keer.';
	
?>