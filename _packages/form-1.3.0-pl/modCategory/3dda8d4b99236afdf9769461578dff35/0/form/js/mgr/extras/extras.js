Form.combo.Status = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        store       : new Ext.data.ArrayStore({
            mode        : 'local',
            fields      : ['type', 'label'],
            data        : [
                ['1', _('form.valid')],
                ['0', _('form.notvalid')]
            ]
        }),
        remoteSort  : ['label', 'asc'],
        hiddenName  : 'status',
        valueField  : 'type',
        displayField : 'label',
        mode        : 'local'
    });

    Form.combo.Status.superclass.constructor.call(this, config);
};

Ext.extend(Form.combo.Status, MODx.combo.ComboBox);

Ext.reg('form-combo-status', Form.combo.Status);

Form.combo.Names = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : Form.config.connector_url,
        baseParams  : {
            action      : 'mgr/forms/getnodes',
            context     : MODx.request.context || MODx.config.default_context,
            combo       : true
        },
        fields      : ['id', 'name', 'context_key', 'context_name'],
        hiddenName  : 'form',
        pageSize    : 15,
        valueField  : 'name',
        displayField : 'name',
        typeAhead   : true,
        editable    : true
    });

    Form.combo.Names.superclass.constructor.call(this, config);
};

Ext.extend(Form.combo.Names, MODx.combo.ComboBox);

Ext.reg('form-combo-names', Form.combo.Names);