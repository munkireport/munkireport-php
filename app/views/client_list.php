<?$this->view('partials/head')?>

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


  <h1>Munki Clients (<?=count($objects)?>)</h1>
  
  <table class="clientlist">
    <thead>
      <tr>
        <th>Client    </th>
        <th>User      </th>
        <th>IP        </th>
		<th>OS        </th>
        <th>Latest Run</th>
		<th>Manifest</th>
      </tr>
    </thead>
    <tbody>
	<?foreach($objects as $client):?>
      <tr>
        <?$url = url("show/report/$client->serial")?>
		<?$machine = new Machine($client->serial)?>
		<?$reportdata = new Reportdata($client->serial)?>
        <td>
			<?if($client->report_plist):?>
			<a href="<?=$url?>"><?=$machine->computer_name?></a>
			<?else:?>
			<?=$machine->computer_name?>
			<?endif?>
		</td>
		<td><?=$client->console_user?></td>
		<td><?=$client->remote_ip?></td>
		<td><?=isset($client->report_plist['MachineInfo']['os_vers']) ? $client->report_plist['MachineInfo']['os_vers'] : '?'?> <?=isset($client->report_plist['MachineInfo']['arch']) ? $client->report_plist['MachineInfo']['arch'] : '?'?></td>
		<td>
			<?=$client->timestamp?>
			<?=$reportdata->runtype?>
			<?=$reportdata->runstate?>
			<?if($client->errors):?>
            <span class="error">
              <a href="<?=$url?>#errors">
                <?=count($client->errors) . 'error' . (count($client->errors) > 1 ? 's' : '')?>
              </a>
            </span>
			<?endif?>
			<?if($client->warnings):?>
            <span class="warning">
              <a href="<?=$url?>#errors">
                <?=count($client->warnings) . 'warning' . (count($client->warnings) > 1 ? 's' : '')?>
              </a>
            </span>
			<?endif?>
		</td>
		<td>
			<?if($client->report_plist && isset($client->report_plist['ManifestName'])):?>
			<?=$client->report_plist['ManifestName']?>
			<?else:?>
			?
			<?endif?>
		</td>
      </tr>
	<?endforeach?>
    </tbody>
  </table>
<?$this->view('partials/foot')?>
