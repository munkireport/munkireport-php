<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

	<div class="row">

		<div class="col-xs-12">

			<script type="text/javascript">

		$(document).ready(function() {

			    oTable = $('.table').dataTable( {
			        "bProcessing": true,
			        "bDeferRender": true,
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = get_client_detail_link(name, sn, '<?=url()?>/');
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
			} );
		</script>

		<h3>Pending Updates</h3>

<?// Select pending, loop, check if item create table?>

		<table class="table table-striped table-condensed table-bordered">

			<thead>
			  <tr>
			  	<th data-colname='machine#computer_name'>Client</th>
			  	<th data-colname='machine#computer_name'>Serial</th>
			  	<th data-colname='machine#computer_name'>User</th>
			    <th data-colname='machine#serial_number'>Name</th>
				<th data-colname='machine#os_version'>Type</th>
			  </tr>
			</thead>

			<tbody>
				<?$sql = "SELECT computer_name, m.serial_number, report_plist, long_username
						FROM munkireport m
						LEFT JOIN machine USING (serial_number)
						LEFT JOIN reportdata USING (serial_number)
						WHERE pendinginstalls > 0";
				$compress = function_exists('gzdeflate');
					$mr = new Munkireport;
					?>
				<?foreach($mr->query($sql) as $obj):?>
					<?$report_plist = unserialize( $compress ? gzinflate( $obj->report_plist ) : $obj->report_plist )?>
					<?if(isset($report_plist['AppleUpdates'])):?>
						<?foreach ($report_plist['AppleUpdates'] as $update):?>

				<tr>
					<td><?=$obj->computer_name?></td>
					<td><?=$obj->serial_number?></td>
					<td><?=$obj->long_username?></td>
					<td>
						<?=$update['apple_product_name']?>
						<?=$update['version_to_install']?>
					</td>
					<td>Apple Update</td>
				</tr>
						<?endforeach?>
					<?endif?>

					<?if(isset($report_plist['ItemsToInstall'])):?>
						<?foreach ($report_plist['ItemsToInstall'] as $update):?>

				<tr>
					<td><?=$obj->computer_name?></td>
					<td><?=$obj->serial_number?></td>
					<td><?=$obj->long_username?></td>
					<td>
						<?=$update['display_name']?>
						<?=$update['version_to_install']?>
					</td>
					<td>Munki Update</td>
				</tr>
						<?endforeach?>
					<?endif?>
				<?endforeach?>
			</tbody>

		</table>

    </div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>	<!-- /container -->


<?$this->view('partials/foot')?>
