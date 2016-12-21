// Global functions

// Global munkireport object
var mr = {
        dt:{},
        
        statusFormat: {
            install_failed: {type: 'danger'},
            install_succeeded: {type: 'success'},
            installed: {type: 'info'},
            pending_install: {type: 'warning'},
            pending_removal: {type: 'warning'},
            removed: {type: 'info'},
            uninstall_failed: {type: 'danger'},
            uninstalled: {type: 'success'}
        },
        
        // Graphing defaults
        graph: {
            barColor: ['steelBlue']
        },
        
        // Localstorage handler
        state: function(id, data){
            
            if( data == undefined)
            {
              // Get data
              return JSON.parse( localStorage.getItem(id) );

            }
            else
            {
              // Set data
              localStorage.setItem( id, JSON.stringify(data) );
            }
        },
        
        // Set Preference handler (uses localstorage)
        setPref: function(key, val){
            var globalPrefs = mr.state('global') || {};
            globalPrefs[key] = val;
            mr.state('global', globalPrefs);
        },
        
        // Get Preference handler (uses localstorage)
        getPref: function(key){
            var globalPrefs = mr.state('global') || {};
            return globalPrefs[key];
        },
        
        // Integer or integer string OS Version to semantic OS version
        integerToVersion: function(osvers)
        {
        	osvers = "" + osvers
        	// If osvers contains a dot, don't convert
        	if( osvers.indexOf(".") == -1)
            {
        		// Remove non-numerical string
        		osvers = isNaN(osvers) ? "" : osvers;

        		// Left pad with zeroes if necessary
        		osvers = ("000000" + osvers).substr(-6)
        		osvers = osvers.match(/.{2}/g).map(function(x){return +x}).join('.')
            }
            return osvers
        },
        
        // Get client detail link
        getClientDetailLink: function(name, sn, hash)
        {
        	hash = (typeof hash === "undefined") ? "" : hash;
        	return '<div class="machine">\
            		<a class="btn btn-default btn-xs" href="'+appUrl+'/clients/detail/'+sn+hash+'">'+name+'</a>\
            		<a href="'+appUrl+'/manager/delete_machine/'+sn+'" class="btn btn-xs btn-danger">\
            		<i class="fa fa-times"></i></a></div>';
        },
        
        /*
         * Natural Sort algorithm for Javascript - Version 0.8.1 - Released under MIT license
         * Author: Jim Palmer (based on chunking idea from Dave Koelle)
         */
        naturalSort: function(a, b) {
            var re = /(^([+\-]?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?(?=\D|\s|$))|^0x[\da-fA-F]+$|\d+)/g,
                sre = /^\s+|\s+$/g,   // trim pre-post whitespace
                snre = /\s+/g,        // normalize all whitespace to single ' ' character
                dre = /(^([\w ]+,?[\w ]+)?[\w ]+,?[\w ]+\d+:\d+(:\d+)?[\w ]?|^\d{1,4}[\/\-]\d{1,4}[\/\-]\d{1,4}|^\w+, \w+ \d+, \d{4})/,
                hre = /^0x[0-9a-f]+$/i,
                ore = /^0/,
                insensitive = true,
                i = function(s) {
                    return (insensitive && ('' + s).toLowerCase() || '' + s).replace(sre, '');
                },
                // convert all to strings strip whitespace
                x = i(a),
                y = i(b),
                // chunk/tokenize
                xN = x.replace(re, '\0$1\0').replace(/\0$/,'').replace(/^\0/,'').split('\0'),
                yN = y.replace(re, '\0$1\0').replace(/\0$/,'').replace(/^\0/,'').split('\0'),
                // numeric, hex or date detection
                xD = parseInt(x.match(hre), 16) || (xN.length !== 1 && Date.parse(x)),
                yD = parseInt(y.match(hre), 16) || xD && y.match(dre) && Date.parse(y) || null,
                normChunk = function(s, l) {
                    // normalize spaces; find floats not starting with '0', string or 0 if not defined (Clint Priest)
                    return (!s.match(ore) || l == 1) && parseFloat(s) || s.replace(snre, ' ').replace(sre, '') || 0;
                },
                oFxNcL, oFyNcL;
            // first try and sort Hex codes or Dates
            if (yD) {
                if (xD < yD) { return -1; }
                else if (xD > yD) { return 1; }
            }
            // natural sorting through split numeric strings and default strings
            for(var cLoc = 0, xNl = xN.length, yNl = yN.length, numS = Math.max(xNl, yNl); cLoc < numS; cLoc++) {
                oFxNcL = normChunk(xN[cLoc] || '', xNl);
                oFyNcL = normChunk(yN[cLoc] || '', yNl);
                // handle numeric vs string comparison - number < string - (Kyle Adams)
                if (isNaN(oFxNcL) !== isNaN(oFyNcL)) {
                    return isNaN(oFxNcL) ? 1 : -1;
                }
                // if unicode use locale comparison
                if (/[^\x00-\x80]/.test(oFxNcL + oFyNcL) && oFxNcL.localeCompare) {
                    var comp = oFxNcL.localeCompare(oFyNcL);
                    return comp / Math.abs(comp);
                }
                if (oFxNcL < oFyNcL) { return -1; }
                else if (oFxNcL > oFyNcL) { return 1; }
            }
        }, // End naturalSort
        
        // Draw graph for nvd3 and update graph
        drawGraph: function(conf){
            var graphData = [{"key": " ", "values": []}];
            d3.json(conf.url, function(data) {
                graphData[0].values = data;
                height = data.length * 26 + 40;
                conf.chart.height(height);
                
                d3.select(conf.svg)
                    .attr('height', height)
                    .datum(graphData)
                    .transition()
                    .duration(500)
                    .call(conf.chart);
                    
                conf.chart.update();
                
            });
            
        },
        
        // Get preference for graph in this order: conf, default for graph, default
        getGraphPref: function(setting, graphName, conf){
            if(conf[setting]) return conf[setting];
            if(mr.graph[graphName] && mr.graph[graphName][setting]) return mr.graph[graphName][setting];
            return mr.graph[setting];
        },
        
        // Add nvd3 graph
        addGraph: function(conf){
            // Sanity check
            if( ! conf.widget){
                alert('no widget provided for addGraph');
                return;
            };
            
            // Assemble svg identifier
            conf.svg = '#' + conf.widget + ' svg';

            nv.addGraph(function() {
              conf.chart = nv.models.multiBarHorizontalChart()
                  .x(function(d) { return conf.labelModifier ? conf.labelModifier(d.label) : d.label })
                  .y(function(d) { return d.count })
                  .margin(conf.margin ? conf.margin : {top: 20, right: 10, bottom: 20, left: 70})
                  .showValues(true)
                  .valueFormat(d3.format(''))
                  .tooltips(false)
                  .showControls(false)
                  .showLegend(false)
                  .barColor(mr.getGraphPref('barColor', conf.widget, conf))//conf.barColor ? conf.barColor : (graphSettings.barColor ? mr.graph.barColor))
                  .height(0);

              conf.chart.yAxis
                  .tickFormat(d3.format(''));
                  
              d3.select(conf.svg)
                  .attr('height', 0)
                  .datum([{"key": " ","values": []}])
                  .call(conf.chart);
            
            // Callback for click events
              if(conf.elementClickCallback){
                  // visit page on click
                  conf.chart.multibar.dispatch.on("elementClick", function(e) {
                      conf.elementClickCallback(e)
                  });
                  
                  d3.select(conf.svg).attr("class", "clickLabels");
              }
                
                // Call the munkireport drawGraph routine
                mr.drawGraph(conf);
                
                // update chart data on appUpdate
                $(document).on('appUpdate', function(){mr.drawGraph(conf)});
                
                // update chart data on Resize
                nv.utils.windowResize(conf.chart.update);

            });

        },
                
        loadTheme: function() {
            // Get global state
            var theme = mr.getPref('theme') || 'Default';
            var theme_dir = baseUrl + 'assets/themes/' + theme + '/';
            var theme_file = theme_dir + 'bootstrap.min.css';
            $('#bootstrap-stylesheet').attr('href', theme_dir + 'bootstrap.min.css');
            $('#nvd3-override-stylesheet').attr('href', theme_dir + 'nvd3.override.css');
            
            // Add active to menu item
            $('[data-switch]').parent().removeClass('active');
            $('[data-switch="'+theme+'"]').parent().addClass('active');
        }

    };

