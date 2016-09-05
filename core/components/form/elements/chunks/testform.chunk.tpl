[[!Form?
    &redirect=`14`
    
    &extensions=`save,recaptcha,email`
    &validate=`{"sex": ["required"], "name": ["required"], "phone": ["phone", "required"], "email":["email", "required"], "content": ["required"]}`

    &saveName=`Test formulier`
    &saveElements=`{"sex": {"type": "select", "label": "Geslacht", "values": {"male": "Man", "female": "Vrouw"}}, "name": "Naam", "email": "E-mail adres", "phone": "Telefoonnummer", "type" : {"type": "checkbox", "label": "Type", "values": {"phone": "Telefoon", "email": "E-mail", "fax": "Fax"}}, "content": {"type": "textarea", "label": "Reactie en/of opmerking"}}`
    
    &emailTo=`{"info@oetzie.nl": "Oetzie.nl"}`
    &emailFrom=`{"form.email": "form.name"}`
    &emailSubject=`Test formulier`
    &emailTpl=`testFormEmailTpl`
    
    &tpl=`testFormTpl`
]]