Form.grid.Forms = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('bulk_actions'),
        menu        : [{
            text        : '<i class="x-menu-item-icon icon icon-history"></i>' + _('form.forms_clean'),
            handler     : this.cleanForms,
            scope       : this
        }, '-', {
            text        : '<i class="x-menu-item-icon icon icon-times"></i>' + _('form.forms_reset'),
            handler     : this.resetForms,
            scope       : this
        }]
    }, '->', {
        xtype       : 'form-combo-names',
        name        : 'form-filter-form',
        id          : 'form-filter-form',
        emptyText   : _('form.filter_form'),
        listeners   : {
            'select'    : {
                fn          : this.filterForm,
                scope       : this
            }
        }
    }, {
        xtype       : 'form-combo-status',
        name        : 'form-filter-status',
        id          : 'form-filter-status',
        hidden      : !Form.config['form_save_invalid'],
        emptyText   : _('form.filter_status'),
        listeners   : {
            'select'    : {
                fn          : this.filterStatus,
                scope       : this
            }
        }
    }, {
        xtype       : 'textfield',
        name        : 'form-filter-search',
        id          : 'form-filter-search',
        emptyText   : _('search')+'...',
        listeners   : {
            'change'    : {
                fn          : this.filterSearch,
                scope       : this
            },
            'render'    : {
                fn          : function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key     : Ext.EventObject.ENTER,
                        fn      : this.blur,
                        scope   : cmp
                    });
                },
                scope       : this
            }
        }
    }, {
        xtype       : 'button',
        cls         : 'x-form-filter-clear',
        id          : 'form-filter-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];
    
    var sm = new Ext.grid.CheckboxSelectionModel();

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('form.label_form_name'),
            dataIndex   : 'name',
            sortable    : true,
            editable    : false,
            width       : 200,
            fixed       : true,
            renderer    : this.renderName
        }, {
            header      : _('form.label_form_data'),
            dataIndex   : 'data_formatted',
            sortable    : true,
            editable    : false,
            width       : 100
        }, {
            header      : _('form.label_form_ipnumber'),
            dataIndex   : 'ip',
            sortable    : true,
            editable    : false,
            width       : 150,
            fixed       : true
        }, {
            header      : _('form.label_form_active'),
            dataIndex   : 'active',
            sortable    : true,
            editable    : false,
            width       : 100,
            fixed       : true,
            renderer    : this.renderBoolean
        }, {
            header      : _('form.label_form_date'),
            dataIndex   : 'editedon',
            sortable    : true,
            editable    : false,
            fixed       : true,
            width       : 200,
            renderer    : this.renderDate
        }]
    });
    
    Ext.applyIf(config, {
        cm          : columns,
        id          : 'form-grid-forms',
        url         : Form.config.connector_url,
        baseParams  : {
            action      : 'mgr/forms/getlist',
            context     : MODx.request.context || MODx.config.default_context
        },
        fields      : ['id', 'context_key', 'name', 'ip', 'data', 'data_formatted', 'active', 'editedon', 'context_name', 'resource_id', 'resource_url',],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'id',
    });
    
    Form.grid.Forms.superclass.constructor.call(this, config);
};

