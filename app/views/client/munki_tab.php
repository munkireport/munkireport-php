<?php $client = new Munkireport_model($serial_number); ?>
<?php $munkiinfo = new munkiinfo_model($serial_number); ?>

<?php $report = $client->report_plist; ?>

<?php if( ! $report): ?>
	<p><i>No Munkireport data</i></p>
	<?php return; ?>
<?php endif; ?>

<div class="row">

		<div class="col-lg-6">

		<h2 id="errors">Errors &amp; Warnings</h2>

		<?php if($client->report_plist['Errors'] OR $client->report_plist['Warnings']): ?>
		  
			<?php if($client->report_plist['Errors']): ?>
				<pre class="alert alert-danger">• <?php echo implode("\n• ", $client->report_plist['Errors']); ?></pre>
			<?php endif; ?>
			
			<?php if($client->report_plist['Warnings']): ?>
				<pre class="alert alert-warning">• <?php echo implode("\n• ", $client->report_plist['Warnings']); ?></pre>
			<?php endif; ?>
			
		<?php else: ?>
			<p><i>No errors or warnings</i></p>
		<?php endif ?>

	</div><!-- </div class="col-lg-6"> -->

	<div class="col-lg-6">

		<h2>Munki</h2>
		<table class="table table-striped">
			<tr>
				<th>Version:</th>
				<td><?php echo $client->version; ?></td>
			</tr>
			<tr>
				<th>SoftwareRepoURL:</th>
				<td><?php echo $munkiinfo->softwarerepourl; ?></td>
			</tr>
			<tr>
				<th>AppleCatalogURL:</th>
				<td><?php echo $munkiinfo->applecatalogurl; ?></td>
			</tr>
			<tr>
				<th>Manifest:</th>
				<td><?php echo $client->manifestname; ?></td>
			</tr>
			<tr>
				<th>LocalOnlyManifest:</th>
				<td><?php echo $munkiinfo->localonlymanifest; ?></td>
			</tr>
			<tr>
				<th>Run Type:</th>
				<td><?php echo $client->runtype; ?></td>
			</tr>
			<tr>
				<th>Start:</th>
				<td><time datetime="<?php echo $client->starttime; ?>"></time></td>
			</tr>
			<tr>
				<?php $duration = strtotime($client->endtime) - strtotime($client->starttime); ?>
				<th>Duration:</th>
				<td><?php echo $duration; ?> seconds</td>
			</tr>
		</table>
	</div><!-- </div class="col-lg-6"> -->
	
	<!-- <Additional Munki Info> -->
	<style>
		/* Popover */
		.popover {
		  	border-bottom:1px solid #ebebeb;
		  	-webkit-border-radius:5px 5px 0 0;
    	  	-moz-border-radius:5px 5px 0 0;
    	  	border-radius:5px 5px 0 0;
		  	width:550px;
		}
		.munkiinfo {
			position: relative;
			top: -15px;
			left: 15px;
		}
	</style>
	<button class="btn btn-info btn-sm munkiinfo">
	<a data-container="body" 
		data-toggle="popover" 
		data-html="true"
		data-placement="right" 
		data-trigger="click"
		data-content='
			<table class="table table-striped">
			<caption>Additional Munki Info</caption>
				<tr>
					<th>AppleSoftwareUpdatesOnly:</th>
					<?php if($munkiinfo->applesoftwareupdatesonly == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>CatalogURL:</th>
					<td><?php echo $munkiinfo->catalogurl; ?></td>
				</tr>
				<tr>
					<th>ClientCertificatePath:</th>
					<td><?php echo $munkiinfo->clientcertificatepath; ?></td>
				</tr>
				<tr>
					<th>ClientIdentifier:</th>
					<td><?php echo $munkiinfo->clientidentifier; ?></td>
				</tr>
				<tr>
					<th>ClientKeyPath:</th>
					<td><?php echo $munkiinfo->clientkeypath; ?></td>
				</tr>
				<tr>
					<th>ClientResourcesFileName:</th>
					<td><?php echo $munkiinfo->clientresourcesfilename; ?></td>
				</tr>
				<tr>
					<th>ClientResourceURL:</th>
					<td><?php echo $munkiinfo->clientresourceurl; ?></td>
				</tr>
				<tr>
					<th>DaysBetweenNotifications:</th>
					<td><?php echo $munkiinfo->daysbetweennotifications; ?></td>
				</tr>
				<tr>
					<th>FollowHTTPRedirects:</th>
					<?php if($munkiinfo->followhttpredirects = 'None'): ?>
						<td><?php echo 'Value not set'; ?></td>
					<?php else: ?>
						<td><?php echo $munkiinfo->followhttpredirects; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>HelpURL:</th>
					<td><?php echo $munkiinfo->helpurl; ?></td>
				</tr>
				<tr>
					<th>IconURL:</th>
					<td><?php echo $munkiinfo->iconurl; ?></td>
				</tr>
				<tr>
					<th>InstallAppleSoftwareUpdates:</th>
					<?php if($munkiinfo->installapplesoftwareupdates == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>InstallRequiresLogout:</th>
					<?php if($munkiinfo->installrequireslogout == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>LocalOnlyManifest:</th>
					<td><?php echo $munkiinfo->localonlymanifest; ?></td>
				</tr>
				<tr>
					<th>LogFile:</th>
					<td><?php echo $munkiinfo->logfile; ?></td>
				</tr>
				<tr>
					<th>LoggingLevel:</th>
					<td><?php echo $munkiinfo->logginglevel; ?></td>
				</tr>	
				<tr>
					<th>LogToSysLog:</th>
					<?php if($munkiinfo->logtosyslog == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>			
				<tr>
					<th>ManagedInstallDir:</th>
					<td><?php echo $munkiinfo->managedinstalldir; ?></td>
				</tr>
				<tr>
					<th>ManifestURL:</th>
					<td><?php echo $munkiinfo->manifesturl; ?></td>
				</tr>
				<tr>
					<th>MSUDebugLogEnabled:</th>
					<td><?php echo $munkiinfo->msudebuglogenabled; ?></td>
				</tr>
				<tr>
					<th>MSULogEnabled:</th>
					<td><?php echo $munkiinfo->msulogenabled; ?></td>
				</tr>
				<tr>
					<th>Protocol:</th>
					<td><?php echo $munkiinfo->munkiprotocol; ?></td>
				</tr>
				<tr>
					<th>PackageURL:</th>
					<td><?php echo $munkiinfo->packageurl; ?></td>
				</tr>
				<tr>
					<th>PackageVerificationMode:</th>
					<td><?php echo $munkiinfo->packageverificationmode; ?></td>
				</tr>
				<tr>
					<th>ShowRemovalDetail:</th>
					<?php if($munkiinfo->showremovaldetail == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>SoftwareRepocaCertificate:</th>
					<td><?php echo $munkiinfo->softwarerepocacertificate; ?></td>
				</tr>
				<tr>
					<th>SoftwareRepoCAPath:</th>
					<td><?php echo $munkiinfo->softwarerepocapath; ?></td>
				</tr>
				<tr>
					<th>SoftwareRepoURL:</th>
					<td><?php echo $munkiinfo->softwarerepourl; ?></td>
				</tr>
				<tr>
					<th>SoftwareUpdateServerURL (depreciated):</th>
					<td><?php echo $munkiinfo->softwareupdateserverurl; ?></td>
				</tr>
				<tr>
					<th>SuppressAutoInstall:</th>
					<?php if($munkiinfo->suppressautoinstall == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>SuppressLoginWindowInstall:</th>
					<?php if($munkiinfo->suppressloginwindowinstall == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>SuppressStopButtonOnInstall:</th>
					<?php if($munkiinfo->suppressstopbuttononinstall == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>SuppressUserNotification:</th>
					<?php if($munkiinfo->suppressusernotification == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>UnattendedAppleUpdates:</th>
					<?php if($munkiinfo->unattendedappleupdates = 'None'): ?>
						<td><?php echo 'Value not set'; ?></td>
					<?php else: ?>
						<td><?php echo $munkiinfo->followhttpredirects; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>UseClientCertificate:</th>
					<?php if($munkiinfo->useclientcertificate == true): ?>
						<td><?php echo 'True'; ?></td>
					<?php else: ?>
						<td><?php echo 'False'; ?></td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>UseClientCertificateCNAsClientIdentifier:</th>
					<td><?php echo $munkiinfo->useclientcertificatecnasclientidentifier; ?></td>
				</tr>	
			</table>
		'><b><font color="white">Additional Munki Info</font></b></a>	</button>
	<!-- </Additional Munki Info> -->


	<script>
			$(document).on('appReady', function(e, lang) {
				$( "table time" ).each(function( index ) {
					$(this).html(moment($(this).attr('datetime'), "YYYY-MM-DD HH:mm:ss Z").fromNow());
				});
			});
	</script>

<?php // Move install results over to their install items.
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
			$dversion = $report[$r_item][$key]["name"].'-'.$report[$r_item][$key]["version_to_install"];
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
			$dversion = $item["name"].'-'.$item["version_to_install"];
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
		$dversion = $report['ItemsToRemove'][$key]["name"];
		if(isset($removal_results[$dversion]))
		{
			$item['install_result'] = $removal_results[$dversion]['result'];
		}
	}
	unset($item);
}
?>

<?php $package_tables = array(	'Apple Updates' =>'AppleUpdates',
							'Active Installs' => 'ItemsToInstall',
							'Active Removals' => 'ItemsToRemove',
							'Problem Installs' => 'ProblemInstalls'); ?>

<!--! Package tables -->
<?php foreach($package_tables AS $title => $report_key): ?>
	<div class="col-lg-6">
		  <h2><?php echo $title; ?></h2>
		  
			<?php if(isset($report[$report_key]) && $report[$report_key]): ?>
			<table class="table table-striped">
		      <thead>
		        <tr>
		          <th>Name</th>
		          <th>Size</th>
		          <th>Status</th>
		        </tr>
		      </thead>
		      <tbody>
				<?php foreach($report[$report_key] AS $item): ?>
		        <tr>
		          <td>
					<?php echo isset($item['display_name']) && $item['display_name'] ? $item['display_name'] : $item['name']; ?>
					<?php echo isset($item['version_to_install']) ? $item['version_to_install'] : ''; ?>
					<?php echo isset($item['installed_version']) ? $item['installed_version'] : ''; ?>
		          </td>
		          <td class="filesize" style="text-align: left;"><?php echo isset($item['installed_size']) ? $item['installed_size'] * 1024: '?'; ?></td>
		          <td><?php echo isset($item['install_result']) ? $item['install_result'] : (isset($item['installed']) && $item['installed'] ? 'installed' : "not installed"); ?></td>
		        </tr>
				<?php endforeach; ?>
		      </tbody>
		    </table>
		    <?php else: ?>
		      <p><i>No <?php echo strtolower($title); ?></i></p>
			<?php endif ?>
	</div><!-- </div class="col-lg-6"> -->
<?php endforeach; ?>

  </div><!-- </div class="row"> -->
  
  <div class="row">

<?php $package_tables = array(	'Managed Installs' =>'ManagedInstalls'); ?>

	<div class="col-lg-6">
		<?php foreach($package_tables AS $title => $report_key): ?>
		  <h2><?php echo $title; ?></h2>

			<?php if(isset($report[$report_key]) && $report[$report_key]): ?>
			<table class="table table-striped <?php echo $report_key; ?>">
		      <thead>
		        <tr>
		          <th>Name</th>
		          <th>Size</th>
		          <th>Status</th>
		        </tr>
		      </thead>
		      <tbody>
				<?php foreach($report[$report_key] AS $item): ?>
		        <tr>
		          <td>
					<?php echo isset($item['display_name']) ? $item['display_name'] : $item['name']; ?>
					<?php echo isset($item['version_to_install']) ? $item['version_to_install'] : ''; ?>
					<?php echo isset($item['installed_version']) ? $item['installed_version'] : ''; ?>
		          </td>
		          <td style="text-align: left;"><?php echo isset($item['installed_size']) ? $item['installed_size'] * 1024: 0; ?></td>
		          <td><?php echo $item['installed'] ? 'installed' : "not installed"; ?></td>
		        </tr>
				<?php endforeach; ?>
		      </tbody>
		    </table>
		    <?php else: ?>
		      <p><i>No <?php echo strtolower($title); ?></i></p>
			<?php endif; ?>
		<?php endforeach; ?>
    </div><!-- </div class="col-lg-6"> -->

    <div class="col-lg-6">
    
		<?php if(isset($report['managed_uninstalls_list'])): ?>
		  <h2>Managed Uninstalls</h2>

		  <table class="table table-striped">
		    <thead>
		      <tr>
		        <th>Name</th>
		      </tr>
		    </thead>
		    <tbody>
			<?php foreach($report['managed_uninstalls_list'] AS $item): ?>
		      <tr>
		        <td>
		          <?php echo $item; ?>
		        </td>
		      </tr>
			<?php endforeach; ?>
		    </tbody>
		  </table>
		<?php endif; ?>

    </div><!-- </div class="col-lg-6"> -->

  </div><!-- </div class="row"> -->

<pre><?php //print_r($client->rs) ?></pre>
<script>
// Popup Close button - Credit to https://jsfiddle.net/backos/T5Bev/
$.fn.extend({
    popoverClosable: function (options) {
        var defaults = {
            template:
                '<div class="popover">\
<div class="arrow"></div>\
<div class="popover-header">\
<button type="button" class="close" data-dismiss="popover" aria-hidden="true">&times;</button>\
<h3 class="popover-title"></h3>\
</div>\
<div class="popover-content"></div>\
</div>'
        };
        options = $.extend({}, defaults, options);
        var $popover_togglers = this;
        $popover_togglers.popover(options);
        $popover_togglers.on('click', function (e) {
            e.preventDefault();
            $popover_togglers.not(this).popover('hide');
        });
        $('html').on('click', '[data-dismiss="popover"]', function (e) {
            $popover_togglers.popover('hide');
        });
    }
});

$(function () {
    $('[data-toggle="popover"]').popoverClosable();
});
// Popup close button

$(document).on('appReady', function(e, lang) {

	// Format filesize
	$('td.filesize').each(function(index, el){
		var size = $(el).html();
		if(size != '?'){
			$(el).html(fileSize(size))
		}
	});

	// Initialize datatables
	$('.ManagedInstalls').dataTable({
	    serverSide: false,
	    order: [0,'asc'],
	    createdRow: function( nRow, aData, iDataIndex ) {
	    	// Update name in first column to link
	    	var size=$('td:eq(1)', nRow).html();
	        $('td:eq(1)', nRow).html(fileSize(size, 1));

	    }
	});
});
</script>