// Load theme files
mr.loadTheme();

$(document).on('appReady', function(e, lang) {
    

    // addMenuItem({
    //     menu: 'admin',
    //     i18n: 'notification.menu_link',
    //     url: appUrl + '/module/notification/manage'
    // });
    
    addMenuItem({
        menu: 'admin',
        i18n: 'systemstatus.menu_link',
        url: appUrl + '/system/show/status'
    });
    addMenuItem({
        menu: 'report',
        i18n: 'managedinstalls.installratio_report',
        url: appUrl + '/module/managedinstalls/view/pkg_stats'
    }); 


});


$( document ).ready(function() {
    
    
    // Theme switcher
    $('a[data-switch]').on('click', function(){
        mr.setPref('theme', $(this).data('switch'));
        mr.loadTheme();
   });
    
    $.i18n.init({
        debug: munkireport.debug,
        useLocalStorage: false,
        resGetPath: munkireport.subdirectory + "assets/locales/__lng__.json",
        fallbackLng: 'en',
        useDataAttrOptions: true
    }, function() {
        $('body').i18n();

        // Check if current locale is available (FIXME: check loaded locale)
        if( ! $('.locale a[data-i18n=\'nav.lang.' + i18n.lng() + '\']').length)
        {
          // Load 'en' instead...
          i18n.setLng('en', function(t) { /* loading done - should init other stuff now*/ });
        }

        // Add tooltips after translation
        $('[title]').tooltip();
        // Set the current locale in moment.js
        moment.locale([i18n.lng(), 'en'])

        // Activate current lang dropdown
        $('.locale a[data-i18n=\'nav.lang.' + i18n.lng() + '\']').parent().addClass('active');

        // Activate filter
        $('a.filter-popup').click(showFilterModal);
        
        // *******   Define hotkeys  *******
        // Dashboard
        $(document).bind('keydown', 'd', function(){
            window.location = appUrl + '/show/dashboard';
            return true;
        });
        
        // Client listing
        $(document).bind('keydown', 'c', function(){
            window.location = appUrl + '/show/listing/clients';
            return true;
        });
        
        // search
        $(document).bind('keydown', '/', function(){
            $('input[type="search"]').focus();
            return false;
        });

        // Trigger appReady
        $(document).trigger('appReady', [i18n.lng()]);
    });
});

