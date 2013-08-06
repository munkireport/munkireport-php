<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		<script type="text/javascript">
		$(document).ready(function() {

				// Get column names from data attribute
				var myCols = [];
				$('.table th').map(function(){
					  myCols.push({'mData' : $(this).data('colname')});
				});
			    $('.table').dataTable( {
			        "bProcessing": true,
			        "bServerSide": true,
			        "sAjaxSource": "<?=url('datatables/data')?>",
			        "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
			        "sPaginationType": "bootstrap",
			        "bStateSave": true,
			        "aoColumns": myCols,
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	var sn=$('td:eq(0)', nRow).html();
			        	var link = '<a class="" href="<?=url('clients/detail/')?>'+sn+'">'+sn+'</a>';
			        	$('td:eq(0)', nRow).html(link);

			        	var date = new Date($('td:eq(6)', nRow).html() * 1000);
			        	$('td:eq(6)', nRow).html(date.toLocaleDateString());
				    }
			    } );
			} );
		</script>

		<?$machine = new Machine()?>

		  <h1>Machines <span class="label"><?=$machine->count()?></span></h1>
		  
		  <table class="table table-striped table-condensed">
		    <thead>
		      <tr>
		        <th data-colname='machine#serial_number'>Serial</th>
		        <th data-colname='machine#hostname'>Hostname</th>
				<th data-colname='machine#os_version'>OS</th>
		        <th data-colname='machine#machine_name'>Machine_name</th>
		        <th data-colname='warranty#status'>Warranty status</th>
		        <th data-colname='reportdata#long_username'>Username</th>
		        <th data-colname='reportdata#timestamp'>Check-in</th>
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