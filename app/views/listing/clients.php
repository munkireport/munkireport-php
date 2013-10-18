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
			if(decimals == undefined){decimals = 0};
			var i = Math.floor( Math.log(size) / Math.log(1024) );
			return ( size / Math.pow(1024, i) ).toFixed(decimals) * 1 + ' ' + ['', 'K', 'M', 'G', 'T'][i] + 'B';
		}

		$(document).ready(function() {

				// Get column names from data attribute
				var myCols = [];
				$('.table th').map(function(){
					  myCols.push({'mData' : $(this).data('colname')});
				});
			    oTable = $('.table').dataTable( {
			        "bProcessing": true,
			        "bServerSide": true,
			        "sAjaxSource": "<?=url('datatables/data')?>",
			        "aoColumns": myCols,
			        "fnDrawCallback": function( oSettings ) {
						$('#total-count').html(oSettings.fnRecordsTotal());
						if($('#edit.btn-danger').length > 0){
							$('div.machine').addClass('edit');
						}

						// Add callback to remove machine buttons
						$('div.machine a.btn-danger').click(function (e) {
							e.preventDefault();
							var row = $(this).parents('tr');
							$.getJSON( $(this).attr('href'), function( data ) {
								if(data.status == 'success')
								{
									row.hide(600, function(){
										oTable.fnDraw();
									});
									// adjust counter
									//var newcnt = $('#total-count').html() - 1;
									//$('#total-count').html(newcnt);
								}
							  	else
							  	{
							  		alert('remove failed')
							  	}
							});
							
						});
					},
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = '<div class="btn-group machine"><a class="btn btn-default btn-xs" href="<?=url('clients/detail/')?>'+sn+'">'+name+'</a><a href="<?=url('admin/delete_machine/')?>'+sn+'" class="btn btn-xs btn-danger"><i class="icon-remove"></i></a></div>';
			        	$('td:eq(0)', nRow).html(link);

			        	// Format disk usage
			        	var disk=$('td:eq(5)', nRow).html();
			        	var cls = disk > 90 ? 'danger' : (disk > 80 ? 'warning' : 'success');
			        	$('td:eq(5)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+disk+'%;">'+disk+'%</div></div>');

			        	// Format date
			        	var date = new Date($('td:eq(7)', nRow).html() * 1000);
			        	$('td:eq(7)', nRow).html(moment(date).fromNow());
				    }
			    } );

				// Use hash as searchquery
			    if(window.location.hash.substring(1))
			    {
					oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
			    }

			    // Add edit button
			    $('#total-count').after(' <a id="edit" class="btn btn-xs btn-default" href="#">edit</a>');

			    $('#edit').click(function(event){
			    	event.preventDefault()
			    	$(this).toggleClass('btn-danger');
			    	$('.machine').toggleClass('edit');
			    });
			    
			} );
		</script>

	  <h3>Machines <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

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
			<th data-colname='munkireport#manifestname'>Manifest</th>
	      </tr>
	    </thead>
	    
	    <tbody>
	    	<tr>
				<td colspan="10" class="dataTables_empty">Loading data from server</td>
			</tr>
	    </tbody>

	  </table>

    </div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>