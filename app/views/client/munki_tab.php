<?$client = new Munkireport_model($serial_number)?>

<?$report = $client->report_plist?>

<?if( ! $report):?>
	<p><i>No Munkireport data</i></p>
	<?return?>
<?endif?>

<div class="row">

		<div class="col-lg-6">

		<h2 id="errors">Errors &amp; Warnings</h2>

		<?if($client->report_plist['Errors'] OR $client->report_plist['Warnings']):?>
		  
			<?if($client->report_plist['Errors']):?>
				<pre class="alert alert-danger">• <?=implode("\n• ", $client->report_plist['Errors'])?></pre>
			<?endif?>
			
			<?if($client->report_plist['Warnings']):?>
				<pre class="alert alert-warning">• <?=implode("\n• ", $client->report_plist['Warnings'])?></pre>
			<?endif?>
			
		<?else:?>    
			<p><i>No errors or warnings</i></p>
		<?endif?>

	</div><!-- </div class="col-lg-6"> -->

	<div class="col-lg-6">

		<h2>Munki</h2>
		<table class="table table-striped">
			<tr>
				<th>Version:</th>
				<td><?=$client->version?></td>
			</tr>
			<tr>
				<th>Manifest:</th>
				<td><?=$client->manifestname?></td>
			</tr>
			<tr>
				<th>Run Type:</th>
				<td><?=$client->runtype?></td>
			</tr>
			<tr>
				<th>Start:</th>
				<td><time datetime="<?=$client->starttime?>"></time></td>
			</tr>
			<tr>
				<?$duration = strtotime($client->endtime) - strtotime($client->starttime);?>
				<th>Duration:</th>
				<td><?=$duration?> seconds</td>
			</tr>
		</table>

	</div><!-- </div class="col-lg-6"> -->

	<script>
			$(document).on('appReady', function(e, lang) {
				$( "table time" ).each(function( index ) {
					$(this).html(moment($(this).attr('datetime'), "YYYY-MM-DD HH:mm:ss Z").fromNow());
				});
			});
	</script>

<?// Move install results over to their install items.
$install_results = array();
if(isset($report['InstallResults']))
{
	foreach($report['InstallResults'] as $result)
	{
		$install_results[$result["name"] . '-' .$result["version"]] = 
			array('result' => $result["status"] == 0 ? 'Installed' : 'error');
	}
}
foreach(array('ItemsToInstall', 'AppleUpdates') AS $r_item)
{
	if(isset($report[$r_item]))
	{
		foreach($report[$r_item] as $key => &$item)
		{
			$item['install_result'] = 'Pending';
			$dversion = $report[$r_item][$key]["display_name"].'-'.$report[$r_item][$key]["version_to_install"];
			if(isset($install_results[$dversion]))
			{
				$item['install_result'] = $install_results[$dversion]['result'];
			}
		}
		unset($item);
	}
}		

// Move install results to managed installs
if(isset($report['ManagedInstalls']))
{
	foreach($report['ManagedInstalls'] as $key => $item)
	{
		if(isset($item["version_to_install"]))
		{
			$dversion = $item["display_name"].'-'.$item["version_to_install"];
			if(isset($install_results[$dversion]) && $install_results[$dversion]['result'] == 'Installed')
			{
				$report['ManagedInstalls'][$key]['installed'] = TRUE;
			}
		}
	}
}

// Move removal results over to their removal items.
$removal_results = array();
if(isset($report['RemovalResults']))
{
	foreach($report['RemovalResults'] as $result)
	{
		if(is_string($result) && preg_match('/^Removal of (.+): (.+)$/', $result, $matches))
		{
			$removal_results[$matches[1]]['result'] = $matches[2] == 'SUCCESSFUL' ? 'Removed' : $matches[2];
		}
	}
}
if(isset($report['ItemsToRemove']))
{
	foreach($report['ItemsToRemove'] as $key => &$item)
	{
		$item['install_result'] = 'Pending';
		$dversion = $report['ItemsToRemove'][$key]["display_name"];
		if(isset($removal_results[$dversion]))
		{
			$item['install_result'] = $removal_results[$dversion]['result'];
		}
	}
	unset($item);
}
?>

