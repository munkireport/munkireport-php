<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

	<div class="row">

		<div class="col-xs-12">

			<script type="text/javascript">

		$(document).on('appReady', function(e, lang) {

			    oTable = $('.table').dataTable( {
			        "bDeferRender": true,
		            "bServerSide": false,
			        "fnDrawCallback": function( oSettings ) {
						$('#pen-count').html(oSettings.fnRecordsTotal());
					},
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = get_client_detail_link(name, sn, '<?=url()?>/');
			        	$('td:eq(0)', nRow).html(link);

			        	// Format date
			        	date = aData[4];
			        	if(date)
			        	{
			              	$('td:eq(4)', nRow).html(moment(date).fromNow());
			        	}
			        	else
			        	{
			        		$('td:eq(4)', nRow).html('never');
			        	}
				    }
			    } );

			} );
		</script>

		<h3>Pending Updates <span id="pen-count" class='label label-primary'>…</span></h3>

<?// Select pending, loop, check if item create table?>

		<table class="table table-striped table-condensed table-bordered">

			<thead>
			  <tr>
			  	<th>Client</th>
			  	<th>Serial</th>
			  	<th>User</th>
			  	<th>IP</th>
			  	<th>Latest run</th>
			    <th>Name</th>
				<th>Type</th>
			  </tr>
			</thead>

			<tbody>
				<?$sql = "SELECT computer_name, 
							m.serial_number,
							long_username,
							m.timestamp,
							remote_ip,
							report_plist
						FROM munkireport m
						LEFT JOIN machine USING (serial_number)
						LEFT JOIN reportdata USING (serial_number)
						WHERE pendinginstalls > 0";
				$compress = function_exists('gzdeflate');
					$mr = new Munkireport_model;
					?>
				<?foreach($mr->query($sql) as $obj):?>
					<?$report_plist = unserialize( $compress ? gzinflate( $obj->report_plist ) : $obj->report_plist )?>
					<?if(isset($report_plist['AppleUpdates'])):?>
						<?foreach ($report_plist['AppleUpdates'] as $update):?>

				<tr>
					<td><?=$obj->computer_name?></td>
					<td><?=$obj->serial_number?></td>
					<td><?=$obj->long_username?></td>
					<td><?=$obj->remote_ip?></td>
					<td><?=$obj->timestamp?></td>
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
					<td><?=$obj->remote_ip?></td>
					<td><?=$obj->timestamp?></td>
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