Ext.extend(Form.grid.Forms, MODx.grid.Grid, {
    filterForm: function(tf, nv, ov) {
        this.getStore().baseParams.form = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    filterStatus: function(tf, nv, ov) {
        this.getStore().baseParams.status = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.form     = '';
        this.getStore().baseParams.status   = '';
        this.getStore().baseParams.query    = '';

        Ext.getCmp('form-filter-form').reset();
        Ext.getCmp('form-filter-status').reset();
        Ext.getCmp('form-filter-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
            text        : '<i class="x-menu-item-icon icon icon-search"></i>' + _('form.form_view'),
            handler     : this.viewForm
        }, {
            text        : '<i class="x-menu-item-icon icon icon-question-circle"></i>' + _('form.form_view_ip'),
            handler     : this.viewFormByIP
        }, '-', {
            text        : '<i class="x-menu-item-icon icon icon-times"></i>' + _('form.form_remove'),
            handler     : this.removeForm
         }];
    },
    viewForm: function(btn, e) {
        if (this.viewFormWindow) {
            this.viewFormWindow.destroy();
        }

        this.viewFormWindow = MODx.load({
            xtype       : 'form-window-form-view',
            record      : this.menu.record,
            buttons     : [{
                text        : _('ok'),
                cls         : 'primary-button',
                handler     : function() {
                    this.viewFormWindow.close();
                },
                scope       : this
            }]
        });

        this.viewFormWindow.show(e.target);
    },
    viewFormByIP: function(btn, e) {
        this.getStore().baseParams.form     = '';
        this.getStore().baseParams.status   = '';
        this.getStore().baseParams.query    = this.menu.record.ip;

        Ext.getCmp('form-filter-search').setValue(this.menu.record.ip);
        Ext.getCmp('form-filter-form').reset();
        Ext.getCmp('form-filter-status').reset();

        this.getBottomToolbar().changePage(1);
    },
    removeForm: function(btn, e) {
        MODx.msg.confirm({
            title       : _('form.form_remove'),
            text        : _('form.form_remove_confirm'),
            url         : Form.config.connector_url,
            params      : {
                action      : 'mgr/forms/remove',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
    },
    cleanForms: function(btn, e) {
        if (this.cleanFormsWindow) {
            this.cleanFormsWindow.destroy();
        }

        this.cleanFormsWindow = MODx.load({
            xtype       : 'form-window-forms-clean',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function(record) {
                        MODx.msg.status({
                            title   : _('success'),
                            message : record.a.result.message,
                            delay   : 4
                        });

                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.cleanFormsWindow.show(e.target);
    },
    resetForms: function(btn, e) {
        MODx.msg.confirm({
            title       : _('form.forms_reset'),
            text        : _('form.forms_reset_confirm'),
            url         : Form.config.connector_url,
            params      : {
                action      : 'mgr/forms/reset'
            },
            listeners   : {
                'success'   : {
                    fn      : this.refresh,
                    scope       : this
                }
            }
        });
    },
    renderName: function(d, c, e) {
        return String.format('<a href="{0}" target="_blank" title="{1}" class="x-grid-link">{2}</a>', e.json.resource_url, d, d);
    },
    renderBoolean: function(d, c) {
        c.css = parseInt(d) === 1 || d ? 'green' : 'red';

        return parseInt(d) === 1 || d ? _('form.valid') : _('form.notvalid');
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('form-grid-forms', Form.grid.Forms);

Form.window.ViewForm = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        autoHeight  : false,
        height      : 500,
        width       : 700,
        title       : _('form.form_view'),
        bodyStyle   : 'padding: 15px;',
        labelAlign  : 'left',
        labelWidth  : 200,
        fields      : this.getFormData(config.record, config.record.data)
    });
    
    Form.window.ViewForm.superclass.constructor.call(this, config);
};

Ext.extend(Form.window.ViewForm, MODx.Window, {
    getFormData : function(record, data) {
        var elements = [{
            xtype       : 'statictextfield',
            fieldLabel  : _('form.label_form_ipnumber'),
            description : MODx.expandHelp ? '' : _('form.label_form_ipnumber_desc'),
            value       : record.ip,
            anchor      : '100%',
            readOnly    : true
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('form.label_form_ipnumber_desc'),
            cls         : 'desc-under',
            style       : 'padding-left: 205px;'
        }, {
            xtype       : 'statictextfield',
            fieldLabel  : _('form.label_form_active'),
            description : MODx.expandHelp ? '' : _('form.label_form_active_desc'),
            value       : parseInt(record.active) === 1|| record.active ? _('form.valid') : _('form.notvalid'),
            cls         : parseInt(record.active) === 1|| record.active ? 'green' : 'red',
            anchor      : '100%',
            readOnly    : true
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('form.label_form_active_desc'),
            cls         : 'desc-under',
            style       : 'padding-left: 205px;'
        }, {
            xtype       : 'statictextfield',
            fieldLabel  : _('form.label_form_date'),
            description : MODx.expandHelp ? '' : _('form.label_form_date'),
            value       : record.editedon,
            anchor      : '100%',
            readOnly    : true
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('form.label_form_date_desc'),
            cls         : 'desc-under',
            style       : 'padding-left: 205px;'
        }, {
            html        : '<hr />'
        }];

        Ext.iterate(data, function (key, element) {
            switch (element.type) {
                case 'radio':
                case 'checkbox':
                    if (element.values) {
                        var items = [];

                        Ext.iterate(element.values, function (optionValue, optionLabel) {
                            items.push({
                                boxLabel    : optionLabel,
                                checked     : (typeof element.value === 'object' && element.value.indexOf(optionValue) !== -1) || optionValue === element.value,
                            });
                        });

                        elements.push({
                            xtype       : element.type + 'group',
                            fieldLabel  : element.label,
                            items       : items,
                            columns     : 1,
                            anchor      : '100%',
                            readOnly    : true
                        });
                    } else {
                        elements.push({
                            xtype       : 'textfield',
                            fieldLabel  : element.label,
                            value       : typeof element.value === 'object' ? element.value.join(', ') : element.value,
                            anchor      : '100%',
                            readOnly    : true
                        });
                    }

                    break;
                case 'select':
                        elements.push({
                            xtype       : 'textfield',
                            fieldLabel  : element.label,
                            value       : element.values && element.values[element.value] ? element.values[element.value] : element.value,
                            anchor      : '100%',
                            readOnly    : true
                        });

                    break;
                case 'textarea':
                    elements.push({
                        xtype       : 'textarea',
                        fieldLabel  : element.label,
                        value       : element.value,
                        anchor      : '100%',
                        readOnly    : true
                    });

                    break;
                case 'password':
                    elements.push({
                        xtype       : 'textfield',
                        inputType   : 'password',
                        fieldLabel  : element.label,
                        value       : element.value,
                        anchor      : '100%',
                        readOnly    : true
                    });

                    break;
                default:
                    if (element.type !== 'recaptcha') {
                        elements.push({
                            xtype       : 'textfield',
                            fieldLabel  : element.label,
                            value       : element.value,
                            anchor      : '100%',
                            readOnly    : true
                        });
                    }

                    break;
            }

            if (element.error) {
                if (element.type === 'recaptcha') {
                    elements.push({
                        xtype       : 'textfield',
                        fieldLabel  : element.label,
                        value       : _('form.validator_' + element.error.toLowerCase(), element.error),
                        anchor      : '100%',
                        cls         : 'red',
                        readOnly    : true
                    });
                } else {
                    elements.push({
                        xtype       : 'label',
                        style       : 'padding-left: 205px;',
                        html        : _('form.validator_' + element.error.toLowerCase(), element.error),
                        cls         : 'desc-under red'
                    });
                }
            }
        });

        return elements;
    }
});

Ext.reg('form-window-form-view', Form.window.ViewForm);

Form.window.CleanForms = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        width       : 500,
        title       : _('form.forms_clean'),
        url         : Form.config.connector_url,
        baseParams  : {
            action      : 'mgr/forms/clean'
        },
        fields      : [{
            html        : '<p>' + _('form.forms_clean_desc') + '</p>',
            cls         : 'panel-desc',
        }, {
            xtype       : 'modx-panel',
            items       : [{
                xtype       : 'label',
                html        : _('formit.label_clean_label')
            }, {
                xtype       : 'numberfield',
                name        : 'days',
                minValue    : 1,
                maxValue    : 9999999999,
                value       : Form.config.clean_days,
                width       : 75,
                allowBlank  : false,
                style       : 'margin: 0 10px;'
            }, {
                xtype       : 'label',
                html        : _('formit.label_clean_desc'),
            }]
        }],
        keys        : [],
        saveBtnText : _('formit.forms_clean'),
        waitMsg     : _('formit.forms_clean_executing')
    });

    Form.window.CleanForms.superclass.constructor.call(this, config);
};

Ext.extend(Form.window.CleanForms, MODx.Window);

Ext.reg('form-window-forms-clean', Form.window.CleanForms);