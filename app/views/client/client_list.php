<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		<script type="text/javascript">
		function fileSize(size, decimals) {
			if(decimals == undefined){decimals = 2};
			var i = Math.floor( Math.log(size) / Math.log(1024) );
			return ( size / Math.pow(1024, i) ).toFixed(decimals) * 1 + ['B', 'K', 'M', 'G', 'T'][i];
		}

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
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = '<a class="btn btn-default btn-xs" href="<?=url('clients/detail/')?>'+sn+'">'+name+'</a>';
			        	$('td:eq(0)', nRow).html(link);

			        	// Format disk usage
			        	var disk=$('td:eq(5)', nRow).html();
			        	var cls = disk > 90 ? 'danger' : (disk > 80 ? 'warning' : 'success');
			        	$('td:eq(5)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+disk+'%;">'+disk+'%</div></div>');

			        	// Format date
			        	var date = new Date($('td:eq(7)', nRow).html() * 1000);
			        	$('td:eq(7)', nRow).html(date.toLocaleDateString());

			        	// Format filesize
			        	var fs=$('td:eq(8)', nRow).html();
			        	$('td:eq(8)', nRow).addClass('text-right').html(fileSize(fs, 0));
				    }
			    } );
			} );
		</script>

		<?$machine = new Machine()?>

		  <h1>Machines <span class="label"><?=$machine->count()?></span></h1>
		  
		  <table class="table table-striped table-condensed">
		    <thead>
		      <tr>
		      	<th data-colname='machine#computer_name'>Name</th>
		        <th data-colname='machine#serial_number'>Serial</th>
				<th data-colname='machine#os_version'>OS</th>
		        <th data-colname='machine#machine_name'>Type</th>
		        <th data-colname='warranty#status'>Warranty status</th>
		        <th data-colname='diskreport#Percentage'>Disk</th>
		        <th data-colname='reportdata#long_username'>Username</th>
		        <th data-colname='reportdata#timestamp'>Check-in</th>
		        <th data-colname='diskreport#FreeSpace'>Free</th>
				<th data-colname='munkireport#manifest'>Manifest</th>
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