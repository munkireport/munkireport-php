<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Timemachine_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="listing.timemachine.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="listing.username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="backup.last_success" data-colname='timemachine.last_success'></th>
		        <th data-i18n="backup.duration" data-colname='timemachine.duration'></th>
		        <th data-i18n="backup.last_failure" data-colname='timemachine.last_failure'></th>
		        <th data-i18n="backup.last_failure_msg" data-colname='timemachine.last_failure_msg'></th>
		        <th data-i18n="backup.kind" data-colname='timemachine.kind'></th>
		        <th data-i18n="backup.location_name" data-colname='timemachine.location_name'></th>
		        <th data-i18n="backup.backup_location" data-colname='timemachine.backup_location'></th>
		        <th data-i18n="backup.destinations" data-colname='timemachine.destinations'></th>
				<th data-i18n="listing.checkin" data-colname='reportdata.timestamp'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="8" class="dataTables_empty"></td>
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
                url: "<?=url('datatables/data')?>",
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "timemachine.id"
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
	        	var link = mr.getClientDetailLink(name, sn, '<?=url()?>/', '#tab_summary');
	        	$('td:eq(0)', nRow).html(link);

	        	// Format start date
	        	var date = $('td:eq(3)', nRow).html();
	        	if(date){
		        	$('td:eq(3)', nRow).html(moment(date + 'Z').fromNow());
		        }

	        	// Format duration
	        	var duration = parseInt($('td:eq(4)', nRow).html());
	        	if(duration){
	        	  	$('td:eq(4)', nRow).html(moment.duration(duration, "seconds").humanize());
	        	}

	        	var date = $('td:eq(5)', nRow).html();
	        	if(date){
		        	$('td:eq(5)', nRow).html(moment(date + 'Z').fromNow());
	        	}
	        	
				// Format Check-In timestamp
				var checkin = parseInt($('td:eq(11)', nRow).html());
				var date = new Date(checkin * 1000);
				$('td:eq(11)', nRow).html(moment(date).fromNow());
		    }
	    } );
	    // Use hash as searchquery
	    if(window.location.hash.substring(1))
	    {
			oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
	    }
	    
	} );
</script>

<?$this->view('partials/foot')?>

