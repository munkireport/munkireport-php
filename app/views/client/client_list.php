<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

  	<div class="span12">
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('.clientlist').dataTable({
					"iDisplayLength": 25,
					"aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
					"sPaginationType": "full_numbers",
					"bStateSave": true,
					"aaSorting": [[4,'desc']]
				});
			} );
		</script>

		<?$machine = new Machine()?>
		<?$hash = new Hash()?>

		  <h1>Machines (<?=$machine->count()?>)</h1>
		  
		  <table class="table table-striped table-condensed">
		    <thead>
		      <tr>
		        <th>Client    </th>
		        <th>Serial    </th>
		        <th>Hostname   </th>
		        <th>IP        </th>
				<th>OS        </th>
		        <th>Machine_name</th>
				<th>Available disk space</th>
				<th>Modules</th>
		      </tr>
		    </thead>
		    <tbody>
			<?foreach($machine->retrieve_many() as $client):?>
		      <tr>
				<?$reportdata = new Reportdata($client->serial_number)?>
		        <td>
		        	<div class="btn-group">
		        		<span class="btn" data-serialnumber="<?=$client->serial_number?>"><i class="icon-info-sign"></i></span>
		        		<a class="btn" href="<?=url("clients/detail/$client->serial_number")?>"><?=$client->computer_name?></a>
		        	</div>
				</td>
				<td><?=$client->serial_number?></td>
				<td><?=$client->hostname?></td>
				<td><?=$reportdata->remote_ip?></td>
				<td><?=$client->os_version?></td>
				<td><?=$client->machine_name?></td>
				<td><?=humanreadablesize($client->available_disk_space * 1024)?></td>
				<td>
					<?foreach($hash->retrieve_many('serial=?', $client->serial_number) AS $item):?>
						<?=$item->name?>
					<?endforeach?>
				</td>
		      </tr>
			<?endforeach?>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>