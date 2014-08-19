<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Warranty_model;
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
			    } );
			} );
		</script>

		  <h3>Warranty report <span id="total-count" class='label label-primary'>…</span></span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine#computer_name'>Name</th>
		        <th data-i18n="serial" data-colname='machine#serial_number'>Serial</th>
		        <th data-i18n="listing.username" data-colname='reportdata#long_username'>Username</th>
		        <th data-colname='warranty#status'>Warranty status</th>
		        <th data-colname='warranty#purchase_date'>Purchased</th>
		        <th data-colname='warranty#end_date'>Warranty Expires</th>
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