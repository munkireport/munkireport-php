<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Tag_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="tag.listing.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='machine.serial_number'></th>
				<th data-i18n="listing.username" data-colname='reportdata.long_username'>Username</th>
		        <th data-i18n="tag.name" data-colname='tag.tag'></th>
				<th data-i18n="listing.checkin" data-colname='reportdata.timestamp'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td colspan="6" class="dataTables_empty">Loading data from server</td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script>

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
                d.mrColNotEmpty = "tag.id";
				
				// Look for a bigger/smaller/equal statement
				if(d.search.value.match(/^tag = .+$/))
				{
					// Add column specific search
					d.columns[3].search.value = d.search.value.replace(/tag = /, '');
					// Clear global search
					d.search.value = '';
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
        	var link = mr.getClientDetailLink(name, sn, '#tab_summary');
        	$('td:eq(0)', nRow).html(link);
			
			// Format Check-In timestamp
			var checkin = parseInt($('td:last', nRow).html());
			var date = new Date(checkin * 1000);
			$('td:last', nRow).html(moment(date).fromNow());
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
