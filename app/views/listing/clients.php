<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Warranty_model;
new Disk_report_model;
new Reportdata_model;
new Munkireport_model;
?>

<div class="container">

  <div class="row">

	<div class="col-lg-12">

	  <h3>Clients report <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine#computer_name'>Name</th>
			<th data-i18n="serial" data-colname='machine#serial_number'>Serial</th>
			<th data-i18n="listing.username" data-colname='reportdata#long_username'>Username</th>
			<th data-colname='machine#os_version'>OS</th>
			<th data-colname='machine#machine_name'>Type</th>
			<th data-colname='warranty#status'>Warranty status</th>
			<th data-colname='diskreport#Percentage'>Disk</th>
			<th data-colname='reportdata#uptime'>Uptime</th>
			<th data-colname='reportdata#timestamp'>Check-in</th>
			<th data-colname='munkireport#manifestname'>Manifest</th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="10" class="dataTables_empty"></td>
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

		// Get column names from data attribute
		var myCols = [];
		$('.table th').map(function(){
			  myCols.push({'mData' : $(this).data('colname')});
		});
		var oTable = $('.table').dataTable( {
			"sAjaxSource": "<?php echo url('datatables/data'); ?>",
			"aoColumns": myCols,
			"fnCreatedRow": function( nRow, aData, iDataIndex ) {
				// Update name in first column to link
				var name=$('td:eq(0)', nRow).html();
				if(name == ''){name = "No Name"};
				var sn=$('td:eq(1)', nRow).html();
				var link = get_client_detail_link(name, sn, '<?php echo url(); ?>/');
				$('td:eq(0)', nRow).html(link);

				// Format disk usage
				var disk=$('td:eq(6)', nRow).html();
				var cls = disk > 90 ? 'danger' : (disk > 80 ? 'warning' : 'success');
				$('td:eq(6)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+disk+'%;">'+disk+'%</div></div>');

				// Format date
				var checkin = parseInt($('td:eq(8)', nRow).html());
				var date = new Date(checkin * 1000);
				$('td:eq(8)', nRow).html(moment(date).fromNow());

				// Format OS Version
				var osvers = integer_to_version($('td:eq(3)', nRow).html());
				$('td:eq(3)', nRow).html(osvers);

				// Format uptime
				var uptime = parseInt($('td:eq(7)', nRow).html());
				if(uptime == 0) {
				  $('td:eq(7)', nRow).html('')
				}
				else
				{
				  $('td:eq(7)', nRow).html('<span title="Booted: ' + moment(date).subtract( uptime, 'seconds').format('llll') + '">' + moment().subtract(uptime, 'seconds').fromNow(true) + '</span>');
				}
			},
			"fnServerParams": function ( aoData ) {

				// Hook in serverparams to change search
				// Convert array to dict
				var out = {}
				for (var i = 0; i < aoData.length; i++) {
					out[aoData[i]['name']] =  aoData[i]['value']
				}

				// Look for 'osversion' statement 
				if(out.sSearch.match(/^\d+\.\d+(\.(\d+)?)?$/))
				{

					var search = out.sSearch.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');

					// Override global search
					aoData.push( { "name": "sSearch", "value": search } );

				}
			}			    
		});
	});
</script>

<?php $this->view('partials/foot'); ?>
