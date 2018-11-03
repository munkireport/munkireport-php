<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Service_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="service.listing.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		        <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='machine.serial_number'></th>
		        <th data-i18n="service.name" data-colname='service.service_name'></th>
		        <th data-i18n="service.status" data-colname='service.service_state'></th>
		        <th data-i18n="listing.checkin" data-colname='reportdata.timestamp'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td colspan="5" class="dataTables_empty">Loading data from server</td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

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
                d.mrColNotEmpty = "service.id"
            }
        },
        order: mySort,
        columnDefs: columnDefs,
        createdRow: function( nRow, aData, iDataIndex ) {
        	// Update name in first column to link
        	var name=$('td:eq(0)', nRow).html();
        	if(name == ''){name = "No Name"};
        	var sn=$('td:eq(1)', nRow).html();
        	var link = mr.getClientDetailLink(name, sn, '#tab_power-tab');
        	$('td:eq(0)', nRow).html(link);

        	
			// Format Check-In timestamp
			var checkin = parseInt($('td:eq(4)', nRow).html());
			var date = new Date(checkin * 1000);
			$('td:eq(4)', nRow).html(moment(date).fromNow());
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
