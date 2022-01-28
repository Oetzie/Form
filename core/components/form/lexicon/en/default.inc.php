<?php

/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$_lang['form']                                                  = 'Forms';
$_lang['form.desc']                                             = 'Show all submitted forms.';

$_lang['area_form']                                             = 'Forms';
$_lang['area_form_recaptcha']                                   = 'Forms: reCAPTCHA';
$_lang['area_form_save']                                        = 'Forms: Save';

$_lang['setting_form.branding_url']                             = 'Branding';
$_lang['setting_form.branding_url_desc']                        = 'The URL of the branding button, if the URL is empty the branding button won\'t be shown.';
$_lang['setting_form.branding_url_help']                        = 'Branding (help)';
$_lang['setting_form.branding_url_help_desc']                   = 'The URL of the branding help button, if the URL is empty the branding help button won\'t be shown.';
$_lang['setting_form.use_pdotools']                             = 'Use pdoTools';
$_lang['setting_form.use_pdotools_desc']                        = 'If true and pdoTools is installed, the chunks will be parsed by pdoTools. Default is "No".';
$_lang['setting_form.encrypt']                                  = 'Encrypt forms';
$_lang['setting_form.encrypt_desc']                             = 'When "Yes" al the forms will be saved encrypted. Default is "Yes".';
$_lang['setting_form.encrypt_key']                              = 'Forms encrypt key';
$_lang['setting_form.encrypt_key_desc']                         = 'The encrypt key to encrypt the forms when the setting "encrypt" is setup to "Yes".';
$_lang['setting_form.encrypt_method']                           = 'Forms encrypt method';
$_lang['setting_form.encrypt_method_desc']                      = 'The encrypt method to encrypte the forms when the setting "encrypt" is setup to "Yes". This can be "mcrypt" or "openssl", default is "openssl".';
$_lang['setting_form.clean_days']                               = 'Clean forms';
$_lang['setting_form.clean_days_desc']                          = 'The amount of thats that a form will be saved, after this the form will be deleted.';
$_lang['setting_form.recaptcha_site_key']                       = 'Google reCAPTCHA API site key';
$_lang['setting_form.recaptcha_site_key_desc']                  = 'The site key of the Google reCAPTCHA API, you can get this at via https://www.google.com/recaptcha/admin.';
$_lang['setting_form.recaptcha_secret_key']                     = 'Google reCAPTCHA API secret key';
$_lang['setting_form.recaptcha_secret_key_desc']                = 'The secret key of the Google reCAPTCHA API, you can get this at https://www.google.com/recaptcha/admin.';
$_lang['setting_form.form_save_invalid']                        = 'Save incomplete forms';
$_lang['setting_form.form_save_invalid_desc']                   = 'When "Yes" all the incomplete forms will be saved during validating the form. Default is "Yes".';
$_lang['setting_form.media_source']                             = 'Forms media source';
$_lang['setting_form.media_source_desc']                        = 'The media source that will be used for the email attachments and uploads.';

$_lang['form.form']                                             = 'Form';
$_lang['form.forms']                                            = 'Forms';
$_lang['form.forms_desc']                                       = 'Show all the forms that are submitted at the website. Status "<span class="green">complete</span>" means that the form is submitted and processed successful, status "<span class="red">incomplete</span>" means that the form is submitted with errors and not is processed.';
$_lang['form.form_view']                                        = 'Show form';
$_lang['form.form_view_ip']                                     = 'Forms with this IP';
$_lang['form.form_remove']                                      = 'Delete form';
$_lang['form.form_remove_confirm']                              = 'Are you sure you want to delete this form?';
$_lang['form.forms_clean']                                      = 'Clean forms';
$_lang['form.forms_reset']                                      = 'Remove forms';
$_lang['form.forms_reset_confirm']                              = 'Weet je zeker dat je alle formulieren wilt verwijderen?';

$_lang['form.label_form_resource']                              = 'Page';
$_lang['form.label_form_resource_desc']                         = 'The page of the submitted form.';
$_lang['form.label_form_name']                                  = 'Name';
$_lang['form.label_form_name_desc']                             = 'The name of the submitted form.';
$_lang['form.label_form_ipnumber']                              = 'IP number';
$_lang['form.label_form_ipnumber_desc']                         = 'IP number of the contributor.';
$_lang['form.label_form_data']                                  = 'Fields';
$_lang['form.label_form_data_desc']                             = 'The fields of the submitted form.';
$_lang['form.label_form_active']                                = 'Status';
$_lang['form.label_form_active_desc']                           = 'The validation status of the submitted form. Status "complete" means that the form is submitted successful, status "incomplete" means that the form is submitted with errors.';
$_lang['form.label_form_date']                                  = 'Submitted at';
$_lang['form.label_form_date_desc']                             = 'The date when the form is submitted.';

$_lang['formit.label_clean_label']                              = 'Delete forms older than';
$_lang['formit.label_clean_desc']                               = 'days';

$_lang['form.filter_context']                                   = 'Filter on context...';
$_lang['form.filter_form']                                      = 'Filter on formulier...';
$_lang['form.filter_status']                                    = 'Filter on status...';
$_lang['form.form']                                             = 'Form';
$_lang['form.valid']                                            = 'Complete';
$_lang['form.notvalid']                                         = 'Incomplete';
$_lang['form.empty']                                            = 'Not filled';
$_lang['form.forms_clean_desc']                                 = 'The European <a href="https://ec.europa.eu/commission/priorities/justice-and-fundamental-rights/data-protection/2018-reform-eu-data-protection-rules_en" target="_blank">General Data Protection Regulation (GDPR)</a> requires that personal data, which is no longer necessary to possess, is removed. This tool makes it possible to remove saved forms with an age older than the given days. This action can not be undone!';
$_lang['form.forms_clean_executing']                            = 'Cleaning up forms';
$_lang['form.forms_clean_success']                              = '[[+amount]] form(s) removed.';
$_lang['form.form_data_encrypt_error']                          = 'The data of the forms could not be formatted, this may be due to the encryption method.';
$_lang['form.form_meta']                                        = 'Send by [[+ip]] on page <a href="[[+url]]" title="[[+title]]">[[+title]]</a>.';
