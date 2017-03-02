	<?php $this->view('partials/head')?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Bluetooth_model;
?>

<div class="container">

	<div class="row">
		<div class="col-lg-12">

			<h3><span data-i18n="listing.bluetooth.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>

			<table class="table table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
					<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
					<th data-i18n="listing.username" data-colname='reportdata.long_username'></th>
					<th data-i18n="listing.bluetooth.device_type" data-colname='bluetooth.device_type'></th> 
					<th data-i18n="listing.bluetooth.battery_percent" data-colname='bluetooth.battery_percent'></th> 
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="5" class="dataTables_empty">Loading data from server</td>
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
	var mySort = [], // Initial sort
	hideThese = [], // Hidden columns
	col = 0, // Column counter
	columnDefs = [{ visible: false, targets: hideThese }]; //Column Definitions

	$('.table th').map(function(){

	columnDefs.push({name: $(this).data('colname'), targets: col});

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
	  ajax: {
		  url: appUrl + '/datatables/data',
		  type: "POST",
		  data: function(d){
	  
			  d.where = [
				  {
					  table: 'bluetooth',
					  column: 'device_type',
					  value: 'bluetooth_power',
					  operator: '!='
				  }
			  ];

		}

	  },
	  
	  dom: mr.dt.buttonDom,
	  buttons: mr.dt.buttons,
	  order: mySort,
	  columnDefs: columnDefs,
	  createdRow: function( nRow, aData, iDataIndex ) {
		// Update name in first column to link
		var name=$('td:eq(0)', nRow).html();
		if(name == ''){name = "No Name"};
		var sn=$('td:eq(1)', nRow).html();
		var link = mr.getClientDetailLink(name, sn, '#tab_summary');
		$('td:eq(0)', nRow).html(link);

		// Change `bluetooth_power` from database entry to i18n display name
		// Battery Percent to On or Off instead of integer 0/1
		// Format the battery percentage to contain the colored bar
		var device_type=$('td:eq(3)', nRow).html();
		var battery_percent=$('td:eq(4)', nRow).html();
		if (device_type == 'bluetooth_power'){
			device_type = i18n.t('listing.bluetooth.bluetooth_power')
			battery_percent = battery_percent == 1 ? '<span class="label label-success">'+i18n.t('on')+'</span>' :
			(battery_percent === '0' ? '<span class="label label-danger">'+i18n.t('off')+'</span>' : '')
			$('td:eq(4)', nRow).html(battery_percent)
			$('td:eq(3)', nRow).html(device_type)
		}
		else {
			// Format keyboard/mouse/trackpad percentage
			var in_device=$('td:eq(4)', nRow).html();
			var cls = in_device < 15 ? 'danger' : (in_device < 40 ? 'warning' : 'success');
			$('td:eq(4)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+in_device+'%;">'+in_device+'%</div></div>');
			$('td:eq(3)', nRow).html(i18n.t('bluetooth.' + device_type));
		}
	}
	} );

	// Use hash as searchquery
	if(window.location.hash.substring(1))
	{
		oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
	}

} );
</script>

<?php $this->view('partials/foot'); ?>
