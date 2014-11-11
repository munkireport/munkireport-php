<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		<script type="text/javascript">

		$(document).on('appReady', function(e, lang) {

				// Get column names from data attribute
				var myCols = [];
				$('.table th').map(function(){
					  myCols.push({'mData' : $(this).data('colname')});
				});
			    oTable = $('.table').dataTable( {
			        "sAjaxSource": "<?=url('datatables/data')?>",
			        "aoColumns": myCols,
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = get_client_detail_link(name, sn, '<?=url()?>/');
			        	$('td:eq(0)', nRow).html(link);

			        	var mem=$('td:eq(4)', nRow).html();
			        	$('td:eq(4)', nRow).html(parseInt(mem) + ' GB');
			        },
			         "fnServerParams": function ( aoData ) {
				      //aoData.push( { "name": "more_data", "value": "my_value" } );
				      	// Hook in serverparams to change search
				      	// Convert array to dict
				      	var out = {}
						for (var i = 0; i < aoData.length; i++) {
							out[aoData[i]['name']] =  aoData[i]['value']
						}

						// Look for a GB search string
						if(out.sSearch.match(/^\d+ GB$/))
						{
							// Clear global search
							aoData.push( { "name": "sSearch", "value": "" } );

							// Add column specific search
							aoData.push( { "name": "sSearch_4", "value": parseInt(out.sSearch) } );
							//dumpj(out)
						}
				    }
			    } );

			} );
		</script>

		  <h3>Hardware report <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine#computer_name'>Name</th>
		        <th data-i18n="serial" data-colname='machine#serial_number'>Serial</th>
		        <th data-i18n="listing.username" data-colname='reportdata#long_username'>Username</th>
		        <th data-i18n="listing.hardware.description" data-colname='machine#machine_desc'>Description</th>
		        <th data-i18n="memory" data-colname='machine#physical_memory'>Memory</th>
		        <th data-colname='machine#number_processors'>Processors</th>
		        <th data-colname='machine#cpu_arch'>CPU</th>
		        <th data-colname='machine#current_processor_speed'>Speed</th>
		        <th data-colname='machine#boot_rom_version'>Boot ROM</th>
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