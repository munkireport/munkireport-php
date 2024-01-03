// Global functions

// Load theme files
mr.loadTheme();

$(document).on('appReady', function(e, lang) {
    $('html.no-js').removeClass('no-js');
});

$(function () {
  // client_details tab
  $('.client-tabs a.dropdown-item').on('click', function(e) {
    e.preventDefault();
    $(e.currentTarget).tab('show');
  }).on('shown.bs.tab', function(e) {
    // bs.tab is not keeping track of the active link correctly.
    $('.client-tabs a.active').removeClass('active');
    $(e.currentTarget).addClass('active');
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

var loadHash = function() {
  // Intentionally empty so older scripts don't throw an Exception
};

var showFilterModal = function(e) {
	e.preventDefault();

	var mgList = [];
	var updateGroup = function() {

		var checked = this.checked,
			settings = {
				filter: 'machine_group',
				value: $(this).data().groupid,
				action: checked ? 'remove' : 'add'
			}

      $.post(appUrl + '/filter/set_filter', settings, function() {
        // Update all
        $(document).trigger('appUpdate');
      })
	};
    
  var updateArchived = function() {
    var checked = this.checked,
      settings = {
        filter: 'archived',
        value: 'yes',
        action: checked ? 'remove' : 'add'
      }

    $.post(appUrl + '/filter/set_filter', settings, function() {
      // Update all
      enableDisableArchivedOnly();

      $(document).trigger('appUpdate');
    })
  }

  var updateArchivedOnly = function() {
    var checked = this.checked,
      settings = {
        filter: 'archived_only',
        value: 'yes',
        action: checked ? 'add' : 'remove'
      }

    $.post(appUrl + '/filter/set_filter', settings, function(){
      // Update all
      $(document).trigger('appUpdate');
    })
  }

  // Enable or disable depending on state of archived
  var enableDisableArchivedOnly = function(){
      if($('#archived').prop('checked'))
      {
          // enable checkbox
          $('#archived_only input').prop('disabled', false)
          $('#archived_only').removeClass("text-muted");

      }else{
          $('#archived_only input').prop('disabled', true);
          $('#archived_only').addClass("text-muted");
      }
  }


  var updateAll = function() {
      var checked = this.checked,
          settings = {
              filter: 'machine_group',
              value: mgList,
              action: checked ? 'clear' : 'add_all'
          }

      $.post(appUrl + '/filter/set_filter', settings, function(){
        // Update all
        $('#myModal input.mgroups[type=checkbox]').prop('checked', checked);
        $(document).trigger('appUpdate');
      })
  };

	// Get all business units and machine_groups
	var defer = $.when(
		$.getJSON(appUrl + '/unit/get_machine_groups'),
		$.getJSON(appUrl + '/filter/get_filter')
    );

	// Render when all requests are successful
	defer.done(function(mg_data, filter_data){

        var mg_data = mg_data[0],
            filter_data = filter_data[0],
            modal_title = $('#myModal .modal-title'),
            modal_body = $('#myModal .modal-body');

		// Set title
		modal_title.empty();
		modal_title.append($('<i class="fa fa-filter">'))
               .append(' ' + i18n.t("filter.title"));

    // empty body
    modal_body.empty()
    var container = $('<div class="container">').appendTo(modal_body);
    var form = $('<form>').appendTo(container);
    var archive_row = $('<div class="row">').appendTo(form);
    var archive_col = $('<div class="col-sm">').appendTo(archive_row);

    // Add archive filters
    var mg_archive = $('<div class="form-check form-check-inline machine_groups">').appendTo(archive_col);
    $('<input class="form-check-input" id="archived" type="checkbox">')
      .change(updateArchived)
      .prop('checked', function () {
          return filter_data['archived'].length == 0;
      })
      .appendTo(mg_archive);
    $('<label class="form-check-label" for="archived">')
      .append(i18n.t('filter.show_archived')).appendTo(mg_archive);

    var mg_archiveonly = $('<div class="form-check form-check-inline machine_groups">').appendTo(archive_col);
    $('<input class="form-check-input" id="archived_only_checkbox" type="checkbox">')
      .change(updateArchivedOnly)
      .prop('checked', function () {
        return filter_data['archived_only'].length > 0;
      })
      .appendTo(mg_archiveonly);
    $('<label id="archived_only" class="form-check-label" for="archived_only_checkbox">')
      .append(i18n.t('filter.only_show_archived')).appendTo(mg_archiveonly);


    var mg_row = $('<div class="row">').appendTo(form);
    var mg_col = $('<div class="col-sm">').appendTo(mg_row);

    $('<h5 class="mt-2">').text(i18n.t("business_unit.machine_groups")).appendTo(mg_col);

    // Add check/uncheck all
    var mg_checkall = $('<div class="form-check">').appendTo(mg_col);
    $('<input class="form-check-input" id="machine_group_toggle_all" type="checkbox">')
      .change(updateAll)
      .appendTo(mg_checkall);
    $('<label class="form-check-label" for="machine_group_toggle_all">')
      .text('Check/uncheck all').appendTo(mg_checkall);

		// Add machine groups
		$.each(mg_data, function(index, obj){
			if(obj.groupid !== undefined){
			  mgList.push(obj.groupid);

			  var chk = $('<div class="form-check">').appendTo(mg_col);
			  $('<input class="form-check-input mgroups" type="checkbox">')
          .data(obj)
          .attr('id', 'mg' + index)
          .prop('checked', function(){
            return obj.checked;
          })
          .change(updateGroup)
          .appendTo(chk);

        $('<label class="form-check-label">')
          .text(obj.name || 'No Name')
          .appendTo(chk);
			}
		});

		$('#myModal button.ok').text(i18n.t("dialog.close"));

		// Set ok button
		$('#myModal button.ok')
			.off()
            .click(function(){$('#myModal').modal('hide')});

        // Enable/disable archivedOnly depending on state of #archived
        enableDisableArchivedOnly();

		// Show modal
		$('#myModal').modal('show');

	});
}

// Delete machine ajax call
function delete_machine(obj)
{
    var row = obj.parents('tr');
    $.ajax({
        url: obj.attr('href'),
        method: 'DELETE',
        dataType: 'json'
    })
    .done(function(data) {
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
    })
    .fail(function(data){
        alert(i18n.t('admin.delete_failed') + i18n.t('error') + ': '+data.responseJSON['error']);
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
