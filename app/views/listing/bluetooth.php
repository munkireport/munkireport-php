<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Bluetooth_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		<script type="text/javascript">

		$(document).on('appReady', function() {

				// Get modifiers from data attribute
				var myCols = [], // Colnames
					mySort = [], // Initial sort
					hideThese = [], // Hidden columns
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
			        	var link = get_client_detail_link(name, sn, '<?=url()?>/', '#tab_bluetooth-tab');
			        	$('td:eq(0)', nRow).html(link);
			        	
			        	// Translate bool. todo function for any bool we find
                        var status=$('td:eq(7)', nRow).html();
                        status = status == 1 ? 'Yes' : 
                        (status === '0' ? 'No' : '')
                        $('td:eq(7)', nRow).html(status)

				    }
			    } );
			} );
		</script>

		  <h3><span data-i18n="listing.bluetooth.title">Bluetooth report</span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine#computer_name'>Name</th>
		        <th data-i18n="serial" data-colname='machine#serial_number'>Serial</th>
		        <th data-i18n="listing.username" data-colname='reportdata#long_username'>Username</th>
		        <th data-i18n="listing.bluetooth.status" data-colname='bluetooth#bluetooth_status'>Bluetooth Status</th> 
		        <th data-i18n="listing.bluetooth.keyboard_battery" data-colname='bluetooth#keyboard_battery'>Keyboard Status</th>
		        <th data-i18n="listing.bluetooth.mouse_battery" data-colname='bluetooth#mouse_battery'>Mouse Status</th>
		        <th data-i18n="listing.bluetooth.trackpad_battery" data-colname='bluetooth#trackpad_battery'>Trackpad Status</th>
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