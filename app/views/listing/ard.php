<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Ard_model;
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
			        "aoColumns": myCols,
			        "sAjaxSource": "<?=url('datatables/data')?>",
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = get_client_detail_link(name, sn, '<?=url()?>/');
			        	$('td:eq(0)', nRow).html(link);

			        }
			    } );
			} );
		</script>

		  <h3><span data-i18n="listing.ard.title">ARD report</span> <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine#computer_name'>Name</th>
		        <th data-i18n="serial" data-colname='machine#serial_number'>Serial</th>
		        <th data-i18n="listing.username" data-colname='reportdata#long_username'>Username</th>
		        <th data-i18n="listing.ard.text" data-i18n-options='{"number":1}' data-colname='ard#Text1'>Text 1</th>
		        <th data-i18n="listing.ard.text" data-i18n-options='{"number":2}' data-colname='ard#Text2'>Text 2</th>
		        <th data-i18n="listing.ard.text" data-i18n-options='{"number":3}' data-colname='ard#Text3'>Text 3</th>
		        <th data-i18n="listing.ard.text" data-i18n-options='{"number":4}' data-colname='ard#Text4'>Text 4</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td colspan="7" class="dataTables_empty">Loading data from server</td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>