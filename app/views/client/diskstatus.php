<?$this->view('partials/head')?>

<div class="container">

  <div class="row">

  	<div class="span12">
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('.table').dataTable({
		            "iDisplayLength": 25,
		            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
				    "aoColumns": [ 
						/* Client */			null,
						/* Serial */			null,
						/* Hostname */			null,
						/* Smart status */		null,
						/* Disk type */			null,
						/* Used disk space */	null,
						/* Percent */   { "bVisible":    false }
					],
					"bStateSave": true,
		            "aaSorting": [[5,'desc']],
		            "aoColumnDefs": [
				      { "iDataSort": 6, "aTargets": [ 5 ] }
				    ]
				});
			} );
		</script>

		<?$diskreport = new DiskReport()?>

		  <h1>Disk Reports (<?=$diskreport->count()?>)</h1>
		  
		  <table class="table table-striped">
		    <thead>
		      <tr>
		        <th>Client    </th>
		        <th>Serial    </th>
		        <th>Hostname   </th>
		        <th>Smart status</th>
				<th>Disk type</th>
				<th>Used disk space</th>
				<th>Percent</th>
		      </tr>
		    </thead>
		    <tbody>
			<?foreach($diskreport->retrieve_many() as $client):?>
		      <tr>
				<?$machine = new Machine($client->serial_number)?>
		        <td>
					<a href="<?=url("clients/detail/$client->serial_number")?>"><?=$machine->computer_name?></a>
				</td>
				<td><?=$client->serial_number?></td>
				<td><?=$machine->hostname?></td>
				<td><?=$client->SMARTStatus?></td>
				<td><?=$client->SolidState?></td>

				<td>
					<?$pct = $client->Percentage;
					$label = $pct > 90 ? 'danger' : ($pct > 80 ? 'warning' : 'success');?>
					<div class="progress progress-<?=$label?>">
						<div class="bar" style="width: <?=$pct?>%;"><?=$pct?>%</div>
					</div>
				</td>
				<td><?=$pct?></td>
		      </tr>
			<?endforeach?>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>