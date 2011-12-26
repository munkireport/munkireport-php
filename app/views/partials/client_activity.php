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
      	<?$url = "/show/report/$client->serial"?>
        <td>
			<?if($client->report_plist):?>
			<a href="<?=$url?>"><?=$client->name?></a>
			<?else:?>
			<?=$client->name?>
			<?endif?>
		</td>
		<td><?=$client->console_user?></td>
		<td><?=$client->remote_ip?></td>
		<?
			$failed_installs = 0;
			// Check results for failed installs
			if(isset($client->activity['InstallResults']))
			{
				foreach($client->activity['InstallResults'] as $result)
				{
					if($result["status"])
					{
						$failed_installs++;
					}
				}
			}
			$install_items = isset($client->activity['ItemsToInstall']) ? count($client->activity['ItemsToInstall']) : 0;
			$install_results = isset($client->activity['InstallResults']) ? count($client->activity['InstallResults']) : 0;
			$removal_items = isset($client->activity['ItemsToRemove']) ? count($client->activity['ItemsToRemove']) : 0;
			$removal_results = isset($client->activity['RemovalResults']) ? count($client->activity['RemovalResults']) : 0;
			$apple_updates = isset($client->activity['AppleUpdateList']) ? count($client->activity['AppleUpdateList']) : 0;
			$pending_installs = max(($install_items + $apple_updates) - $install_results, 0);
            $pending_removals = $removal_items - $removal_results;
			$install_results -= $failed_installs;
		?>
        <td>
			<?=$client->timestamp?>
          &nbsp;
			<?if(isset($client->activity['Updating'])):?>
            	<?=$client->runtype?> run in progress
			<?endif?>
			<?if($pending_installs):?>
            	<?=$pending_installs?> pending install<?=$pending_installs > 1 ? 's' : ''?>
			<?endif?>
			<?if($install_results):?>
				<?=$install_results?> package<?=$install_results > 1 ? 's' : ''?> installed
       		<?endif?>
			<?if($failed_installs):?>
				<span class="error"><?=$failed_installs?> package<?=$failed_installs > 1 ? 's' : ''?> failed</span>
       		<?endif?>
			<?if($pending_removals):?>
				<?=$pending_removals?> pending removal<?=$pending_removals > 1 ? 's' : ''?>
			<?endif?>
			<?if($removal_results):?>
				<?=$removal_results?> package<?=$removal_results > 1 ? 's' : ''?> removed
			<?endif?>
        </td>
    </tr>
	<?endforeach?>
  </tbody>
</table>
