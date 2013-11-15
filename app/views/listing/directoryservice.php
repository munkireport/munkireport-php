<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine;
new Reportdata;
new Directory_service_model;
?>

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
			        	var link = get_client_detail_link(name, sn, '<?=url()?>/');
			        	$('td:eq(0)', nRow).html(link);

				    }
			    } );

			    // Use hash as searchquery
			    if(window.location.hash.substring(1))
			    {
					oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
			    }
			    
			} );
		</script>

		  <h3>Directory Services report <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-colname='machine#computer_name'>Name</th>
		        <th data-colname='machine#serial_number'>Serial</th>
		        <th data-colname='reportdata#long_username'>Username</th>
		        <th data-colname='directoryservice#which_directory_service'>Bound Status</th>
		        <th data-colname='directoryservice#directory_service_comments'>AD Comments</th>
				<th data-colname='directoryservice#adforest'>Active Directory Forest</th>
				<th data-colname='addomain'>Active Directory Domain</th>
				<th data-colname='computeraccount'>Computer Account</th>
				<th data-colname='createmobileaccount'>Create mobile account at login</th>
				<th data-colname='requireconfirmation'>Require confirmation</th>
				<th data-colname='forcehomeinstartup'>Force home to startup disk</th>
				<th data-colname='mounthomeassharepoint'>Mount home as sharepoint</th>
				<th data-colname='usewindowsuncpathforhome'>Use Windows UNC path for home</th>
				<th data-colname='networkprotocoltobeused'>Network protocol to be used</th>
				<th data-colname='defaultusershell'>Default user Shell</th>
				<th data-colname='mappinguidtoattribute'>Mapping UID to attribute</th>
				<th data-colname='mappingusergidtoattribute'>Mapping user GID to attribute</th>
				<th data-colname='mappinggroupgidtoattr'>Mapping group GID to attribute</th>
				<th data-colname='generatekerberosauth'>Generate Kerberos authority</th>
				<th data-colname='preferreddomaincontroller'>Preferred Domain controller</th>
				<th data-colname='allowedadmingroups'>Allowed admin groups</th>
				<th data-colname='authenticationfromanydomain'>Authentication from any domain</th>
				<th data-colname='packetsigning'>Packet signing</th>
				<th data-colname='packetencryption'>Packet encryption</th>
				<th data-colname='passwordchangeinterval'>Password change interval</th>
				<th data-colname='restrictdynamicdnsupdates'>Restrict Dynamic DNS updates</th>
				<th data-colname='namespacemode'>Namespace mode</th>
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