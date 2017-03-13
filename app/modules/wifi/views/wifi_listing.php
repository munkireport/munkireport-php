<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new wifi_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="wifi.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		      	<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		      	<th data-colname='wifi.ssid'>SSID</th>
		      	<th data-colname='wifi.bssid'>BSSID</th>
		      	<th data-i18n="wifi.listing.state" data-colname='wifi.state'></th>
		      	<th data-i18n="wifi.listing.lasttx" data-colname='wifi.lasttxrate'></th>
		      	<th data-i18n="wifi.listing.maxtx" data-colname='wifi.maxrate'></th>
		      	<th data-i18n="wifi.listing.channel" data-colname='wifi.channel'></th>
		      	<th data-colname='wifi.agrctlrssi'>RSSI</th>
		      	<th data-i18n="wifi.listing.noise" data-colname='wifi.agrctlnoise'></th>
		      	<th data-i18n="wifi.listing.authtype" data-colname='wifi.x802_11_auth'></th>
		      	<th data-i18n="wifi.listing.linkauthtype" data-colname='wifi.link_auth'></th>
		      	<th data-i18n="wifi.listing.apmode" data-colname='wifi.op_mode'></th>
		      	<th data-colname='wifi.mcs'>MCS</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="14" class="dataTables_empty"></td>
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
		var columnDefs = [],
            col = 0; // Column counter
		$('.table th').map(function(){
              columnDefs.push({name: $(this).data('colname'), targets: col});
              col++;
		});
	    oTable = $('.table').dataTable( {
	        columnDefs: columnDefs,
	        ajax: {
                url: appUrl + '/datatables/data',
                type: "POST"
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
	        createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = mr.getClientDetailLink(name, sn);
	        	$('td:eq(0)', nRow).html(link);

	        }
	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
