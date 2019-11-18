# MODX Form
![Form version](https://img.shields.io/badge/version-1.3.0-blue.svg) ![MODX Extra by Oetzie.nl](https://img.shields.io/badge/checked%20by-oetzie-blue.svg) ![MODX version requirements](https://img.shields.io/badge/modx%20version%20requirement-2.4%2B-brightgreen.svg)

Form for MODX
==========================
Current version: 1.3.0-pl
Author: Oene Tjeerd de Bruin <modx@oetzie.nl>

Form is a snippet to handle forms in MODx. It will validate the form and triggers actions like sending an email if the validation succeed. It does not generate the form, but it can repopulate it if it fails validation

## Snippet parameters

| Parameter                  | Description                                                                  |
|----------------------------|------------------------------------------------------------------------------|
| action | The action of the form. This can be an URL, ID of a resource, or `resource`. If set to `resource` the action will be set to the current resource. If set to an ID of a resource or `resource` the current URL parameters will be merged in this action URL. |
| method | The method of the form. This can be `POST` or `GET`. This parameter will be available as the `{$method}` placeholder. Default is `POST`. |
| submit | The submit action of the form. Default is `submit`. |
| retriever | |
| prefix | The prefix of the placeholders, this prefix will used if you not work with the `tpl` parameter. Default is `form`.|
| validator | The validation rules of the form. This must be a valid array. |
| validatorMessages | The custom validation messages for the validation rules. This must be a valid array. If empty the default validation messages (in the `validation.inc.php` lexicon file) will be used. |
| plugins | The plugins of the form. This must be a valid array. |
| success | The success action of the form, if the validation is succeed the form will be redirected to this action. This can be an URL, ID of a resource, or `resource`. If set to `resource` the success action will be set to the current resource. If set to an ID of a resource or `resource` the current URL parameters will be merged in this action URL. If empty the form will not be redirected after a succeed validation. |
| failure | The failed action of the form, if the validation is failed the form will be redirected to this action. This can be an URL, ID of a resource, or `resource`. If set to `resource` the failed action will be set to the current resource. If set to an ID of a resource or `resource` the current URL parameters will be merged in this action URL. If empty the form will not be redirected after a failed validation. |
| tpl | The template of the form. This can be a chunk name or prefixed with `@FILE` or `@INLINE`. If empty all placeholders will set with the prefix to be used after the snippet call. |
| tplSuccess | The template of the form if the validation succeed. This will replace the form template if not empty. |
| tplFailure | The template of the form if the validation failed. This will replace the form template if not empty. |
| tplError | The template of an error. Default is `@INLINE <p class="help-block">{$error}</p>` |
| tplErrorMessage | The template of the bulk error. Default is `@INLINE <div class="form-group form-group--error"><p class="help-block">{$error}</p></div>`. The bulk error is available as the `{$error_message}` placeholder. |
| usePdoTools | If `true` pdoTool will be used for the tpl's (Fenom is also available). `@FILE` and `@INLINE` are also available without PdoTools. Default is `false`. |
| usePdoElementsPath | If `true` pdoTools will use the `pdotools_elements_path` setting to locate the `@FILE` tpl's, otherwise the `core/components/form/` will be used as directory. Default is `false`. |

## Build-in validators

| Validation rule            | Description                                                                  |
|----------------------------|------------------------------------------------------------------------------|
| required | The field needs to contain a value. |
| requiredWhen | `Not yet supported.` |
| blank | The field needs to be empty. |
| equals | The field needs to be equal to a specified value. |
| equalsTo | The field needs to be equal to a specified field. |
| minLength | The minimum amount of characters (or checkboxes). |
| maxLength | The maximum amount of characters (or checkboxes). |
| betweenLength | The minimum and maximum amount of characters (or checkboxes). |
| minValue | The minimum value (or checkboxes). |
| maxValue | The maximum value (or checkboxes). |
| betweenValue | The minimum and maximum value (or checkboxes). |
| regex | A custom regex to validate the field. |
| email | The field needs to be a valid email adress. |
| domain | The field needs to be a valid domain (without a path/segments). |
| url | The field needs to be a valid URL. |
| ip | The field needs to be a valid IP. |
| iban | The field needs to be a valid IBAN. |
| number | The field can only contain 0-9 characters (also , and . are allowed). |
| alpha | The field can only contain a-z characters. |
| alphaNumeric | Combination of number and alpha characters. |
| date | The field needs to be a valid date. |
| minDate | The field needs to be a valid date and later then a specified date. |
| maxDate | The field needs to be a valid date and earlier then a specified date. |
| betweenDate | The field needs to a valid date and between 2 specified dates. |
| file | The field needs to have a valid upload (UPLOAD_ERR_OK). |
| fileExtension | The valid need to have a valid upload with a specified extension. |
| fileExtension | The valid need to have a valid upload with a specified file size. |
| age | The field needs to have a valid date and calculates the age that needs to be older then a specified age. |

**Example validation parameter:**

```
{'!Form' | snippet : [
    'validator'             => [
        // Name field required.
        'name'                  => 'required',
        
        // Phone field required and phone validator.
        'phone'                 => ['phone', 'required'],
        
        // Email field required and email validator.
        'email'                 => ['email', 'required'],
        
        // Content field required.
        'content'               => 'required',
        
        // Age field required and age validator with the minimum age of 18.
        'age'                   => [
            'age'                   => 18,
            'required'              => true
        ]
    ],
    'validatorMessages'     => [
        'required'              => 'This field is required'
    ]
]}
```

## Plugins

A form can handle plugin/events, in FormIt know as hooks.

### Build-in plugins

| Plugin                     | Description                                                                  |
|----------------------------|------------------------------------------------------------------------------|
| recaptcha | Validated the form data with Google Recaptcha (V2 and V3 supported). |
| save | Saves the form data encrypted into the database (custom manager component). |
| email | Sent the form data by email to specified emails (multiple emails supported, email to administrator, email to client etc). |

**Example ReCaptcha plugin:**


```
{'!Form' | snippet : [
    'plugins'               => [
        'recaptcha'             => 'v3' // Can be v2 or v3.
    ]
]}
```

**Example email plugin:**
```
{'!Form' | snippet : [
    'plugins'               => [
        'email'                 => [
            'subject'               => 'Title of the email.',
            'emailTo'               => 'The e-mailadress to sent the email to.',
            'emailToField'          => 'The name of the field to get the e-mailadress to sent the email to.',
            'emailFrom'             => 'The e-mailadress to sent from.',
            'tpl'                   => 'The template of the email.'
        ]
    ]
]}
```

### Custom plugins

Each plugin will be triggered multiple times:

* `onBeforePost`, gets triggered before the form renders.
* `onValidatePost`, gets triggered during the form validation (after the validation rules).
* `onAfterPost`, get triggered after a succeed form validation.

**Example custom plugin:**

The following code is a simple example how to use a custom snippet as plugin. The key of the in the `plugins` value is the name of the custom snippet thats needs to be triggerd.
```
{'!Form' | snippet : [
    'plugins'               => [
        'mailchimp'             => [
            'list_id'               => 'The id of the MailChimp list',
            'double_optin;          => true
        ]
    ]
]}
```

```
<?php

    /**
     * Custom snippet with the name: mailchimp.
     *
     * Available parameters:
     * @param $event, the name of the event (onBeforePost, onValidatePost or onAfterPost).
     * @param $properties, the properties of the plugin. In this example it contains 'list' and 'double_optin'.
     * @param $form, the form object. $form->getCollection() contains the values object, $form->getCollection() contains the validator object.
     */

    // Gets triggered before the form renders.
    if ($event === 'onBeforePost') {
        $form->getCollection()->setValue('email', 'modx@oetzie.nl');
        
        return true;
    }
    
    // Gets triggered during the form validation (after the validation rules).
    if ($event === 'onValidatePost') {
        $email = $form->getCollection()->getValue('email');
        
        if (empty($email)) {
            $form->getValidator()->setError('email', 'E-mailaddress is required.');
            
            return false;
        }
        
        return true;
    }
    
    // Get triggered after a succeed form validation.
    // CURL to the MailChimp API, $properties['list_id'], $properties['double_optin'].
    if ($event === 'onAfterPost') {
        $state = curl...
        
        if (!$state) {
            $form->getValidator()->setError('email', 'An error has occurred during the MailChimp api call.');
            
            return false;
        }
        
        return true;
    }
    
    return true;
    
?>
```

## Form template (chunk)

All form values are stored in an array that is available as the `values` placeholder. You can access a value by the name of the field like `{$_pls['values']['FIELD_NAME']}` or `[[+values.FIELD_NAME]]`.

All the validation errors are stored in an array that is avaible as the `errors` placeholder. You can access an error by the name of the field like `{$_pls['values']['FIELD_NAME']}` or `[[+values.FIELD_NAME]]`. The error returns an array
with two keys:

* Error, the first occurred error of the field.
* Errors, all the occurred errors of the field. 

To display the first occurred error of the field `{$_pls['values']['FIELD_NAME']['error']}` or `[[+values.FIELD_NAME.error]]`.

**Available placeholders:**

| Placeholder                | Description                                                                  |
|----------------------------|------------------------------------------------------------------------------|
| action | The action of the form, this is an URL. |
| method | The method of the form, this can be `POST` or `GET`. |
| active | The state of the form, if the form is submitted the state is `true` otherwise `false`. |
| error_message | A global error message if the form validation is failed. |
| errors | All the error of the form if the form validation is failed. |
| values | All the values of the form. |
| submit | The submit value of the form. |
| plugins | The output of the plugins (for example rendering the Recaptcha HTML). |

**Example chunk:**

```
<form novalidate action="{$action}" method="{$method}" class="form {$active ? 'form-active' : ''}">
    {$error_message}
    
    <!-- Name field -->
    <div class="form-group required {$_pls['errors']['name']['error'] ? 'form-group--error' : ''}">
        <label for="name">{'base.contact_form.name' | lexicon} *</label>
        <div class="form-control-wrapper">
            <input type="text" name="name" id="name" class="form-control" value="{$_pls['values']['name']}" /> {$_pls['errors']['name']['error']}
        </div>
    </div>
    
    <!-- Phone field -->
    <div class="form-group required {$_pls['errors']['phone']['error'] ? 'form-group--error' : ''}">
        <label for="phone">{'base.contact_form.phone' | lexicon} *</label>
        <div class="form-control-wrapper">
            <input type="tel" name="phone" id="phone" class="form-control" value="{$_pls['values']['phone']}" /> {$_pls['errors']['phone']['error']}
        </div>
    </div>
    
    <!-- Email field -->
    <div class="form-group required {$_pls['errors']['email']['error'] ? 'form-group--error' : ''}">
        <label for="email">{'base.contact_form.email' | lexicon} *</label>
        <div class="form-element-wrapper">
            <input type="email" name="email" id="email" class="form-control" value="{$_pls['values']['email']}" /> {$_pls['errors']['email']['error']}
        </div>
    </div>
    
    <!-- Content field -->
    <div class="form-group required {$_pls['errors']['content']['error'] ? 'form-group--error' : ''}">
        <label for="content">{'base.contact_form.content' | lexicon} *</label>
        <div class="form-control-wrapper">
            <textarea name="content" id="content" class="form-control form-control--textarea">{$_pls['values']['content']}</textarea> {$_pls['errors']['content']['error']}
        </div>
    </div>
    
    <!-- Recaptcha plugin output -->
    <div class="form-group {$_pls['errors']['recaptcha']['error'] ? 'form-group--error' : ''}">
        <div class="form-control-wrapper">
            {$_pls['plugins']['recaptcha']['output']} {$_pls['errors']['recaptcha']['error']}
        </div>
    </div>
    
    <!-- Submit button -->
    <div class="form-group">
        <div class="form-control-wrapper">
            <button type="submit" class="btn" name="{$submit}" title="{'base.contact_form.submit' | lexicon}">{'base.contact_form.submit' | lexicon}</button>
        </div>
    </div>
</form>
```
