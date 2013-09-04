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
			        "bProcessing": true,
			        "bServerSide": true,
			        "sAjaxSource": "<?=url('datatables/data')?>",
			        "aoColumnDefs": [
			        	{ 'bVisible': false, "aTargets": hideThese }
					],
			        "aoColumns": myCols,
			        "aaSorting": mySort,
			        "fnDrawCallback": function( oSettings ) {
						$('#total-count').html(oSettings.fnRecordsTotal());
					},
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = '<a class="btn btn-default btn-xs" href="<?=url('clients/detail/')?>'+sn+'#tab_munki">'+name+'</a>';
			        	$('td:eq(0)', nRow).html(link);

			        	// Format date
			        	date = aData['munkireport#timestamp'];
			        	if(date)
			        	{
			              	$('td:eq(6)', nRow).html(moment(date).fromNow());
			        	}
			        	else
			        	{
			        		$('td:eq(6)', nRow).html('never');
			        	}

			        	var runtype = $('td:eq(7)', nRow).html(),
				        	cols = [
				        		{name:'errors', flag: 'danger', desc: 'error%s'},
				        		{name:'warnings', flag: 'warning', desc: 'warning%s'},
				        		{name:'pendinginstalls', flag: 'info', desc: 'pending install%s'},
				        		{name:'pendingremovals', flag: 'info', desc: 'pending removal%s'},
				        		{name:'installresults', flag: 'success', desc: 'package%s installed'},
				        		{name:'removalresults', flag: 'success', desc: 'package%s removed'}
				        	], 
			        		count = 0

			        	cols.map( function(col) {
			        		count = aData['munkireport#' + col.name]
				        	if(count > 0)
				        	{
				        		runtype += ' <span class="text-'+col.flag+'">' + 
					        		count + ' ' + col.desc.replace('%s', ''.pluralize(count)) + '</span>'
				        	}
						})

			        	$('td:eq(7)', nRow).html(runtype)

				    }
			    } );
			    // Use hash as sort column for hidden cols
			    if(window.location.hash.substring(1))
			    {
					sortcol = window.location.hash.substring(1);
					// Detect correct column here
					var col = 0,
						sortarr = []
					myCols.map(function(item){
						if(item.mData.indexOf(sortcol) > 0)
						{
							sortarr.push([col, 'desc']);
						}
						col++;
					});
					//oTable.fnFilter( window.location.hash.substring(1) );
					 oTable.fnSort( sortarr );
			    }
			} );
		</script>

		  <h3>Munki <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed">
		    <thead>
		      <tr>
		      	<th data-colname='machine#computer_name'>Client</th>
		        <th data-colname='machine#serial_number'>Serial</th>
		        <th data-colname='reportdata#long_username'>User</th>
		        <th data-colname='reportdata#remote_ip'>IP</th>
				<th data-colname='machine#os_version'>OS</th>
		        <th data-colname='munkireport#version'>Munki</th>
		        <th data-sort="desc" data-colname='munkireport#timestamp'>Latest Run</th>
		        <th data-colname='munkireport#runtype'>Runtype</th>
		        <th data-hide="1" data-colname='munkireport#errors'>Errors</th>
		        <th data-hide="1" data-colname='munkireport#warnings'>Warnings</th>
		        <th data-hide="1" data-colname='munkireport#pendinginstalls'>Pending</th>
		        <th data-hide="1" data-colname='munkireport#installresults'>Installed</th>
		        <th data-hide="1" data-colname='munkireport#removalresults'>Removed</th>
		        <th data-hide="1" data-colname='munkireport#pendingremovals'>Removed</th>
				<th data-colname='munkireport#manifestname'>Manifest</th>
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