<div class="row">

		<div class="col-lg-6">

		<h2 data-i18n="munki.errors_and_warnings"></h2>
			
				<pre id="munkireport-errors" class="hide alert alert-danger"></pre>
				<pre id="munkireport-warnings" class="hide alert alert-warning"></pre>

			<p><i data-i18n="no_errors_or_warnings" id="munkireport-no-errors">Loading</i></p>

	</div><!-- </div class="col-lg-6"> -->

	<div class="col-lg-6">

		<h2>Munki</h2>
		<table class="table table-striped">
			<tr>
				<th>Version</th>
				<td id="munki-version"></td>
			</tr>
			<tr>
				<th>SoftwareRepoURL</th>
				<td><div id="munkiinfo-SoftwareRepoURL"></div></td>
			</tr>
			<tr>
				<th>AppleCatalogURL</th>
				<td><div id="munkiinfo-AppleCatalogURL"></div></td>
			</tr>
			<tr>
				<th>Manifest</th>
				<td id="munki-manifestname"></td>
			</tr>
			<tr>
				<th>LocalOnlyManifest</th>
				<td><div id="munkiinfo-LocalOnlyManifest"></div></td>
			</tr>
			<tr>
				<th>Run Type</th>
				<td id="munki-runtype"></td>
			</tr>
			<tr>
				<th>Start</th>
				<td id="munki-starttime"></td>
			</tr>
			<tr>
				<th>Duration</th>
				<td id="munki-duration"></td>
			</tr>
		</table>
		<button id="popoverId" class="btn btn-info btn-sm"><span data-i18n="munki.additional_info"></span></button>

	</div><!-- </div class="col-lg-6"> -->

  



<script>
$(document).on('appReady', function(){
	
	var table = $('<div>');
	
	$('#popoverId').click(function(e){
		
		$('#myModal .modal-title')
			.empty()
			.append(i18n.t("munki.additional_info"))
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
		
		<h2 data-i18n="managedinstalls.title"></h2>
		
			<table id="managedinstalls-table" class="table table-striped">
		      <thead>
		        <tr>
		          <th>Name</th>
		          <th>Size</th>
				  <th>Status</th>
				  <th>Type</th>
		        </tr>
		      </thead>
		      <tbody>
		      </tbody>
		    </table>
			
    </div><!-- </div class="col-lg-12"> -->

  </div><!-- </div class="row"> -->



<script>
$(document).on('appReady', function(e, lang) {
	

	$.getJSON(appUrl + '/module/managedinstalls/get_data/' + serialNumber, function(data){
		
		var dataSet = [];
		$.each(data, function(index, val){
			dataSet.push([
				val.display_name, 
				val.size,
				val.status,
				val.type
			])
		});
				
		// Initialize datatables
		$('#managedinstalls-table tbody').empty();
		$('#managedinstalls-table').dataTable({
			data: dataSet,
			serverSide: false,
			order: [0,'asc'],
			createdRow: function( nRow, aData, iDataIndex ) {
				// make filesize human readable
				var size=$('td:eq(1)', nRow).html();
				$('td:eq(1)', nRow).html(fileSize(size, 1));
			}
		});
	});
    
	// Get munkireport data TODO: move to client_detail.js
    $.getJSON(appUrl + '/module/munkireport/get_data/' + serialNumber, function(data){
		// TODO: check for errors
		$.each(data, function(prop, val){
			$('#munki-'+prop).html(val);
		});
		
		// Set times
		var starttime = moment(data.starttime, "YYYY-MM-DD HH:mm:ss Z"),
			endtime = moment(data.endtime, "YYYY-MM-DD HH:mm:ss Z"),
			duration = endtime.diff(starttime, 'seconds');

		$('#munki-starttime').html(starttime.fromNow());
		$('#munki-duration').html(moment.duration(duration, "seconds").humanize());
		
		// Handle Errors and Warnings
		if (data.errors) {
			var errors = JSON.parse(data.error_json);
			console.log(errors);
			$('#munkireport-errors')
				.removeClass('hide')
				.html(errors.join("\n"));
			$('#munkireport-no-errors').addClass('hide');
		}
		if (data.warnings) {
			var warnings = JSON.parse(data.warning_json);
			$('#munkireport-warnings')
				.removeClass('hide')
				.html(warnings.join("\n"));
			$('#munkireport-no-errors').addClass('hide');
		}


    });

});
</script>