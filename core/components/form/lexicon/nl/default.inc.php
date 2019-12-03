<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$_lang['form']                                                  = 'Formulieren';
$_lang['form.desc']                                             = 'Bekijk alle ingevulde formulieren.';

$_lang['area_form']                                             = 'Formulieren';
$_lang['area_form_recaptcha']                                   = 'Formulieren: reCAPTCHA';
$_lang['area_form_save']                                        = 'Formulieren: Opslaan';

$_lang['setting_form.branding_url']                             = 'Branding';
$_lang['setting_form.branding_url_desc']                        = 'De URL waar de branding knop heen verwijst, indien leeg wordt de branding knop niet getoond.';
$_lang['setting_form.branding_url_help']                        = 'Branding (help)';
$_lang['setting_form.branding_url_help_desc']                   = 'De URL waar de branding help knop heen verwijst, indien leeg wordt de branding help knop niet getoond.';
$_lang['setting_form.encrypt']                                  = 'Formulieren versleutelen';
$_lang['setting_form.encrypt_desc']                             = 'Indien ja zullen de formulieren versleuteld opgeslagen worden. Standaard is "Ja".';
$_lang['setting_form.encrypt_key']                              = 'Formulieren sleutel';
$_lang['setting_form.encrypt_key_desc']                         = 'De sleutel om de formulieren te versleutelen indien de instelling "versleutelen" op "Ja" ingesteld is.';
$_lang['setting_form.clean_days']                               = 'Formulieren opruimen';
$_lang['setting_form.clean_days_desc']                          = 'Het aantal dagen dat een formulier opgeslagen mag blijven, daarna wordt hij automatisch verwijderd.';
$_lang['setting_form.recaptcha_site_key']                       = 'Google reCAPTCHA API site sleutel';
$_lang['setting_form.recaptcha_site_key_desc']                  = 'De website sleutel voor de Google reCAPTCHA API, deze is te verkrijgen via https://www.google.com/recaptcha/admin.';
$_lang['setting_form.recaptcha_secret_key']                     = 'Google reCAPTCHA API geheime sleutel';
$_lang['setting_form.recaptcha_secret_key_desc']                = 'De geheime sleutel voor de Google reCAPTCHA API, deze is te verkrijgen via https://www.google.com/recaptcha/admin.';
$_lang['setting_form.form_save_invalid']                        = 'Incomplete formulieren opslaan';
$_lang['setting_form.form_save_invalid_desc']                   = 'Indien "Ja" worden ook incomplete formulieren opgeslagen tijdens het valideren van het formulier. Standaard is "Ja".';

$_lang['form.form']                                             = 'Formulier';
$_lang['form.forms']                                            = 'Formulieren';
$_lang['form.forms_desc']                                       = 'Bekijk hier alle formulieren die via de website zijn ingevuld. Status "<span class="green">compleet</span>" betekent dat het formulier zonder fouten is ingevuld en afgehandeld, status "<span class="red">incompleet</span>" betekent dat het formulier met fouten is ingevuld en daardoor niet afgehandeld is. Als een formulier te vaak de status "incompleet" heeft betekent dit dat het formulier onduidelijk is en dat de labels geoptimaliseerd dienen te worden.';
$_lang['form.form_view']                                        = 'Formulier bekijken';
$_lang['form.form_view_ip']                                     = 'Formulieren met dit IP';
$_lang['form.form_remove']                                      = 'Formulier verwijderen';
$_lang['form.form_remove_confirm']                              = 'Weet je zeker dat je dit formulier wilt verwijderen?';
$_lang['form.forms_clean']                                      = 'Formulieren opruimen';
$_lang['form.forms_reset']                                      = 'Formulieren verwijderen';
$_lang['form.forms_reset_confirm']                              = 'Weet je zeker dat je alle formulieren wilt verwijderen?';

$_lang['form.label_form_resource']                              = 'Pagina';
$_lang['form.label_form_resource_desc']                         = 'De pagina waar het formulier ingevuld is.';
$_lang['form.label_form_name']                                  = 'Naam';
$_lang['form.label_form_name_desc']                             = 'De naam van het ingevulde formulier.';
$_lang['form.label_form_ipnumber']                              = 'IP nummer';
$_lang['form.label_form_ipnumber_desc']                         = 'IP nummer van de inzender.';
$_lang['form.label_form_data']                                  = 'Velden';
$_lang['form.label_form_data_desc']                             = 'De velden van het ingevulde formulier.';
$_lang['form.label_form_active']                                = 'Status';
$_lang['form.label_form_active_desc']                           = 'De validatie status van het ingevulde formulier. Status "<span class="green">compleet</span>" betekend dat het formulier succesvol ingevuld is, status "<span class="red">incompleet</span>" betekend dat het formulier met fouten is ingevuld.';
$_lang['form.label_form_date']                                  = 'Ingevuld op';
$_lang['form.label_form_date_desc']                             = 'De datum wanneer het formulier ingevuld is.';

$_lang['formit.label_clean_label']                              = 'Verwijder formulieren ouder dan';
$_lang['formit.label_clean_desc']                               = 'dagen';

$_lang['form.filter_context']                                   = 'Filter op context...';
$_lang['form.filter_form']                                      = 'Filter op formulier...';
$_lang['form.filter_status']                                    = 'Filter op status...';
$_lang['form.form']                                             = 'Formulier';
$_lang['form.valid']                                            = 'Compleet';
$_lang['form.notvalid']                                         = 'Incompleet';
$_lang['form.empty']                                            = 'Niet ingevuld';
$_lang['form.forms_clean_desc']                                 = 'De <a href="https://autoriteitpersoonsgegevens.nl/nl/onderwerpen/avg-europese-privacywetgeving/algemene-informatie-avg" target="_blank">Algemene verordening gegevensbescherming (AVG)</a> stelt verplicht dat persoonlijke data, dat niet langer noodzakelijk is om te bewaren, wordt verwijderd. Deze functie maakt het mogelijk om formulieren, ouder dan het opgegeven aantal dagen, te verwijderen. Deze actie kan niet worden teruggedraaid!';
$_lang['form.forms_clean_executing']                            = 'Bezig met opruimen van formulieren';
$_lang['form.forms_clean_success']                              = '[[+amount]] formulier(en) verwijderd.';
