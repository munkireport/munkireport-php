<div class="row">

		<div class="col-lg-6">

		<h2 data-i18n="munkireport.errors_and_warnings"></h2>

				<pre id="munkireport-errors" class="hide alert alert-danger"></pre>
				<pre id="munkireport-warnings" class="hide alert alert-warning"></pre>

			<p><i data-i18n="no_errors_or_warnings" data-i18n="listing.loading" id="munkireport-no-errors"></i></p>

	</div><!-- </div class="col-lg-6"> -->

	<div class="col-lg-6">

		<h2>Munki</h2>
		<table class="table table-striped">
			<tr>
				<th data-i18n="version"></th>
				<td id="munki-version"></td>
			</tr>
			<tr>
				<th data-i18n="munkiinfo.softwarerepourl"></th>
				<td><div id="munkiinfo-SoftwareRepoURL"></div></td>
			</tr>
			<tr>
				<th data-i18n="munkiinfo.applecatalog"></th>
				<td><div id="munkiinfo-AppleCatalogURL"></div></td>
			</tr>
			<tr>
				<th data-i18n="munkiinfo.manifest"></th>
				<td id="munki-manifestname"></td>
			</tr>
			<tr>
				<th data-i18n="munkiinfo.localonlymanifest"></th>
				<td><div id="munkiinfo-LocalOnlyManifest"></div></td>
			</tr>
			<tr>
				<th data-i18n="munkireport.run_type"></th>
				<td id="munki-runtype"></td>
			</tr>
			<tr>
				<th data-i18n="munkiinfo.start"></th>
				<td id="munki-starttime"></td>
			</tr>
			<tr>
				<th data-i18n="munkiinfo.duration"></th>
				<td id="munki-duration"></td>
			</tr>
		</table>
		<button id="popoverId" class="btn btn-info btn-sm"><span data-i18n="munkireport.additional_info"></span></button>

	</div><!-- </div class="col-lg-6"> -->

<script>
$(document).on('appReady', function(){

	var table = $('<div>');

	$('#popoverId').click(function(e){

		$('#myModal .modal-title')
			.empty()
			.append(i18n.t("munkireport.additional_info"))
		$('#myModal .modal-body')
			.empty()
			.append(table);

		$('#myModal button.ok').text(i18n.t("dialog.close"));

		// Set ok button
		$('#myModal button.ok')
			.off()
			.click(function(){$('#myModal').modal('hide')});


		$('#myModal').modal('show');
	})

  $.getJSON(appUrl + '/module/munkiinfo/get_data/' + serialNumber, function(data){
    // These are single preferences
    $('#munkiinfo-SoftwareRepoURL').text(data['SoftwareRepoURL']);
    $('#munkiinfo-AppleCatalogURL').text(data['AppleCatalogURL']);
    $('#munkiinfo-LocalOnlyManifest').text(data['LocalOnlyManifest']);

    // Create table of all preferences
    var rows = ''
    for (key in data){
      rows = rows + '<tr><th>'+key+'</th><td>'+data[key]+'</td></tr>'
    }
      table.append('<center><a target="_blank" href="https://github.com/munki/munki/wiki/Preferences#supported-managedinstalls-keys">Munki Wiki - Supported Managedinstalls Keys</a></center>')
      .append($('<div>')
        .addClass('table-responsive')
        .append($('<table>')
          // .append('<caption>Additional Munki Info</caption>')
					.addClass('table table-striped')
					.append($('<tbody>')
						.append(rows))))
  });
});
</script>



  </div><!-- </div class="row"> -->

  <div class="row">

	<div class="col-lg-12">

		<h2><span data-i18n="managedinstalls.title"></span><span id="managedinstalls-statuslist"></span></h2>

			<table id="managedinstalls-table" class="table table-striped">
		      <thead>
		        <tr>
		          <th data-i18n="name"></th>
				  <th data-i18n="version"></th>
				  <th data-i18n="size"></th>
				  <th data-i18n="status"></th>
				  <th data-i18n="type"></th>
		        </tr>
		      </thead>
		      <tbody>
		      </tbody>
		    </table>

    </div><!-- </div class="col-lg-12"> -->

  </div><!-- </div class="row"> -->



