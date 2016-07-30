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
			<th data-i18n="listing.computername" data-colname='machine.computer_name'>Name</th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'>Serial</th>
			<th data-i18n="listing.username" data-colname='reportdata.long_username'>Username</th>
      <th data-colname='machine.os_version'>OS</th>
			<th data-i18n="buildversion" data-colname='machine.buildversion'>Type</th>
      <th data-colname='machine.machine_name'>Type</th>
			<th data-colname='warranty.status'>Warranty status</th>
			<th data-colname='reportdata.uptime'>Uptime</th>
			<th data-colname='reportdata.timestamp'>Check-in</th>
			<th data-colname='munkireport.manifestname'>Manifest</th>
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
		var columnDefs = [], //Column Definitions
            col = 0; // Column counter
		$('.table th').map(function(){
            columnDefs.push({name: $(this).data('colname'), targets: col});
            col++;
		});
		var oTable = $('.table').dataTable( {
            ajax: {
                url: "<?php echo url('datatables/data'); ?>",
                type: "POST",
                data: function( d ){
                    // Look for 'osversion' statement
                    if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                        var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                        d.search.value = search;
                    }
                  
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            columnDefs: columnDefs,
			createdRow: function( nRow, aData, iDataIndex ) {
				// Update name in first column to link
				var name=$('td:eq(0)', nRow).html();
				if(name == ''){name = "No Name"};
				var sn=$('td:eq(1)', nRow).html();
				var link = mr.getClientDetailLink(name, sn);
				$('td:eq(0)', nRow).html(link);

				// Format date
				var checkin = parseInt($('td:eq(8)', nRow).html());
				var date = new Date(checkin * 1000);
				$('td:eq(8)', nRow).html(moment(date).fromNow());

				// Format OS Version
				var osvers = mr.integerToVersion($('td:eq(3)', nRow).html());
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
			}
		});
	});
</script>

<?php $this->view('partials/foot'); ?>
