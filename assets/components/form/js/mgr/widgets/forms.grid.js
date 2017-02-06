Form.grid.Forms = function(config) {
    config = config || {};

	config.tbar = [{
		text		: _('bulk_actions'),
		menu		: [{
			text		: _('form.forms_remove_selected'),
			handler		: this.removeSelectedForms,
			scope		: this
		}, '-', {
			text		: _('form.forms_reset'),
			handler		: this.resetForms,
			scope		: this
		}]
	}, {
		xtype		: 'checkbox',
		name		: 'form-refresh-forms',
        id			: 'form-refresh-forms',
		boxLabel	: _('form.auto_refresh_grid'),
		listeners	: {
			'check'		: {
				fn 			: this.autoRefresh,
				scope 		: this	
			},
		}
	}, '->', {
    	xtype		: 'form-combo-names',
    	name		: 'form-filter-names',
        id			: 'form-filter-names',
        emptyText	: _('form.filter_names'),
        listeners	: {
        	'select'	: {
	           	fn			: this.filterNames,
	            scope		: this   
		    }
		}
    }, {
    	xtype		: 'form-combo-status',
    	name		: 'form-filter-status',
        id			: 'form-filter-status',
        emptyText	: _('form.filter_status'),
        listeners	: {
        	'select'	: {
	            fn			: this.filterStatus,
	            scope		: this   
		    }
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
	        'render'	: {
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
        id			: 'form-grid-forms',
        url			: Form.config.connector_url,
        baseParams	: {
        	action		: 'mgr/forms/getlist',
        	context		: MODx.request.context || MODx.config.default_context
        },
        fields		: ['id', 'context_key', 'context_name', 'resource_id', 'resource_url', 'name', 'ip', 'data', 'data_formatted', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        gridRefresh	: {
	        timer 		: null,
	        duration	: 30,
	        count 		: 0
        }
    });
    
    Form.grid.Forms.superclass.constructor.call(this, config);
};

Ext.extend(Form.grid.Forms, MODx.grid.Grid, {
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
	filterNames: function(tf, nv, ov) {
        this.getStore().baseParams.names = tf.getValue();
        
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
	    this.getStore().baseParams.names = '';
    	this.getStore().baseParams.status = '';
	    this.getStore().baseParams.query = '';
	    
	    Ext.getCmp('form-filter-names').reset();
	    Ext.getCmp('form-filter-status').reset();
	    Ext.getCmp('form-filter-search').reset();
	    
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('form.form_show'),
	        handler	: this.showForm
	    }, '-', {
		    text	: _('form.form_remove'),
		    handler	: this.removeForm
		 }];
    },
    showForm: function(btn, e) {
        if (this.showFormWindow) {
	        this.showFormWindow.destroy();
        }
        
        this.showFormWindow = MODx.load({
	        xtype		: 'form-window-form-show',
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
    removeForm: function(btn, e) {
    	MODx.msg.confirm({
        	title 	: _('form.form_remove'),
        	text	: _('form.form_remove_confirm'),
        	url		: Form.config.connector_url,
        	params	: {
            	action	: 'mgr/forms/remove',
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
    removeSelectedForms: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
    	MODx.msg.confirm({
        	title 	: _('form.forms_remove_selected'),
        	text	: _('form.forms_remove_selected_confirm'),
        	url		: Form.config.connector_url,
        	params	: {
            	action	: 'mgr/forms/removeselected',
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
    resetForms: function(btn, e) {
    	MODx.msg.confirm({
        	title 	: _('form.forms_reset'),
        	text	: _('form.forms_reset_confirm'),
        	url		: Form.config.connector_url,
        	params	: {
            	action	: 'mgr/forms/reset'
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

Ext.reg('form-grid-forms', Form.grid.Forms);

Form.window.ShowForm = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: false,
    	height		: 500,
    	width		: 700,
        title 		: _('form.form_show'),
	    labelAlign	: 'left',
	    labelWidth	: 200,
        fields		: this.getFormData(config.record.data)
	});
    
    Form.window.ShowForm.superclass.constructor.call(this, config);
};

Ext.extend(Form.window.ShowForm, MODx.Window, {
	getFormData : function(data) {
		var elements = [];
		
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
					if ('recaptcha' != element.type) {
						elements.push({
							xtype		: 'textfield',
					        fieldLabel	: element.label,
					        value		: element.value,
					        anchor 		: '100%',
					        disabled	: true
					    });
					}

					break;
			}

			if (typeof element.error == 'object') {
				if ('recaptcha' == element.type) {
					elements.push({
			        	xtype		: 'textfield',
			        	fieldLabel	: element.label,
			            value		: _('form.is_' + element.error.type.toLowerCase(), element.error),
			            anchor 		: '100%',
			            cls			: 'red',
					    disabled	: true
			        });
				} else {
					elements.push({
			        	xtype		: 'label',
			        	style		: 'padding-left: 205px;',
			            html		: _('form.is_' + element.error.type.toLowerCase(), element.error),
			            cls			: 'desc-under red'
			        });
			    }
		    }
		}
		
		return elements;
	}
});

Ext.reg('form-window-form-show', Form.window.ShowForm);

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

Form.combo.Names = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: Form.config.connector_url,
        baseParams 	: {
            action		: 'mgr/forms/getnodes',
            context		: MODx.request.context || MODx.config.default_context,
            combo		: true
        },
        fields		: ['id', 'context_key', 'context_name', 'name'],
        hiddenName	: 'forms',
        pageSize	: 15,
        valueField	: 'name',
        displayField: 'name',
        typeAhead	: true,
        editable	: true
    });
    
    Form.combo.Names.superclass.constructor.call(this,config);
};

Ext.extend(Form.combo.Names, MODx.combo.ComboBox);

Ext.reg('form-combo-names', Form.combo.Names);