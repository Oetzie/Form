Form.panel.Home = function(config) {
	config = config || {};
	
    Ext.apply(config, {
        id			: 'form-panel-home',
        cls			: 'container',
        items		: [{
            html		: '<h2>'+_('form')+'</h2>',
            id			: 'form-header',
            cls			: 'modx-page-header'
        }, {
        	layout		: 'form',
            items		: [{
            	html			: '<p>' + _('form.forms_desc') + '</p>',
                bodyCssClass	: 'panel-desc'
            }, {
                xtype			: 'form-grid-forms',
                cls				: 'main-wrapper',
                preventRender	: true
            }]
        }]
    });

	Form.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(Form.panel.Home, MODx.FormPanel);

Ext.reg('form-panel-home', Form.panel.Home);