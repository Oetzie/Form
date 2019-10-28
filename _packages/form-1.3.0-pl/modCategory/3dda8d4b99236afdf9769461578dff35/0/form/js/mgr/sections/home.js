Ext.onReady(function() {
    MODx.load({
        xtype : 'form-page-home'
    });
});

Form.page.Home = function(config) {
    config = config || {};

    config.buttons = [];

    if (Form.config.branding_url) {
        config.buttons.push({
            text        : 'Form ' + Form.config.version,
            cls         : 'x-btn-branding',
            handler     : this.loadBranding
        });
    }

    config.buttons.push({
        xtype       : 'modx-combo-context',
        hidden      : Form.config.context,
        value       : MODx.request.context || MODx.config.default_context,
        name        : 'form-filter-context',
        emptyText   : _('form.filter_context'),
        listeners   : {
            'select'    : {
                fn          : this.filterContext,
                scope       : this
            }
        },
        baseParams  : {
            action      : 'context/getlist',
            exclude     : 'mgr'
        }
    });

    if (Form.config.branding_url_help) {
        config.buttons.push({
            text        : _('help_ex'),
            handler     : MODx.loadHelpPane,
            scope       : this
        });
    }

    Ext.applyIf(config, {
        components  : [{
            xtype       : 'form-panel-home',
            renderTo    : 'form-panel-home-div'
        }]
    });

    Form.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(Form.page.Home, MODx.Component, {
    loadBranding: function(btn) {
        window.open(Form.config.branding_url);
    },
    filterContext: function(tf) {
        MODx.loadPage('?a=home&namespace=' + Form.config.namespace + '&context=' + tf.getValue());
    }
});

Ext.reg('form-page-home', Form.page.Home);