<?$obj = new View();$obj->view('partials/head')?>

<?$report_type = (object) array('name'=>'Munkireport', 'desc' => 'munkireport')?>
<?$this->view('partials/machine_info', array('report_type' => $report_type))?>

<h2 id="errors">Errors &amp; Warnings</h2>

<?if($report['Errors'] OR $report['Warnings']):?>
  
	<?if($report['Errors']):?>
		<pre class="error"><?=implode("\n", $report['Errors'])?></pre>
	<?endif?>
	
	<?if($report['Warnings']):?>
	<pre class="warning"><?=implode("\n", $report['Warnings'])?></pre>
	<?endif?>
	
<?else:?>    
	<p><i>No errors or warnings</i></p>
<?endif?>

<?$package_tables = array(	'Apple Updates' =>'AppleUpdates',
							'Active Installs' => 'ItemsToInstall',
							'Active Removals' => 'ItemsToRemove',
							'Problem Installs' => 'ProblemInstalls')?>

<!--! Package tables -->
<?foreach($package_tables AS $title => $report_key):?>
  <h2><?=$title?></h2>
  
	<?if(isset($report[$report_key]) && $report[$report_key]):?>
	<table class="client_info">
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
          <td style="text-align: right;"><?=isset($item['installed_size']) ? humanreadablesize($item['installed_size'] * 1024): '?'?></td>
          <td><?=isset($item['install_result']) ? $item['install_result'] : (isset($item['installed']) && $item['installed'] ? 'installed' : "not installed")?></td>
        </tr>
		<?endforeach?>
      </tbody>
    </table>
    <?else:?>
      <p><i>No <?=strtolower($title)?></i></p>
	<?endif?>
<?endforeach?>

<?$package_tables = array(	'Managed Installs' =>'ManagedInstalls')?>

<table class="twocol">
  <tbody>
    <td>
		<?foreach($package_tables AS $title => $report_key):?>
		  <h2><?=$title?></h2>

			<?if(isset($report[$report_key]) && $report[$report_key]):?>
			<table class="client_info">
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
		          <td style="text-align: right;"><?=isset($item['installed_size']) ? humanreadablesize($item['installed_size'] * 1024): '?'?></td>
		          <td><?=$item['installed'] ? 'installed' : "not installed"?></td>
		        </tr>
				<?endforeach?>
		      </tbody>
		    </table>
		    <?else:?>
		      <p><i>No <?=strtolower($title)?></i></p>
			<?endif?>
		<?endforeach?>
    </td>
    <td>
    
<?if(isset($report['managed_uninstalls_list'])):?>
  <h2>Managed Uninstalls</h2>

  <table class="client_info">
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

    </td>
  </tbody>
</table>

<!--! test code below -->

<?/*
    import pprint
    if True:
        for k in ("MachineInfo",
                  "ConsoleUser",
                  "AvailableDiskSpace",
                  "ManagedInstallVersion",
                  "ManifestName",
                  "RunType",
                  "StartTime",
                  "EndTime",
                  "Errors",
                  "Warnings",
                  "InstallResults",
                  "ItemsToInstall",
                  "ItemsToRemove",
                  "RemovalResults",
                  "ManagedInstalls",
                  "RemovedItems",
                  "ProblemInstalls",
                  'AppleUpdates',
                  'InstalledItems',
                  'RestartRequired',
                  'managed_installs_list',
                  'managed_uninstalls_list',
                  'managed_updates_list'):
            try:
                del report[k]
            except:
                pass
    pretty_report = pprint.pformat(report)
*/?>

  <h2>Debug</h2>
  <pre py:content="pretty_report">
    <?print_r($report)?>
  </pre>
<?$obj = new View();$obj->view('partials/foot')?>
