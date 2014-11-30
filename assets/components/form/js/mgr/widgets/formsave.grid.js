Form.grid.FormSave = function(config) {
    config = config || {};

	config.tbar = [{
		text		: _('bulk_actions'),
		cls			:'primary-button',
		menu		: [{
			text		: _('form.formsave_remove_selected'),
			handler		: this.removeSelectedFormSave,
			scope		: this
		}, '-', {
			text		: _('form.formsave_reset'),
			handler		: this.resetFormSave,
			scope		: this
		}]
	}, '->', {
    	xtype		: 'form-combo-xstatus',
    	name		: 'form-filter-status',
        id			: 'form-filter-status',
        emptyText	: _('form.filter_status'),
        listeners	: {
        	'select'	: {
	            	fn			: this.filterStatus,
	            	scope		: this   
		    }
		},
		width: 250
    }, {
    	xtype		: 'modx-combo-context',
    	hidden		: 0 == parseInt(Form.config.context) ? true : false,
    	name		: 'form-filter-context',
        id			: 'form-filter-context',
        emptyText	: _('form.filter_context'),
        listeners	: {
        	'select'	: {
	            	fn			: this.filterContext,
	            	scope		: this   
		    }
		},
		width: 250
    }, {
        xtype		: 'textfield',
        name 		: 'form-filter-search',
        id			: 'form-filter-search',
        emptyText	: _('search')+'...',
        listeners	: {
	        'change'	: {
	        	fn			: this.filterSearch,
	        	scope		: this
	        },
	        'render'		: {
		        fn			: function(cmp) {
			        new Ext.KeyMap(cmp.getEl(), {
				        key		: Ext.EventObject.ENTER,
			        	fn		: this.blur,
				        scope	: cmp
			        });
		        },
		        scope	: this
	        }
        }
    }, {
    	xtype	: 'button',
    	cls		: 'x-form-filter-clear',
    	id		: 'form-filter-clear',
    	text	: _('filter_clear'),
    	listeners: {
        	'click': {
        		fn		: this.clearFilter,
        		scope	: this
        	}
        }
    }];
    
    sm = new Ext.grid.CheckboxSelectionModel();

    columns = new Ext.grid.ColumnModel({
        columns: [sm, {
            header		: _('form.label_name'),
            dataIndex	: 'name',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed		: true,
            renderer	: this.renderName
        }, {
            header		: _('form.label_data'),
            dataIndex	: 'data_formatted',
            sortable	: true,
            editable	: false,
            width		: 100
        }, {
            header		: _('form.label_ipnumber'),
            dataIndex	: 'ip',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed		: true
        }, {
            header		: _('form.label_active'),
            dataIndex	: 'active',
            sortable	: true,
            editable	: false,
            width		: 100,
            fixed		: true,
			renderer	: this.renderBoolean
        }, {
            header		: _('form.label_date'),
            dataIndex	: 'editedon',
            sortable	: true,
            editable	: false,
            fixed		: true,
			width		: 200
        }, {
            header		: _('context'),
            dataIndex	: 'resource_context_key',
            sortable	: true,
            hidden		: true,
            editable	: false
        }]
    });
    
    Ext.applyIf(config, {
    	sm			: sm,
    	cm			: columns,
        id			: 'form-grid-formsave',
        url			: Form.config.connectorUrl,
        baseParams	: {
        	action		: 'mgr/getList'
        },
        fields		: ['id', 'resource_id', 'resource_url', 'resource_name', 'resource_name_alias', 'resource_context_key', 'name', 'ip', 'data', 'data_formatted', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        grouping	: 0 == parseInt(Form.config.context) ? false : true,
        groupBy		: 'resource_context_key',
        singleText	: _('form.formsave'),
        pluralText	: _('form.formsaves')
    });
    
    Form.grid.FormSave.superclass.constructor.call(this, config);
};

Ext.extend(Form.grid.FormSave, MODx.grid.Grid, {
	filterStatus: function(tf, nv, ov) {
        this.getStore().baseParams.status = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
	filterContext: function(tf, nv, ov) {
        this.getStore().baseParams.context = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
    	this.getStore().baseParams.status = '';
    	this.getStore().baseParams.context = '';
	    this.getStore().baseParams.query = '';
	    Ext.getCmp('form-filter-status').reset();
	    Ext.getCmp('form-filter-context').reset();
	    Ext.getCmp('form-filter-search').reset();
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('form.formsave_show'),
	        handler	: this.showFormSave
	    }, '-', {
		    text	: _('form.formsave_remove'),
		    handler	: this.removeFormSave
		 }];
    },
    showFormSave: function(btn, e) {
        if (this.showFormWindow) {
	        this.showFormWindow.destroy();
        }
        
        this.showFormWindow = MODx.load({
	        xtype		: 'form-window-formsave-show',
	        record		: this.menu.record,
	        closeAction	:'close',
	        buttons		: [{
	    		text    	: _('ok'),
	    		cls			: 'primary-button',
	    		handler		: function() {
	    			this.showFormWindow.close();
	    		},
	    		scope		: this
			}]
        });
        
        this.showFormWindow.show(e.target);
    },
    removeFormSave: function(btn, e) {
    	MODx.msg.confirm({
        	title 	: _('form.formsave_remove'),
        	text	: _('form.formsave_remove_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/remove',
            	id		: this.menu.record.id
            },
            listeners: {
            	'success': {
            		fn		: this.refresh,
            		scope	: this
            	}
            }
    	});
    },
    removeSelectedFormSave: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
    	MODx.msg.confirm({
        	title 	: _('form.formsave_remove_selected'),
        	text	: _('form.formsave_remove_selected_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/removeSelected',
            	ids		: cs
            },
            listeners: {
            	'success': {
            		fn		: function() {
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
            		scope	: this
            	}
            }
    	});
    },
    resetFormSave: function(btn, e) {
    	MODx.msg.confirm({
        	title 	: _('form.formsave_reset'),
        	text	: _('form.formsave_reset_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/reset',
            	id		: 'all'
            },
            listeners: {
            	'success': {
            		fn		: this.refresh,
            		scope	: this
            	}
            }
    	});
    },
    renderName: function(d, c, e) {
    	return String.format('<a href="{0}" target="_blank" title="{1}" class="x-grid-link">{2}</a>', e.json.resource_url, d, d);
    },
    renderBoolean: function(d, c) {
    	c.css = 1 == parseInt(d) || d ? 'green' : 'red';
    	
    	return 1 == parseInt(d) || d ? _('form.valid') : _('form.notvalid');
    }
});

