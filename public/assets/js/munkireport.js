// Global functions

// Load theme files
mr.loadTheme();

$(document).on('appReady', function(e, lang) {

    $('html.no-js').removeClass('no-js');

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
        menu: 'admin',
        i18n: 'system.database.menu_link',
        url: appUrl + '/system/show/database'
    });

    addMenuItem({
        menu: 'admin',
        i18n: 'widget.gallery',
        url: appUrl + '/system/show/widget_gallery'
    });

    // Add list link
    $('list-link').each(function( index ){
        var url = appUrl + $(this).data('url');
        $(this).after('<a href="'+url+'" class="btn btn-xs pull-right"><i class="fa fa-list"></i></a>');
        $(this).remove();
    });

});


$( document ).ready(function() {


    // Theme switcher
    $('a[data-switch]').on('click', function(){
        mr.setPref('theme', $(this).data('switch'));
        mr.loadTheme();
   });

   // Initialize i18n
   $.i18n.init({
       debug: mr.debug,
       useLocalStorage: false,
       fallbackLng: 'en',
       useDataAttrOptions: true,
       getAsync: false,
       resStore: {}
   });
    
   var lang = $.i18n.lng();

   // Load locales
   var localeUrl = '/locale/get/' + lang
   if(typeof loadAllModuleLocales !== 'undefined'){
	var localeUrl = localeUrl + '/all_modules'
   }
   $.when(
       $.getJSON( appUrl + localeUrl)
   )
    .fail(function(){
        alert('failed to load locales, please check for syntax errors');
    })
   .done(function( data ){

        i18n.addResourceBundle('en', 'translation', data.fallback_main);
        i18n.addResourceBundle('en', 'translation', data.fallback_module);
        i18n.addResourceBundle(lang, 'translation', data.lang_main);
        i18n.addResourceBundle(lang, 'translation', data.lang_module);

        $('body').i18n();

        // Sort menus
        mr.sortMenu('ul.report');
        mr.sortMenu('ul.listing');
        mr.sortMenu('ul.client-tabs');

        // Put summary on top
        $('ul.client-tabs').prepend($('ul.client-tabs a[href="#summary"]').closest('li'));

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
        // Client listing
        mr.setHotKey('c', appUrl + '/show/listing/reportdata/clients');

        // Search
        $(document).bind('keydown', '/', function(){
            $('input[type="search"]').focus();
            return false;
        });

        // Filter popup
        $(document).bind('keydown', 'f', function(){
            document.getElementById("filter-popup").click();
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