<?$package_tables = array(	'Apple Updates' =>'AppleUpdates',
							'Active Installs' => 'ItemsToInstall',
							'Active Removals' => 'ItemsToRemove',
							'Problem Installs' => 'ProblemInstalls')?>

<!--! Package tables -->
<?foreach($package_tables AS $title => $report_key):?>
	<div class="col-lg-6">
		  <h2><?=$title?></h2>
		  
			<?if(isset($report[$report_key]) && $report[$report_key]):?>
			<table class="table table-striped">
		      <thead>
		        <tr>
		          <th>Name</th>
		          <th>Size</th>
		          <th>Status</th>
		        </tr>
		      </thead>
		      <tbody>
				<?foreach($report[$report_key] AS $item):?>
		        <tr>
		          <td>
					<?=isset($item['display_name']) ? $item['display_name'] : $item['name']?>
					<?=isset($item['version_to_install']) ? $item['version_to_install'] : ''?>
					<?=isset($item['installed_version']) ? $item['installed_version'] : ''?>
		          </td>
		          <td style="text-align: left;"><?=isset($item['installed_size']) ? humanreadablesize($item['installed_size'] * 1024): '?'?></td>
		          <td><?=isset($item['install_result']) ? $item['install_result'] : (isset($item['installed']) && $item['installed'] ? 'installed' : "not installed")?></td>
		        </tr>
				<?endforeach?>
		      </tbody>
		    </table>
		    <?else:?>
		      <p><i>No <?=strtolower($title)?></i></p>
			<?endif?>
	</div><!-- </div class="col-lg-6"> -->
<?endforeach?>

  </div><!-- </div class="row"> -->
  
  <div class="row">

<?$package_tables = array(	'Managed Installs' =>'ManagedInstalls')?>

	<div class="col-lg-6">
		<?foreach($package_tables AS $title => $report_key):?>
		  <h2><?=$title?></h2>

			<?if(isset($report[$report_key]) && $report[$report_key]):?>
			<table class="table table-striped">
		      <thead>
		        <tr>
		          <th>Name</th>
		          <th>Size</th>
		          <th>Status</th>
		        </tr>
		      </thead>
		      <tbody>
				<?foreach($report[$report_key] AS $item):?>
		        <tr>
		          <td>
					<?=isset($item['display_name']) ? $item['display_name'] : $item['name']?>
					<?=isset($item['version_to_install']) ? $item['version_to_install'] : ''?>
					<?=isset($item['installed_version']) ? $item['installed_version'] : ''?>
		          </td>
		          <td style="text-align: left;"><?=isset($item['installed_size']) ? humanreadablesize($item['installed_size'] * 1024): '?'?></td>
		          <td><?=$item['installed'] ? 'installed' : "not installed"?></td>
		        </tr>
				<?endforeach?>
		      </tbody>
		    </table>
		    <?else:?>
		      <p><i>No <?=strtolower($title)?></i></p>
			<?endif?>
		<?endforeach?>
    </div><!-- </div class="col-lg-6"> -->

    <div class="col-lg-6">
    
		<?if(isset($report['managed_uninstalls_list'])):?>
		  <h2>Managed Uninstalls</h2>

		  <table class="table table-striped">
		    <thead>
		      <tr>
		        <th>Name</th>
		      </tr>
		    </thead>
		    <tbody>
			<?foreach($report['managed_uninstalls_list'] AS $item):?>
		      <tr>
		        <td>
		          <?=$item?>
		        </td>
		      </tr>
			<?endforeach?>
		    </tbody>
		  </table>
		<?endif?>

    </div><!-- </div class="col-lg-6"> -->

  </div><!-- </div class="row"> -->

<pre><?//print_r($client->rs)?></pre>