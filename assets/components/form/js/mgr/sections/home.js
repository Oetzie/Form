Ext.onReady(function() {
	MODx.load({xtype: 'form-page-home'});
});

Form.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [{
		text		: _('help_ex'),
		handler		: MODx.loadHelpPane,
		scope		: this
	}];
	
	Ext.applyIf(config, {
		components	: [{
			xtype		: 'form-panel-home',
			renderTo	: 'form-panel-home-div'
		}]
	});
	
	Form.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(Form.page.Home, MODx.Component);

Ext.reg('form-page-home', Form.page.Home);