<?$this->view('partials/head')?>

<div class="container">

  <div class="row">

  	<div class="span12">
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('.table').dataTable({
		            "iDisplayLength": 25,
		            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
					"bStateSave": true,
		            "aaSorting": [[3,'desc']],
				});
			} );
		</script>

		<?$warranty = new Warranty()?>

		  <h1>Warranty Reports (<?=$warranty->count()?>)</h1>
		  
		  <table class="table table-striped">
		    <thead>
		      <tr>
		        <th>Client    </th>
		        <th>Serial    </th>
		        <th>Hostname   </th>
		        <th>Status</th>
				<th>End date</th>
		      </tr>
		    </thead>
		    <tbody>
			<?foreach($warranty->retrieve_many() as $client):?>
			<?$class = $client->status == 'Expired' ? 'error' : ($client->status == 'Supported' ? 'success' : 'warning')?>
		      <tr class="<?=$class?>">
				<?$machine = new Machine($client->sn)?>
		        <td>
					<a href="<?=url("clients/detail/$client->sn")?>"><?=$machine->computer_name?></a>
				</td>
				<td><?=$machine->serial_number?></td>
				<td><?=$machine->hostname?></td>
				<td><?=$client->status?></td>
				<td><?=$client->end_date?></td>

		      </tr>
			<?endforeach?>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>