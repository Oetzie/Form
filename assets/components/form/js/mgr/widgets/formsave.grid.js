Form.grid.FormSave = function(config) {
    config = config || {};

	config.tbar = [{
		text		: _('bulk_actions'),
		menu		: [{
			text		: _('form.formsaves_remove_selected'),
			handler		: this.removeSelectedFormSaves,
			scope		: this
		}, '-', {
			text		: _('form.formsave_reset'),
			handler		: this.resetFormSave,
			scope		: this
		}]
	}, {
		xtype		: 'checkbox',
		name		: 'form-refresh-formsave',
        id			: 'form-refresh-formsave',
		boxLabel	: _('form.auto_refresh_grid'),
		listeners	: {
			'check'		: {
				fn 			: this.autoRefresh,
				scope 		: this	
			},
		}
	}, '->', {
    	xtype		: 'form-combo-status',
    	name		: 'form-filter-status',
        id			: 'form-filter-status',
        emptyText	: _('form.filter_status'),
        listeners	: {
        	'select'	: {
	            	fn		: this.filterStatus,
	            	scope	: this   
		    }
		}
    }, {
    	xtype		: 'modx-combo-context',
    	hidden		: Form.config.context,
    	name		: 'form-filter-context',
        id			: 'form-filter-context',
        value		: MODx.config.default_context,
        emptyText	: _('form.filter_context'),
        listeners	: {
        	'select'	: {
	            	fn		: this.filterContext,
	            	scope	: this   
		    }
		},
		baseParams	: {
			action		: 'context/getlist',
			exclude		: 'mgr'
		}
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
    	xtype		: 'button',
    	cls			: 'x-form-filter-clear',
    	id			: 'form-filter-clear',
    	text		: _('filter_clear'),
    	listeners	: {
        	'click'		: {
        		fn			: this.clearFilter,
        		scope		: this
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
			width		: 200,
			renderer	: this.renderDate
        }]
    });
    
    Ext.applyIf(config, {
    	sm			: sm,
    	cm			: columns,
        id			: 'form-grid-formsave',
        url			: Form.config.connector_url,
        baseParams	: {
        	action		: 'mgr/formsave/getlist',
        	context		: MODx.config.default_context
        },
        fields		: ['id', 'context_key', 'context_name', 'resource_id', 'url', 'pagetitle', 'name', 'ip', 'data', 'data_formatted', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        gridRefresh	: {
	        timer 		: null,
	        duration	: 30,
	        count 		: 0
        }
    });
    
    Form.grid.FormSave.superclass.constructor.call(this, config);
};

Ext.extend(Form.grid.FormSave, MODx.grid.Grid, {
	autoRefresh: function(tf, nv) {
		var scope = this;
		
		if (nv) {
			scope.config.gridRefresh.timer = setInterval(function() {
				tf.setBoxLabel(_('form.auto_refresh_grid') + ' (' + (scope.config.gridRefresh.duration - scope.config.gridRefresh.count) + ')');
				
				if (0 == (scope.config.gridRefresh.duration - scope.config.gridRefresh.count)) {
					scope.config.gridRefresh.count = 0;
					
					scope.getBottomToolbar().changePage(1);
				} else {
					scope.config.gridRefresh.count++;
				}
			}, 1000);
		} else {
			clearInterval(scope.config.gridRefresh.timer);
		}
	},
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
    	this.getStore().baseParams.context = MODx.config.default_context;
	    this.getStore().baseParams.query = '';
	    
	    Ext.getCmp('form-filter-status').reset();
	    Ext.getCmp('form-filter-context').reset();
	    Ext.getCmp('form-filter-search').reset();
	    
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
	    console.log(this.menu.record.data);
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
        	url		: Form.config.connector_url,
        	params	: {
            	action	: 'mgr/formsave/remove',
            	id		: this.menu.record.id
            },
            listeners: {
            	'success'	: {
            		fn			: this.refresh,
            		scope		: this
            	}
            }
    	});
    },
    removeSelectedFormSaves: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
    	MODx.msg.confirm({
        	title 	: _('form.formsave_remove_selected'),
        	text	: _('form.formsave_remove_selected_confirm'),
        	url		: Form.config.connector_url,
        	params	: {
            	action	: 'mgr/formsave/removeselected',
            	ids		: cs
            },
            listeners: {
            	'success'	: {
            		fn			: function() {
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
            		scope		: this
            	}
            }
    	});
    },
    resetFormSave: function(btn, e) {
    	MODx.msg.confirm({
        	title 	: _('form.formsave_reset'),
        	text	: _('form.formsave_reset_confirm'),
        	url		: Form.config.connector_url,
        	params	: {
            	action	: 'mgr/formsave/reset'
            },
            listeners: {
            	'success'	: {
            		fn			: this.refresh,
            		scope		: this
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
    },
	renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('form-grid-formsave', Form.grid.FormSave);

Form.window.ShowFormSave = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: false,
    	height		: 500,
    	width		: 700,
        title 		: _('form.form') + ': ' + config.record.name,
	    labelAlign	: 'left',
	    labelWidth	: 200,
        fields		: this.formData(config.record.data)
	});
    
    Form.window.ShowFormSave.superclass.constructor.call(this, config);
};

Ext.extend(Form.window.ShowFormSave, MODx.Window, {
	formData : function(data) {
		elements = [];
		
		for (key in data) {
			var element = data[key];
			
			switch (element.type) {
				case 'radio':
				case 'checkbox':
					var items = [];
					
					for (value in element.values) {
						if (typeof element.values[value] != 'function') {
							items.push({
							    boxLabel	: element.values[value],
							    checked		: (typeof element.value == 'object' && -1 != element.value.indexOf(value)) || value == element.value ? true : false,
							});
						}
					}
					
					elements.push({
						xtype		: element.type + 'group',
						fieldLabel	: element.label,
						items		: items,
						columns		: 1,
						anchor 		: '100%',
				        disabled 	: true
					});
				
					break;
				case 'select':
					elements.push({
						xtype		: 'textfield',
				        fieldLabel	: element.label,
				        value		: undefined == element.values[element.value] ? element.value : element.values[element.value],
				        anchor 		: '100%',
				        disabled	: true
				    });
				    
					break;
				case 'textarea':
					elements.push({
						xtype		: 'textarea',
				        fieldLabel	: element.label,
				        value 		: element.value,
				        anchor 		: '100%',
				        disabled	: true
				    });
			    
					break;
				case 'password':
					elements.push({
						xtype		: 'textfield',
						inputType	: 'password',
				        fieldLabel	: element.label,
				        value 		: element.value,
				        anchor 		: '100%',
				        disabled	: true
				    });
				    
					break;
				default:
					elements.push({
						xtype		: 'textfield',
				        fieldLabel	: element.label,
				        value		: element.value,
				        anchor 		: '100%',
				        disabled	: true
				    });

					break;
			}
			
			if (!element.valid) {
			   elements.push({
		        	xtype		: 'label',
		        	fieldLabel	: '&nbsp;',
		            html		: _('form.has_error_' + element.error.type.toLowerCase(), element.error),
		            cls			: 'desc-under red'
		        });
		    }
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
                ['1', _('form.valid')],
               	['0', _('form.notvalid')]
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

Ext.reg('form-combo-status', Form.combo.Status);