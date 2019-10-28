var Form = function(config) {
    config = config || {};

    Form.superclass.constructor.call(this, config);
};

Ext.extend(Form, Ext.Component, {
    page    : {},
    window  : {},
    grid    : {},
    tree    : {},
    panel   : {},
    combo   : {},
    config  : {}
});

Ext.reg('form', Form);

Form = new Form();