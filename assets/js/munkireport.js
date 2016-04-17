// Global functions

// Global munkireport object
var mr = {
        dt:{}
    };

$(document).on('appReady', function(e, lang) {
    
    addMenuItem({
        menu: 'admin',
        i18n: 'notification.menu_link',
        url: appUrl + '/module/notification/manage'
    }); 
});

$( document ).ready(function() {
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
	}

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

		// Add machine groups
		$.each(mg_data, function(index, obj){
			if(obj.groupid !== undefined){
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






// Integer or integer string OS Version to semantic OS version
function integer_to_version(osvers)
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
}

// Get client detail link
function get_client_detail_link(name, sn, baseurl, hash)
{
	hash = (typeof hash === "undefined") ? "" : hash;
	return '<div class="machine">\
    		<a class="btn btn-default btn-xs" href="'+baseurl+'clients/detail/'+sn+hash+'">'+name+'</a>\
    		<a href="'+baseurl+'manager/delete_machine/'+sn+'" class="btn btn-xs btn-danger">\
    		<i class="fa fa-times"></i></a></div>';
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
				oTable.fnDraw();
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

// Draw a formatted graph with flotr2
// Handle resize events
// url: the url where the data comes from
// id: the jquery string for the domelement (eg #barchart)
// options: the flotr2 options for a particular chart
// parms: extra parameters to send to the server
function drawGraph(url, id, options, parms)
{
	$.getJSON(url, {'req':JSON.stringify(parms)}, function(data) {

		// Create a tick array to get labels on ticks
		// (a modification to flotr2.js)
		if(options.yaxis && options.yaxis.tickFormatter)
		{
			options.yaxis.ticks = data.map(function(x){
				return [x.data[0][1]]
			})
		}
		if(options.callBack){options.callBack(data)}

		options.colors = makeColorGradient(data.length, options.reverseColors);
		options.resolution = getScale();

		// preventDefault by default for mobile events.  Turn off to enable scroll.
		options.preventDefault = false;

		//dumpj(options.colors)
		chartObjects[id] = Flotr.draw($(id)[0], data, options);

		var myWidth = $(id).width()
		// Bind resize
		$(window).resize(function() {
			if( $(id).width() != myWidth)
			{
				Flotr.draw($(id)[0], data, options);
				myWidth = $(id).width()
			}
		});
	});
}

// Get correct scale for screen resolution
// Adapted from http://www.html5rocks.com/en/tutorials/canvas/hidpi/
var scale = 0;
function getScale()
{
	if( scale == 0)
	{
		var canvas = document.createElement('canvas'),
		context = canvas.getContext('2d'),

		devicePixelRatio = window.devicePixelRatio || 1,
	    backingStoreRatio = context.webkitBackingStorePixelRatio ||
	                        context.mozBackingStorePixelRatio ||
	                        context.msBackingStorePixelRatio ||
	                        context.oBackingStorePixelRatio ||
	                        context.backingStorePixelRatio || 1;

	    scale = devicePixelRatio / backingStoreRatio;
	}

	return scale

}

// Generate nice colors
// Adapted from http://krazydad.com/tutorials/makecolors.php
if(typeof window.makeColorGradient !== 'function')
{
	window.makeColorGradient = function(len, reverse)
	{
		var center = 128,
			width = 127,
			frequency1 = .4,
			frequency2 = frequency1,
			frequency3 = frequency1;
		var out = []
		if(reverse)
		{
			var phase1 = 2,
				phase2 = phase1 - 2,
				phase3 = phase1 - 4;
		}
		else
		{
			var phase1 = -2,
				phase2 = phase1 + 2,
				phase3 = phase1 + 4;
		}
		for (var i = 0; i < len; ++i)
		{
		   var red = Math.round(Math.sin(frequency1*i + phase1) * width + center);
		   var grn = Math.round(Math.sin(frequency2*i + phase2) * width + center);
		   var blu = Math.round(Math.sin(frequency3*i + phase3) * width + center);
		   out.push('rgb('+red+','+grn+','+blu+')')
		}

		return out
	}
}

// Global variables
var chartObjects = {}, // Holds instantiated chart objects
	barOptions = {

	    	bars: {
	            show: true,
	            lineWidth: 0,
	            fillOpacity: 0.8,
	            barWidth: 0.9,
	            lineWidth: 0
			},
			markers: {
				show: true,
				fontSize: 9,
				position: 'ct'
			},
			xaxis:
			{
				showLabels: false
			},
			yaxis: {
				min: 0
			},
			grid:
			{
				verticalLines : false
			},
		    legend: {
				position : 'ne',
				backgroundColor: 'white',
				outlineColor: 'white'
			},
			shadowSize: 0

	    },
	    horBarOptions = {

	    	bars: {
	            show: true,
	            lineWidth: 0,
	            fillOpacity: 0.8,
	            barWidth: 0.9,
	            horizontal: true
			},
			markers: {
				show: true,
				fontSize: 10,
				position: 'm',
				labelFormatter: function(obj){
					return (Math.round(obj.x*100)/100)+'';
				}
			},
			yaxis: {},
			xaxis: {
				min: 0
			},
			grid:
			{
		      horizontalLines : false,
			},
		    legend: {
				position : 'ne',
				backgroundColor: 'white',
				outlineColor: 'white'
			},
			shadowSize: 0

	    },
		pieOptions = {

	        pie: {
	            show: true,
	            explode: 5,
	            sizeRatio: .9 / getScale(), // Bug in flotr2
	            labelRadius: 1/3,
	            labelFormatter: function(total, value) {
					return "<div style='font-size:150%; text-align:center; padding:2px; color:white;'>" + value + "</div>";
				}

	        },
	        shadowSize: 0,
	        grid : {
		      verticalLines : false,
		      horizontalLines : false,
		      outlineWidth: 0
		    },
			xaxis : { showLabels : false },
		    yaxis : { showLabels : false },
		    legend: {
				position : 'ne',
				backgroundColor: 'white',
				outlineColor: 'white'
			},
	    };