$(window).on("hashchange", function (e) {
     loadHash();
})

// Update hash in url
var updateHash = function(e){
		var url = String(e.target)
		if(url.indexOf("#") != -1)
		{
			var hash = url.substring(url.indexOf("#"));
			// Save scroll position
			var yScroll=document.body.scrollTop;
			window.location.hash = '#tab_'+hash.slice(1);
			document.body.scrollTop=yScroll;
		}
	},
	loadHash = function(){
		// Activate correct tab depending on hash
		var hash = window.location.hash.slice(5);
		if(hash){
			$('.client-tabs a[href="#'+hash+'"]').tab('show');
		}
		else{
			$('.client-tabs a[href="#summary"]').tab('show');
		}
	},
    addMenuItem = function(conf){
        // Add menu item
        conf.menu = conf.menu || 'listing';
        conf.name = conf.name || 'no_name';
        conf.i18n = conf.i18n || '';
        conf.url = conf.url || appUrl + '/show/' + conf.menu + '/' + conf.name;
        $('ul.dropdown-menu.' + conf.menu)
            .append($('<li>')
                .append($('<a>')
                    .attr('href', conf.url)
                    .text(function(){
                        if(conf.i18n){
                            return i18n.t(conf.i18n);
                        }
                        return conf.name;
                    })));
    },
	addTab = function(conf){

		// Add tab link
		$('.client-tabs .divider')
			.before($('<li>')
				.append($('<a>')
					.attr('href', '#'+conf.id)
					.attr('data-toggle', 'tab')
					.on('show.bs.tab', function(){
						// We have to remove the active class from the
						// previous tab manually, unfortunately
						$('.client-tabs li').removeClass('active');
					})
					.on('shown.bs.tab', updateHash)
					.text(conf.linkTitle)));

		// Add tab
		$('div.tab-content')
			.append($('<div>')
				.attr('id', conf.id)
				.addClass('tab-pane')
				.append($('<h2>')
					.text(conf.tabTitle))
				.append(conf.tabContent));
	},
	removeTab = function(id){
		// remove tab
		$('#'+id).remove();
		$('.client-tabs [href=#'+id+']').parent().remove();
	}

