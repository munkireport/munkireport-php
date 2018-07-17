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

	  <h3><span data-i18n="client.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="username" data-colname='reportdata.long_username'></th>
			<th data-i18n="os.version" data-colname='machine.os_version'></th>
			<th data-i18n="buildversion" data-colname='machine.buildversion'></th>
			<th data-i18n="type" data-colname='machine.machine_name'></th>
			<th data-i18n="warranty.status" data-colname='warranty.status'></th>
			<th data-i18n="uptime" data-i18n="uptime" data-colname='reportdata.uptime'></th>
			<th data-i18n="listing.checkin" data-colname='reportdata.timestamp'></th>
			<th data-i18n="reg_date" data-colname='reportdata.reg_timestamp'></th>
			<th data-i18n="munkireport.manifest.manifest" data-colname='munkireport.manifestname'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="11" class="dataTables_empty"></td>
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
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function( d ){
                    // Look for 'osversion' statement
                    if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                        var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                        d.search.value = search;
                    }
                    
                    // Look for 'between' statement todo: make generic
                    uptimeFieldNumber = 7
                    if(d.search.value.match(/^\d+d uptime \d+d$/)){
                        // Add column specific search
                        d.columns[uptimeFieldNumber].search.value = d.search.value.replace(/(\d+)d uptime (\d+)d/, function(m, from, to){
                            return ' BETWEEN ' + (parseInt(from)*86400) + ' AND ' + (parseInt(to)*86400)
                        });
                        // Clear global search
                        d.search.value = '';
                    }

                    // Look for a bigger/smaller/equal statement
                    if(d.search.value.match(/^uptime [<>=] \d+d$/)){
                        // Add column specific search
                        d.columns[uptimeFieldNumber].search.value = d.search.value.replace(/.*([<>=] )(\d+)d$/, function(m, o, content){
                            return o + (parseInt(content)*86400)
                        });
                        // Clear global search
                        d.search.value = '';
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
				  $('td:eq(7)', nRow).html('<span title="'+i18n.t('boot_time')+': '+moment(date).subtract( uptime, 'seconds').format('llll')+'">'+moment().subtract(uptime, 'seconds').fromNow(true)+'</span>');
				}
                
				// Format check in date
				var checkin = parseInt($('td:eq(8)', nRow).html());
				var date = new Date(checkin * 1000);
				$('td:eq(8)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
                
				// Format registration date
				var registration = parseInt($('td:eq(9)', nRow).html());
				var date = new Date(registration * 1000);
				$('td:eq(9)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
			}
		});
	});
</script>

<?php $this->view('partials/foot'); ?>
