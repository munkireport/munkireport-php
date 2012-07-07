<?if(! isset($clients)) return?>
<table class="clientlist">
  <thead>
    <tr>
      <th>Client     </th>
      <th>User       </th>
      <th>IP         </th>
      <th>Latest Run </th>
    </tr>
  </thead>
  <tbody>
	<?foreach($clients AS $client):?>
    <tr>
      	<?$url = url("show/report/$client->serial")?>
		<?$machine = new Machine($client->serial)?>
        <td>
			<?if($client->report_plist):?>
			<a href="<?=$url?>"><?=$machine->computer_name?></a>
			<?else:?>
			<?=$machine->computer_name?>
			<?endif?>
		</td>
		<td><?=$client->console_user?></td>
		<td><?=$client->remote_ip?></td>
      <td>
		<?=$client->timestamp?>
		<?=$client->runtype?>
		<?=$client->runstate?>
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
    </tr>
	<?endforeach?>
  </tbody>
</table>
