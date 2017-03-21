<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Disk_report_model;
new Reportdata_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="disk_report.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="disk_report.mountpoint" data-colname='diskreport.MountPoint'></th>
		        <th data-i18n="disk_report.volume_type" data-colname='diskreport.VolumeType'></th>
		        <th data-i18n="disk_report.percentage" data-sort='desc' data-colname='diskreport.Percentage'></th>
		        <th data-i18n="disk_report.free" data-colname='diskreport.FreeSpace'></th>
		        <th data-i18n="disk_report.total_size" data-colname='diskreport.TotalSize'></th>
		    	<th data-i18n="disk_report.smartstatus" data-colname='diskreport.SMARTStatus'></th>
		    	<th data-i18n="disk_report.encryption_status" data-colname='diskreport.CoreStorageEncrypted'></th>
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

                    d.mrColNotEmpty = "FreeSpace";

                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'diskreport.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });

                    }

                    // Look for 'between' statement todo: make generic
                    if(d.search.value.match(/^\d+GB freespace \d+GB$/))
                    {
                        // Add column specific search
                        d.columns[6].search.value = d.search.value.replace(/(\d+GB) freespace (\d+GB)/, function(m, from, to){return ' BETWEEN ' + humansizeToBytes(from) + ' AND ' + humansizeToBytes(to)});
                        // Clear global search
                        d.search.value = '';

                        //dumpj(d)
                    }

                    // Look for a bigger/smaller/equal statement
                    if(d.search.value.match(/^freespace [<>=] \d+GB$/))
                    {
                        // Add column specific search
                        d.columns[6].search.value = d.search.value.replace(/.*([<>=] )(\d+GB)$/, function(m, o, content){return o + humansizeToBytes(content)});
                        // Clear global search
                        d.search.value = '';

                        //dumpj(out)
                    }

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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_storage-tab');
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
	        	smartstatus = smartstatus == 'Failing' ? '<span class="label label-danger">'+i18n.t('failing')+'</span>' :
	        		(smartstatus)
	        	$('td:eq(8)', nRow).html(smartstatus)

	        	// Encryption Station
	        	var encryptionstatus=$('td:eq(9)', nRow).html();
	        	encryptionstatus = encryptionstatus == '1' ? i18n.t('disk_report.encrypted') :
	        	(encryptionstatus === '0' ? i18n.t('disk_report.not_encrypted') : '')
	        	$('td:eq(9)', nRow).html(encryptionstatus)
		    }

	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
