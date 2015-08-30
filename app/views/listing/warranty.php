<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Warranty_model;
new Reportdata_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3>Warranty report <span id="total-count" class='label label-primary'>…</span></span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'>Name</th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'>Serial</th>
		        <th data-i18n="listing.username" data-colname='reportdata.long_username'>Username</th>
		        <th data-colname='warranty.status'>Warranty status</th>
		        <th data-colname='warranty.purchase_date'>Purchased</th>
		        <th data-colname='warranty.end_date'>Warranty Expires</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="6" class="dataTables_empty"></td>
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
            url: "<?=url('datatables/data')?>",
            type: "POST"
        },
        order: mySort,
        columnDefs: columnDefs,
        createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = get_client_detail_link(name, sn, '<?php echo url(); ?>/');
	        	$('td:eq(0)', nRow).html(link);

	        	// Format warranty status
	        	var status=$('td:eq(3)', nRow).html();
	        	var cls = status == 'Expired' ? 'danger' : (status == 'Supported' ? 'success' : 'warning');
	        	$('td:eq(3)', nRow).html('<span class="label label-'+cls+'">'+status+'</span>');
	        	
	        	// Format purchase date
	        	var date=$('td:eq(4)', nRow).html();
	        	if(date){
	        		a = moment(date)
	        		b = a.diff(moment(), 'years', true)
	        		if(a.diff(moment(), 'years', true) < -4)
	        		{
	        			$('td:eq(4)', nRow).addClass('danger')
	        		}
	        		if(Math.round(b) == 4)
	        		{
	        			
	        		}
	        		$('td:eq(4)', nRow).addClass('text-right').html(moment(date).fromNow());
	        	}

	        	// Format expiration date
	        	var date=$('td:eq(5)', nRow).html();
	        	if(date){
	        		$('td:eq(5)', nRow).addClass('text-right').html(moment(date).fromNow());
	        	}
		    }
	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
