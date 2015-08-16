<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Disk_report_model;
new Reportdata_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3>Disk report <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine#computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata#serial_number'></th>
		        <th data-i18n="listing.username" data-colname='reportdata#long_username'></th>
		        <th data-i18n="storage.mountpoint" data-colname='diskreport#MountPoint'></th>
		        <th data-i18n="storage.volume_type" data-colname='diskreport#VolumeType'></th>
		        <th data-i18n="storage.percentage" data-sort='desc' data-colname='diskreport#Percentage'></th>
		        <th data-i18n="storage.free" data-colname='diskreport#FreeSpace'></th>
		        <th data-i18n="storage.total_size" data-colname='diskreport#TotalSize'></th>
		    	<th data-i18n="storage.smartstatus" data-colname='diskreport#SMARTStatus'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="9" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

	$(document).on('appUpdate', function(e){

		var oTable = $('.table').DataTable();
		oTable.ajax.reload();
		return;

	});		

	$(document).on('appReady', function(e, lang) {

		// Get modifiers from data attribute
		var myCols = [], // Colnames
			mySort = [], // Initial sort
			hideThese = [], // Hidden columns
			col = 0; // Column counter

		$('.table th').map(function(){

			  myCols.push({'mData' : $(this).data('colname')});

			  if($(this).data('sort'))
			  {
			  	mySort.push([col, $(this).data('sort')])
			  }

			  if($(this).data('hide'))
			  {
			  	hideThese.push(col);
			  }

			  col++
		});

	    oTable = $('.table').dataTable( {
	        "sAjaxSource": appUrl + '/datatables/data',
	        "aaSorting": mySort,
	        "aoColumns": myCols,
	        "aoColumnDefs": [
	        	{ 'bVisible': false, "aTargets": hideThese }
			],
	        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = get_client_detail_link(name, sn, appUrl + '/', '#tab_storage-tab');
	        	$('td:eq(0)', nRow).html(link);
	        	
	        	// is SSD ?
	        	var volumeType=$('td:eq(4)', nRow).html();
	        	$('td:eq(4)', nRow).html(volumeType.toUpperCase())

	        	// Format disk usage
	        	var disk=$('td:eq(5)', nRow).html();
	        	var cls = disk > 90 ? 'danger' : (disk > 80 ? 'warning' : 'success');
	        	$('td:eq(5)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+disk+'%;">'+disk+'%</div></div>');
	        	
	        	// Format filesize
	        	var fs=$('td:eq(6)', nRow).html();
	        	$('td:eq(6)', nRow).addClass('text-right').html(fileSize(fs, 0));

	        	// Format filesize
	        	var fs=$('td:eq(7)', nRow).html();
	        	$('td:eq(7)', nRow).addClass('text-right').html(fileSize(fs, 0));
	        	
	        	// Alert on SMART failures
	        	var smartstatus=$('td:eq(8)', nRow).html();
	        	smartstatus = smartstatus == 'Failing' ? '<span class="label label-danger">Failing</span>' : 
	        		(smartstatus)
	        	$('td:eq(8)', nRow).html(smartstatus)

		    },
		    "fnServerParams": function ( aoData ) {

		      	// Hook in serverparams to change search
		      	// Convert array to dict
		      	var out = {}
				for (var i = 0; i < aoData.length; i++) {
					out[aoData[i]['name']] =  aoData[i]['value']
				}

				// Look for 'between' statement todo: make generic
				if(out.sSearch.match(/^\d+GB freespace \d+GB$/))
				{
					// Clear global search
					aoData.push( { "name": "sSearch", "value": "" } );

					// Add column specific search
					aoData.push( { 
						"name": "sSearch_6", 
						"value": out.sSearch.replace(/(\d+GB) freespace (\d+GB)/, function(m, from, to){return ' BETWEEN ' + humansizeToBytes(from) + ' AND ' + humansizeToBytes(to)})
					} );
					//dumpj(out)
				}

				// Look for a bigger/smaller/equal statement
				if(out.sSearch.match(/^freespace [<>=] \d+GB$/))
				{
					// Clear global search
					aoData.push( { "name": "sSearch", "value": "" } );

					// Add column specific search
					aoData.push( { "name": "sSearch_6", "value": out.sSearch.replace(/.*([<>=] )(\d+GB)$/, function(m, o, content){return o + humansizeToBytes(content)}) } );
					//dumpj(out)
				}
		    }
	    } );
	} );
</script>

<?php $this->view('partials/foot'); ?>
