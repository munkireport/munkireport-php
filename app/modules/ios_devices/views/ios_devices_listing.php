<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Ios_devices_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
	<h3><span data-i18n="ios_devices.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="ios_devices.serial" data-colname='ios_devices.serial'></th>
		        <th data-i18n="ios_devices.device_class" data-colname='ios_devices.device_class'></th>
		        <th data-i18n="ios_devices.firmware_version_string" data-colname='ios_devices.firmware_version_string'></th>
		        <th data-i18n="ios_devices.build_version" data-colname='ios_devices.build_version'></th>
		        <th data-i18n="ios_devices.connected" data-colname='ios_devices.connected'></th>
		        <th data-i18n="ios_devices.product_type" data-colname='ios_devices.product_type'></th>
		        <th data-i18n="ios_devices.use_count" data-colname='ios_devices.use_count'></th>
		        <th data-i18n="ios_devices.region_info" data-colname='ios_devices.region_info'></th>
		        <th data-i18n="ios_devices.imei" data-colname='ios_devices.imei'></th>
		        <th data-i18n="ios_devices.meid" data-colname='ios_devices.meid'></th>
		        <th data-i18n="ios_devices.ios_id" data-colname='ios_devices.ios_id'></th>
		        <th data-i18n="ios_devices.prefpath" data-colname='ios_devices.prefpath'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="14" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
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
            runtypes = [], // Array for runtype column
            columnDefs = [{ visible: false, targets: hideThese }]; //Column Definitions

        $('.table th').map(function(){

            columnDefs.push({name: $(this).data('colname'), targets: col});

            if($(this).data('sort')){
              mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide')){
              hideThese.push(col);
            }

            col++
        });

	    oTable = $('.table').dataTable( {
            ajax: {
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "ios_devices.serial";

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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_ios_devices-tab');
	        	$('td:eq(0)', nRow).html(link);

	        	// Format connected
	        	var checkin = parseInt($('td:eq(6)', nRow).html());
	        	var date = new Date(checkin * 1000);
	        	$('td:eq(6)', nRow).html('<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span>');
                
            }
	    } );
	    // Use hash as searchquery
	    if(window.location.hash.substring(1))
	    {
			oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
	    }

	} );
</script>

<?php $this->view('partials/foot')?>
