<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Supported_os_model;
new Reportdata_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
		  <h3><span data-i18n="supported_os.listingtitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="supported_os.current_os" data-colname='supported_os.current_os'></th>
		        <th data-i18n="supported_os.highest_supported" data-colname='supported_os.highest_supported'></th>
		        <th data-i18n="supported_os.machine_id" data-colname='supported_os.machine_id'></th>
		        <th data-i18n="supported_os.last_touch" data-colname='supported_os.last_touch'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="7" class="dataTables_empty"></td>
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
                    d.mrColNotEmptyBlank = "supported_os.current_os";
                    // Look for 'osversion' statement
                    if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                        var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                        d.search.value = search;
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
	        	var link = mr.getClientDetailLink(name, sn);
	        	$('td:eq(0)', nRow).html(link);

	        	// Format current OS
                var version = $('td:eq(3)', nRow).html();
                if (version != "" && (version)) {
                  $('td:eq(3)', nRow).html(mr.integerToVersion(version));
                } else {
                  $('td:eq(3)', nRow).html('');  
                }
            
                // Format highest supported OS
                var version = $('td:eq(4)', nRow).html();
                if (version != "" && (version)) {
                  $('td:eq(4)', nRow).html(mr.integerToVersion(version));
                } else {
                  $('td:eq(4)', nRow).html('');  
                }

                // Format Check-In timestamp
				var checkin = parseInt($('td:eq(6)', nRow).html());
				var date = new Date(checkin * 1000);
				$('td:eq(6)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
		    }
	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