Ext.reg('form-grid-formsave', Form.grid.FormSave);

Form.window.ShowFormSave = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
    	width		: 800,
        title 		: _('form.form') + ': ' + config.record.name,
	    labelAlign	: 'left',
        fields		: [{
        	layout		: 'column',
        	border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
        	items		: [{
	        	columnWidth	: .4,
	        	labelWidth	: 125,
	        	items		: [{
			        element		: 'label',
			        style		: 'padding: 7px 0;',
		            fieldLabel	: _('form.label_name'),
		            html 		: '<a href="' + config.record.resource_url + '" target="_blank", title="' + config.record.name + '">' + config.record.name + '</a>'
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('form.label_name_desc'),
		            cls			: 'desc-under'
		        }, {
			        element		: 'label',
			        style		: 'padding: 7px 0;',
			        fieldLabel	: _('form.label_ipnumber'),
		            html 		: config.record.ip
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('form.label_ipnumber_desc'),
		            cls			: 'desc-under'
		        }, {
			        element		: 'label',
			        style		: 'padding: 7px 0;',
		            fieldLabel	: _('form.label_date'),
		            html 		: config.record.editedon
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('form.label_date_desc'),
		            cls			: 'desc-under'
		        }, {
			        element		: 'label',
			        style		: 'padding: 7px 0;',
		            fieldLabel	: _('form.label_active'),
		            html 		: 1 == parseInt(config.record.active) || config.record.active ? _('form.valid') : _('form.notvalid'),
		            cls			: 1 == parseInt(config.record.active) || config.record.active ? 'green' : 'red',
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('form.label_active_desc'),
		            cls			: 'desc-under'
		        }]
	        }, {
		        columnWidth	: .6,
		        labelWidth	: 175,
		        style		: 'padding-left: 15px; border-left: 1px solid #f0f0f0; margin-right: 0;',
		        items		: this.formData(config.record.data)
	        }]	
	    }]
	});
    
    Form.window.ShowFormSave.superclass.constructor.call(this, config);
};

Ext.extend(Form.window.ShowFormSave, MODx.Window, {
	formData : function(data) {
		elements = [];
		
		for (var i = 0; i < data.length; i++) {
			elements.push({
				element		: 'label',
			    style		: 'padding: 7px 0;',
		        fieldLabel	: data[i].label,
		        html 		: Ext.util.Format.nl2br(data[i].value)
		    });
		}
		
		return elements;
	}
});

Ext.reg('form-window-formsave-show', Form.window.ShowFormSave);

Form.combo.Status = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type','label'],
            data	: [
                [1, _('form.valid')],
               	[0, _('form.notvalid')]
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'status',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Form.combo.Status.superclass.constructor.call(this,config);
};

Ext.extend(Form.combo.Status, MODx.combo.ComboBox);

Ext.reg('form-combo-xstatus', Form.combo.Status);