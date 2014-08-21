<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Power_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		<script type="text/javascript">

		$(document).on('appReady', function(e, lang) {

				// Get modifiers from data attribute
				var myCols = [], // Colnames
					mySort = [], // Initial sort
					hideThese = [ 12 ], // Hidden columns
					col = 0; // Column counter

				$('.table th').map(function(){

					  myCols.push({'mData' : $(this).data('colname')});

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
			        "sAjaxSource": "<?=url('datatables/data')?>",
			        "aaSorting": mySort,
			        "aoColumns": myCols,
			        "aoColumnDefs": [
			        	{ 'bVisible': false, "aTargets": hideThese }
					],
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = get_client_detail_link(name, sn, '<?=url()?>/', '#tab_power-tab');
			        	$('td:eq(0)', nRow).html(link);
					
			        	// Format 
			        	var fs=$('td:eq(3)', nRow).html();
			        	$('td:eq(3)', nRow).addClass('text-right');

			        	// Format 
			        	var fs=$('td:eq(4)', nRow).html();
			        	$('td:eq(4)', nRow).addClass('text-right');

			        	// Format 
			        	var fs=$('td:eq(5)', nRow).html();
			        	$('td:eq(5)', nRow).addClass('text-right');


			        	// Format battery health
			        	var max_percent=$('td:eq(6)', nRow).html();
			        	var cls = max_percent > 89 ? 'success' : (max_percent > 79 ? 'warning' : 'danger');
			        	$('td:eq(6)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+max_percent+'%;">'+max_percent+'%</div></div>');



						// Battery Condition
			        	var status=$('td:eq(7)', nRow).html();
			        	status = status == 'Normal' ? '<span class="label label-success">Normal</span>' : 
			        	status = status == 'Replace Soon' ? '<span class="label label-warning">Replace Soon</span>' : 
			        	status = status == 'Service Battery' ? '<span class="label label-danger">Service Battery</span>' : 
			        		(status === 'Replace Now' ? '<span class="label label-danger">Replace Now</span>' : '')
			        	$('td:eq(7)', nRow).html(status)

			        	// Format 
			        	var fs=$('td:eq(8)', nRow).html();
			        	$('td:eq(8)', nRow).addClass('text-right');

			        	// Format
			        	var fs=$('td:eq(9)', nRow).html();
			        	$('td:eq(9)', nRow).addClass('text-right');

				    }
			    } );

			    // Use hash as searchquery
			    if(window.location.hash.substring(1))
			    {
					oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
			    }
			    
			} );
		</script>

		  <h3>Power report <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine#computer_name'>Name</th>
		        <th data-i18n="serial" data-colname='machine#serial_number'>Serial</th>
		        <th data-i18n="listing.username" data-colname='reportdata#long_username'>Username</th>
		        <th data-colname='power#design_capacity'>Designed (mAh)</th>
		        <th data-colname='power#max_capacity'>Capacity (mAh)</th>
		        <th data-colname='power#cycle_count'>Cycles</th>
		        <th data-sort="asc" data-colname='power#max_percent'>Health %</th>
		        <th data-colname='power#condition'>Condition</th>
		        <th data-colname='power#current_capacity'>Current (mAh)</th>
		        <th data-colname='power#current_percent'>Charged %</th>
		        <th data-colname='power#temperature'>Temperature</th>
		        <th data-colname='power#manufacture_date'>Manufactured</th> 
		        <th data-colname='machine#machine_model'>Model (Hidden)</th>
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

<?$this->view('partials/foot')?>