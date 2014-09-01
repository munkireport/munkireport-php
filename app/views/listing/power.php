<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Power_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-13">
		<script type="text/javascript">

		$(document).on('appReady', function(e, lang) {

				// Get modifiers from data attribute
				var myCols = [], // Colnames
					mySort = [], // Initial sort
					hideThese = [ 13 ], // Hidden columns
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
					
			        	// Format designed capacity
			        	var fs=$('td:eq(3)', nRow).html();
			        	$('td:eq(3)', nRow).addClass('text-right');

			        	// Format maximum capacity
			        	var fs=$('td:eq(4)', nRow).html();
			        	$('td:eq(4)', nRow).addClass('text-right');

			        	// Format cycles
			        	var fs=$('td:eq(5)', nRow).html();
			        	$('td:eq(5)', nRow).addClass('text-right');


			        	// Format battery health
			        	var max_percent=$('td:eq(6)', nRow).html();
			        	var cls = max_percent > 89 ? 'success' : (max_percent > 79 ? 'warning' : 'danger');
			        	$('td:eq(6)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+max_percent+'%;">'+max_percent+'%</div></div>');

						// Format battery condition
			        	var status=$('td:eq(7)', nRow).html();
			        	status = status == 'Normal' ? '<span class="label label-success">Normal</span>' : 
			        	status = status == 'Replace Soon' ? '<span class="label label-warning">Replace Soon</span>' : 
			        	status = status == 'Service Battery' ? '<span class="label label-warning">Service Battery</span>' : 
			        	status = status == 'Replace Now' ? '<span class="label label-danger">Replace Now</span>' : 
			        		(status === 'No Battery' ? '<span class="label label-danger">No Battery</span>' : '')
			        	$('td:eq(7)', nRow).html(status)


			        	// Format current charge
			        	var fs=$('td:eq(8)', nRow).html();
			        	$('td:eq(8)', nRow).addClass('text-right');

			        	// Format percentage
			        	var fs=$('td:eq(9)', nRow).html();
			        	$('td:eq(9)', nRow).addClass('text-right');


			        	// Format temperature
						// Check config for temperature °C or °F (default Celsius)
						// °C * 9/5 + 32 = °F
						var fahrenheit = "<?=conf('Fahrenheit')?>";
						if ( fahrenheit == 1 ){
							// Fahrenheit
							var temperature=$('td:eq(10)', nRow).html();
							if ( temperature == 0 || temperature == "" ){
								temperature = "";
							} else {
								temperature = (((temperature * 9/5 ) + 3200 ) / 100).toFixed(1);
							}
							$('td:eq(10)', nRow).html(temperature).addClass('text-right');
						} else {
							// Celsius
				        	var temperature=$('td:eq(10)', nRow).html();
							if ( temperature == 0 || temperature == "" ){
								temperature = "";
							} else {
						       	temperature = (temperature / 100).toFixed(1);
							}
				        	$('td:eq(10)', nRow).html(temperature).addClass('text-right');
						}


			        	// Format Manufacture date
			        	var date=$('td:eq(11)', nRow).html();
			        	if(date){
			        		a = moment(date)
			        		b = a.diff(moment(), 'years', true)
			        		if(a.diff(moment(), 'years', true) < -4)
			        		{
			        			$('td:eq(11)', nRow).addClass('danger')
			        		}
			        		if(Math.round(b) == 4)
			        		{
			        			
			        		}
			        		$('td:eq(11)', nRow).addClass('text-right').html(moment(date).fromNow());
			        	}


						// Format Check-In timestamp
						var checkin = parseInt($('td:eq(12)', nRow).html());
						var date = new Date(checkin * 1000);
						$('td:eq(12)', nRow).html(moment(date).fromNow());


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
		        <th data-colname='power#max_percent'>Health %</th>
		        <th data-colname='power#condition'>Condition</th>
		        <th data-colname='power#current_capacity'>Current (mAh)</th>
		        <th data-colname='power#current_percent'>Charged %</th>
				<?php
					$fahrenheit=conf('Fahrenheit');
					if ( $fahrenheit == 1 ) {
						echo "<th data-colname='power#temperature'>Temp°F</th>";
					} else {
						echo "<th data-colname='power#temperature'>Temp°C</th>";
					}
				?>
		        <th data-colname='power#manufacture_date'>Manufactured</th> 
		        <th data-sort="desc" data-colname='power#timestamp'>Check-In</th> 
		        <th data-colname='machine#machine_model'>Model ( col 13 hidden )</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td colspan="13" class="dataTables_empty">Loading data from server</td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>