var showFilterModal = function(e){

	e.preventDefault();
    
    var mgList = [];

	var updateGroup = function(){

		var checked = this.checked,
			settings = {
				filter: 'machine_group',
				value: $(this).data().groupid,
				action: checked ? 'remove' : 'add'
			}

		$.post(appUrl + '/unit/set_filter', settings, function(){
			// Update all
			$(document).trigger('appUpdate');
		})
	};
    
    var updateAll = function() {
        
        var checked = this.checked,
            settings = {
                filter: 'machine_group',
                value: mgList,
                action: checked ? 'clear' : 'add_all'
            }
            
        $.post(appUrl + '/unit/set_filter', settings, function(){
			// Update all
            $('#myModal .modal-body input[type=checkbox]').prop('checked', checked);
			$(document).trigger('appUpdate');
		})
    };

	// Get all business units and machine_groups
	var defer = $.when(
		$.getJSON(appUrl + '/unit/get_machine_groups')
		);

	// Render when all requests are successful
	defer.done(function(mg_data){

		// Set texts
		$('#myModal .modal-title')
			.empty()
			.append($('<i>')
				.addClass('fa fa-filter'))
			.append(' ' + i18n.t("filter.title"));
		$('#myModal .modal-body')
			.empty()
			.append($('<b>')
				.text(i18n.t("business_unit.machine_groups")));

		$('#myModal button.ok').text(i18n.t("dialog.close"));

		// Set ok button
		$('#myModal button.ok')
			.off()
			.click(function(){$('#myModal').modal('hide')});
        
        // Add check/uncheck all
        $('#myModal .modal-body')
            .append($('<div>')
                .addClass('checkbox')
                .append($('<label>')
                    .append($('<input>')
                        .change(updateAll)
                        .attr('type', 'checkbox'))
                    .append('Check/uncheck all')))
                    
		// Add machine groups
		$.each(mg_data, function(index, obj){
			if(obj.groupid !== undefined){
                mgList.push(obj.groupid);
				$('#myModal .modal-body')
					.append($('<div>')
						.addClass('checkbox')
						.append($('<label>')
							.append($('<input>')
								.data(obj)
								.prop('checked', function(){
									return obj.checked;
								})
								.change(updateGroup)
								.attr('type', 'checkbox'))
							.append(obj.name || 'No Name')))
			}
		});

		// Show modal
		$('#myModal').modal('show');

	});
}

// Delete machine ajax call
function delete_machine(obj)
{
	var row = obj.parents('tr');
	$.getJSON( obj.attr('href'), function( data ) {

		data.status = data.status || 'unknown';

		if(data.status == 'success')
		{
			// Animate slide up
			row.find('td')
			.animate({'padding-top': '0px', 'padding-bottom': '0px'}, {duration: 100})
			.wrapInner('<div style="display: block;" />')
			.parent()
			.find('td > div')
			.slideUp(600,function(){
				// After hide animation is done, redraw table
                var oTable = $('.table').DataTable();
                oTable.ajax.reload();
			});
		}
	  	else
	  	{
	  		alert(i18n.t('admin.delete_failed') + i18n.t(data.status) + ': '+data.message);
	  	}
	});
}

// Set/retrieve state data in localStorage
// This function is used by datatables
function state(id, data)
{
    // Create unique id for this page
    path = location.pathname + location.search

    // Strip host information and index.php
    path = path.replace(/.*index\.php\??/, '')

    // Strip serial number from detail page, we don't want to store
    // sorting information for every unique client
    path = path.replace(/(.*\/clients\/detail\/).+$/, '$1')

    // Strip inventory item from page, no unique sort per item
    path = path.replace(/(.*\/inventory\/items\/).+$/, '$1')

    // Append id to page path
    id = path + id

	if( data == undefined)
	{
	  // Get data
	  return JSON.parse( localStorage.getItem(id) );

	}
	else
	{
	  // Set data
	  localStorage.setItem( id, JSON.stringify(data) );
	}
}

// Debug function to dump js objects
function dumpj(obj)
  {
    type = typeof(obj)
    if(type == 'object')
    {
      var out = {}
      for (var key in obj) {
        type = typeof(obj[key])
        if ( type == 'object')
        {
          out[key] = 'object'
        }
        else{
          out[key] = obj[key];
        }
      }
    }
    else{
      out = obj
    }
    alert(JSON.stringify(out));
  }

// Filesize formatter (uses 1000 as base)
function fileSize(size, decimals){
	// Check if number
	if(!isNaN(parseFloat(size)) && isFinite(size)){
		if(size == 0){ return '0 B'}
		if(decimals == undefined){decimals = 0};
		var i = Math.floor( Math.log(size) / Math.log(1000) );
		return ( size / Math.pow(1000, i) ).toFixed(decimals) * 1 + ' ' + ['', 'K', 'M', 'G', 'T', 'P', 'E'][i] + 'B';
	}
}

// Convert human readable filesize to bytes (uses 1000 as base)
function humansizeToBytes(size) {
	var obj = size.match(/(\d+|[^\d]+)/g), res=0;
	if(obj) {
		sizes='BKMGTPE';
		var i = sizes.indexOf(obj[1][0]);
		if(i != -1) {
			res = obj[0] * Math.pow(1000, i);
		}
	}
	return res;
}

// Sort by date (used by D3 charts)
function sortByDateAscending(a, b) {
	// Dates will be cast to numbers automagically:
	return a.x - b.x;
}

// Plural formatter
String.prototype.pluralize = function(count, plural)
{
  if (plural == null)
    plural = this + 's';

  return (count == 1 ? this : plural)
}