<script>
$(document).on('appReady', function(e, lang) {


	// Get managedinstalls data
	$.getJSON(appUrl + '/module/managedinstalls/get_data/' + serialNumber, function(data){

		var dataSet = [],
			statusList = {
				installed: 0,
				install_succeeded: 0,
				install_failed: 0,
				pending_install: 0,
				removed: 0,
				pending_removal: 0,
				uninstalled: 0,
				uninstall_failed: 0
			};

		$.each(data, function(index, val){
			dataSet.push([
				val.display_name,
				val.version,
				val.size,
				val.status,
				val.type
			]);
			statusList[val.status] ++;
		});

		// Show statusList
		$('#managedinstalls-statuslist').empty();
		for (var prop in mr.statusFormat) {
			if(statusList[prop]){
				var format = mr.statusFormat[prop];
				$('#managedinstalls-statuslist')
					.append(' ')
					.append($('<button>')
						.addClass('btn btn-xs')
						.addClass('btn-' + format.type)
						.text(prop + ' ' + statusList[prop])
						.data('prop', prop)
						.click(function(){
							var table = $('#managedinstalls-table').DataTable();
							table.search( $(this).data('prop') ).draw();
						})
					);
			}
		}

		// Initialize datatables
		$('#managedinstalls-table tbody').empty();
		$('#managedinstalls-table').dataTable({
			data: dataSet,
			serverSide: false,
			order: [0,'asc'],
			createdRow: function( nRow, aData, iDataIndex ) {
				// make filesize human readable
				var size=$('td:eq(2)', nRow).html();
				$('td:eq(2)', nRow).html(fileSize(size * 1024, 0));
				// add status labels
					var status = $('td:eq(3)', nRow).text();
					if(mr.statusFormat[status]){
						$('td:eq(3)', nRow).empty()
							.append($('<span>')
								.addClass('label')
								.addClass('label-' + mr.statusFormat[status].type)
								.text(status));
					}

			}
		});
	});

	// Get mwa2Link
	mr.mwa2Link = "<?=conf('mwa2_link')?>";

	// Get munkireport data TODO: move to client_detail.js
    $.getJSON(appUrl + '/module/munkireport/get_data/' + serialNumber, function(data){
		// TODO: check for errors
		$.each(data, function(prop, val){
			$('#munki-'+prop).html(val);
		});

		// Get mwa2 link
		if(mr.mwa2Link){
			$('#munki-manifestname').append(' <a class="btn btn-xs btn-info" target="_blank" href="'+mr.mwa2Link+'/manifests/#'+$('#munki-manifestname').text()+'"><i class="fa fa-arrow-circle-right"></span></a>');
			$('#munki-manifestname a').tooltip(
				{title: i18n.t('mwa.mwa_link')}
			);
		}

		// Set times
		var starttime = moment(data.starttime, "YYYY-MM-DD HH:mm:ss Z"),
			endtime = moment(data.endtime, "YYYY-MM-DD HH:mm:ss Z"),
			duration = endtime.diff(starttime, 'seconds');

		$('#munki-starttime').html('<span title="'+data.starttime+'"></span>'+starttime.fromNow());
		$('#munki-duration').html(moment.duration(duration, "seconds").humanize());

		// Handle Errors and Warnings
		if (data.errors > 0) {
			var errors = JSON.parse(data.error_json);
			$('#munkireport-errors')
				.removeClass('hide')
				.html(errors.join("\n"));
			$('#munkireport-no-errors').addClass('hide');
		}
		if (data.warnings > 0) {
			var warnings = JSON.parse(data.warning_json);
			$('#munkireport-warnings')
				.removeClass('hide')
				.html(warnings.join("\n"));
			$('#munkireport-no-errors').addClass('hide');
		}


    });

});
</script>
