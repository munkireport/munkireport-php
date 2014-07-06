<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Warranty_model;
new Disk_report_model;
new Reportdata_model;
new Munkireport_model;
?>

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
			    oTable = $('.table').dataTable( {
			        "bProcessing": true,
			        "bServerSide": true,
			        "sAjaxSource": "<?=url('datatables/data')?>",
			        "aoColumns": myCols,
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                // Update name in first column to link
                var name=$('td:eq(0)', nRow).html();
                if(name == ''){name = "No Name"};
                var sn=$('td:eq(1)', nRow).html();
                var link = get_client_detail_link(name, sn, '<?=url()?>/');
                $('td:eq(0)', nRow).html(link);

                // Format disk usage
                var disk=$('td:eq(6)', nRow).html();
                var cls = disk > 90 ? 'danger' : (disk > 80 ? 'warning' : 'success');
                $('td:eq(6)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+disk+'%;">'+disk+'%</div></div>');

                // Format uptime
                var uptime=$('td:eq(7)', nRow).html();
                if(uptime == '0') {
                  $('td:eq(7)', nRow).html('')
                } else {
                  // source: stackoverflow.com's question 13903897
                  // calculate (and subtract) whole days
                  var days = Math.floor(uptime / 86400);
                  uptime -= days * 86400;
                  // calculate (and subtract) whole hours
                  var hours = Math.floor(uptime / 3600) % 24;
                  uptime -= hours * 3600;
                  // calculate (and subtract) whole minutes
                  var minutes = Math.floor(uptime / 60) % 60;
                  uptime -= minutes * 60;
                  // what's left is seconds
                  var seconds = uptime
                  $('td:eq(7)', nRow).html('<div title="'+ days + ' days ' + hours +' hours ' + minutes + ' minutes ' + seconds + ' seconds">' + days+' days</div>')
                }

                // Format date
                var date = new Date($('td:eq(8)', nRow).html() * 1000);
                $('td:eq(8)', nRow).html(moment(date).fromNow());
				    }
			    } );

				// Use hash as searchquery
			    if(window.location.hash.substring(1))
			    {
					oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
			    }
			} );
		</script>

	  <h3>Clients report <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

      <thead>
        <tr>
          <th data-colname='machine#computer_name'>Name</th>
          <th data-colname='machine#serial_number'>Serial</th>
          <th data-colname='reportdata#long_username'>Username</th>
          <th data-colname='machine#os_version'>OS</th>
          <th data-colname='machine#machine_name'>Type</th>
          <th data-colname='warranty#status'>Warranty status</th>
          <th data-colname='diskreport#Percentage'>Disk</th>
          <th data-colname='reportdata#uptime'>Uptime</th>
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
