<?php $this->view('partials/head', array('stylesheets' => array('bootstrap-markdown.min.css', 'bootstrap-tagsinput.css'))) ?>

<?php

// Tab list, each item should contain:
//	'view' => path/to/tab
// 'i18n' => i18n identifier matching a localised name
// Optionally:
// 'view_vars' => array with variables to pass to the views
// 'badge' => id of a badge for this tab
$tab_list = array(
	'summary' => array('view' => 'client/summary_tab', 'i18n' => 'client.tab.summary'),
	'munki' => array('view' => 'client/munki_tab', 'i18n' => 'client.tab.munki'),
	'apple-software' => array('view' => 'client/install_history_tab', 'view_vars' => array('apple'=> 1), 'i18n' => 'client.tab.apple_software', 'badge' => 'history-cnt-1'),
	'third-party-software' => array('view' => 'client/install_history_tab', 'view_vars' => array('apple'=> 0), 'i18n' => 'client.tab.third_party_software', 'badge' => 'history-cnt-0'),
	'inventory-items' => array('view' => 'client/inventory_items_tab', 'i18n' => 'client.tab.inventory_items', 'badge' => 'inventory-cnt'),
	'location-tab' => array('view' => 'client/location_tab', 'i18n' => 'client.tab.location'),
	'network-tab' => array('view' => 'client/network_tab', 'i18n' => 'client.tab.network', 'badge' => 'network-cnt'),
	'directory-tab' => array('view' => 'client/directory_tab', 'i18n' => 'client.tab.ds', 'badge' => 'directory-cnt'),
	'displays-tab' => array('view' => 'client/displays_tab', 'i18n' => 'client.tab.displays', 'badge' => 'displays-cnt'),
	'filevault-tab' => array('view' => 'client/filevault_tab', 'i18n' => 'client.tab.fv_escrow'),
	'gsx-tab' => array('view' => 'client/gsx_tab', 'i18n' => 'client.tab.gsx'),
	'power-tab' => array('view' => 'client/power_tab', 'i18n' => 'client.tab.power'),
	'printer-tab' => array('view' => 'client/printer_tab', 'i18n' => 'client.tab.printers', 'badge' => 'printer-cnt'),
	'profile-tab' => array('view' => 'client/profile_tab', 'i18n' => 'client.tab.profiles')
		);

// Add custom tabs
$tab_list = array_merge($tab_list, conf('client_tabs', array()));

?>

<script>
	var serialNumber = '<?php echo $serial_number?>';
</script>

<div class="container">
	<div class="row">

		<div class="col-lg-12">

			<div class="input-group">

				<div class="input-group-btn">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span data-i18n="show" class="hidden-sm hidden-xs"></span>
						<i class="fa fa-list fa-fw"></i>
					</button>
					<ul class="dropdown-menu client-tabs" role="tablist">
							<?foreach($tab_list as $name => $data):?>

								<li>
									<a href="#<?php echo $name?>" data-toggle="tab"><span data-i18n="<?php echo $data['i18n']?>"></span>
									<?php if(isset($data['badge'])):?>
									 <span id="<?php echo $data['badge']?>" class="badge">0</span>
									<?php endif?>
									</a>
								</li>

							<?endforeach?>

							<li class="divider"></li>
						</ul>
				</div><!-- /btn-group -->

				<input type="text" class="form-control mr-computer_name_input" readonly>

				<div class="input-group-btn">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span data-i18n="remote_control" class="hidden-sm hidden-xs"></span>
						<i class="fa fa-binoculars fa-fw"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right" role="tablist" id="client_links">
					</ul>
				</div><!-- /btn-group -->

			</div>

		</div><!-- /col -->

	</div><!-- /row -->
	<div class="row">
		<div class="col-lg-12">

			<div class="tab-content">

			<?foreach($tab_list as $name => $data):?>

				<div class="tab-pane <?if(isset($data['class'])):?>active<?endif?>" id='<?php echo $name?>'>
					<?php $this->view($data['view'], isset($data['view_vars'])?$data['view_vars']:array(), isset($data['view_path'])?$data['view_path']:VIEW_PATH);?>
				</div>

			<?endforeach?>

			</div>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap-markdown.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/typeahead.bundle.min.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/marked.min.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.comment.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.storageplot.js"></script>

<?php $this->view('partials/foot'); ?>
