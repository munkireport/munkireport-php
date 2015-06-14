<?php $this->view('partials/head', array('stylesheets' => array('bootstrap-markdown.min.css'))) ?>

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
	'network-tab' => array('view' => 'client/network_tab', 'i18n' => 'client.tab.network', 'badge' => 'network-cnt'),
	'directory-tab' => array('view' => 'client/directory_tab', 'i18n' => 'client.tab.ds', 'badge' => 'directory-cnt'),
	'displays-tab' => array('view' => 'client/displays_tab', 'i18n' => 'client.tab.displays', 'badge' => 'displays-cnt'),
	'filevault-tab' => array('view' => 'client/filevault_tab', 'i18n' => 'client.tab.fv_escrow'),
	'power-tab' => array('view' => 'client/power_tab', 'i18n' => 'client.tab.power'),
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

<script>
	$(document).on('appReady', function(e, lang) {

		// Set table classes
		$('table').addClass('table table-condensed table-striped');

		// Set h4 classes
		$('h4').addClass('alert alert-info');

		// Fix for using a regular dropdown for tabs
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

			// Remove 'active' class from all li's
			$(e.target).closest('ul').children().removeClass('active');

			// Add 'active' to current li
			$(e.target).parent().addClass('active');
		  
		})

		// Get client data
		$.getJSON( appUrl + '/clients/get_data/' + serialNumber, function( data ) {

			machineData = data[0];

			// Set properties based on id
			$.each(machineData, function(prop, val){
				$('.mr-'+prop).html(val);
			});

			// Set computer name value and title
			$('.mr-computer_name_input')
				.val(machineData.computer_name)
				.attr('title', machineData.computer_name)
				.data('placement', 'bottom')
				.tooltip();

			// Format OS Version
			$('.mr-os_version').html(integer_to_version(machineData.os_version));


			// Format filesizes
			$('.mr-TotalSize').html(fileSize(machineData.TotalSize, 1));
			$('.mr-UsedSize').html(fileSize(machineData.TotalSize - machineData.FreeSpace, 1));
			$('.mr-FreeSpace').html(fileSize(machineData.FreeSpace, 1));

			// Smart status
			$('.mr-SMARTStatus').html(machineData.SMARTStatus);
			if(machineData.SMARTStatus == 'Failing'){
				$('.mr-SMARTStatus').addClass('label label-danger');
			}

			// Warranty status
			var cls = 'text-danger',
				msg = machineData.status
			switch (machineData.status) {
				case 'Supported':
					cls = 'text-success';
					msg = i18n.t("warranty.supported_until", {date:machineData.end_date});
					break;
				case 'No Applecare':
					cls = 'text-warning';
					msg = i18n.t("warranty.supported_no_applecare", {date:machineData.end_date});
					break;
				case 'Unregistered serialnumber':
					cls = 'text-warning';
					msg = i18n.t("warranty.unregistered");
					msg = msg + ' <a target="_blank" href="https://selfsolve.apple.com/RegisterProduct.do?productRegister=Y&amp;country=USA&amp;id='+machineData.serialNumber+'">Register</a>'
					break;
				case 'Expired':
					cls = 'text-danger';
					msg = i18n.t("warranty.expired", {date:machineData.end_date});
					break;
			}

			
			$('.mr-warranty_status').addClass(cls).html(msg);

			// Uptime
			if(machineData.uptime > 0){
				var uptime = moment((machineData.timestamp - machineData.uptime) * 1000);
				$('.mr-uptime').html('<time title="'+i18n.t('boot_time')+': '+uptime.format('LLLL')+'">'+uptime.fromNow(true)+'</time>');
			}else{
				$('.mr-uptime').html(i18n.t('unavailable'));
			}

			// Registration date
			var msecs = moment(machineData.reg_timestamp * 1000);
			$('.mr-reg_date').append('<time title="'+msecs.format('LLLL')+'" datetime="<?php echo $report->reg_timestamp; ?>">'+msecs.fromNow()+'</time>');

			// Check-in date
			var msecs = moment(machineData.timestamp * 1000);
			$('.mr-check-in_date').append('<time title="'+msecs.format('LLLL')+'" datetime="<?php echo $report->reg_timestamp; ?>">'+msecs.fromNow()+'</time>');

			// Set tooltips
			$( "time" ).each(function( index ) {
					$(this).tooltip().css('cursor', 'pointer');
			});
			
			// Remote control links
			$.getJSON( appUrl + '/clients/get_links', function( links ) {
				$.each(links, function(prop, val){
					$('#client_links').append('<li><a href="'+(val.replace(/%s/, machineData.remote_ip))+'">'+i18n.t('remote_control')+' ('+prop+')</a></li>');
				});
			});


		});

		// Get estimate_manufactured_date
		$.getJSON( appUrl + '/module/warranty/estimate_manufactured_date/' + serialNumber, function( data ) {
			$('.mr-manufacture_date').html(data.date)
		});

		// Get certificate data
		$.getJSON( appUrl + '/module/certificate/get_data/' + serialNumber, function( data ) {
			if(data.length)
			{
				var certs = $('<table>').addClass('table table-condensed table-striped');

				// Set headers
				certs.append($('<thead>')
						.append($('<tr>')
							.append($('<th>')
								.text(i18n.t('listing.certificate.commonname')))
							.append($('<th>')
								.text(i18n.t('listing.certificate.expires')))));

				// Load data
				$.each(data, function(index, cert){
					certs.append($('<tr>')
						.attr('title', cert.rs.cert_path)
						.append($('<td>')
							.text(cert.rs.cert_cn))
						.append($('<td>')
							.html(function(){
								var date = new Date(cert.rs.cert_exp_time * 1000);
								var diff = moment().diff(date, 'days');
								var cls = diff > 0 ? 'danger' : (diff > -90 ? 'warning' : 'success');
								return('<span class="label label-'+cls+'">'+moment(date).fromNow()+'</span>');
							})));
				});

				// Add tab
				var conf = {
					id: 'certificate-tab',
					linkTitle: i18n.t('client.tab.certificates'),
					tabTitle: i18n.t('client.tab.certificates'),
					tabContent: certs
				}
				addTab(conf);

				// Add tooltips
				$('tr[title]').tooltip();

				// Set correct tab on location hash
				loadHash();

			}
		});

		// Get services data
		$.getJSON( appUrl + '/module/service/get_data/' + serialNumber, function( data ) {
			if(data.length)
			{
				var content = $('<table>').addClass('table table-condensed table-striped');

				// Set headers
				content.append($('<thead>')
						.append($('<tr>')
							.append($('<th>')
								.text(i18n.t('service.name')))
							.append($('<th>')
								.text(i18n.t('service.status')))));

				// Load data
				$.each(data, function(index, service){
					content.append($('<tr>')
						.append($('<td>')
							.text(service.rs.service_name))
						.append($('<td>')
							.html(function(){
								var cls = "success";
								if(service.rs.service_state != 'running'){
									cls = "warning";
								}
								return '<span class="label label-'+cls+'">'+service.rs.service_state+'</span>';
							})));
				});

				// Add tab
				var conf = {
					id: 'service-tab',
					linkTitle: i18n.t('service.tab.title'),
					tabTitle: i18n.t('service.tab.title'),
					tabContent: content
				}
				addTab(conf);

				// Set correct tab on location hash
				loadHash();
				
			}
		});

		// Get timemachine data
		$.getJSON( appUrl + '/module/timemachine/get_data/' + serialNumber, function( data ) {
			if(data.id !== '')
			{
				$('table.mr-timemachine-table')
					.empty()
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('listing.timemachine.last_success')))
						.append($('<td>')
							.text(function(){
								if(data.last_success){
									return moment(data.last_success + 'Z').fromNow();
								}
							})))
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('listing.timemachine.last_failure')))
						.append($('<td>')
							.text(function(){
								if(data.last_failure){
									return moment(data.last_failure + 'Z').fromNow();
								}
							})))
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('listing.timemachine.last_failure_msg')))
						.append($('<td>')
							.text(data.last_failure_msg)))
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('listing.timemachine.duration')))
						.append($('<td>')
							.text(moment.duration(data.duration, "seconds").humanize())));
			}
			else{
				$('table.mr-timemachine-table')
					.empty()
					.append($('<tr>')
						.append($('<td>')
							.attr('colspan', 2)
							.text(i18n.t('no_data'))))
			}

		});


		// Get ARD data
		$.getJSON( appUrl + '/module/ard/get_data/' + serialNumber, function( data ) {
			$.each(data, function(index, item){
				if(/^Text[\d]$/.test(index))
				{
					$('#ard-data')
						.append($('<tr>')
							.append($('<th>')
								.text(index))
							.append($('<td>')
								.text(item)));
				}
			});
		});

		// Get Bluetooth data
		$.getJSON( appUrl + '/module/bluetooth/get_data/' + serialNumber, function( data ) {
			if(data.id !== '')
			{
				$('table.mr-bluetooth-table')
					.empty()
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('bluetooth.status')))
						.append($('<td>')
							.text(data.bluetooth_status)))
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('bluetooth.keyboard')))
						.append($('<td>')
							.text(data.keyboard_battery)))
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('bluetooth.mouse')))
						.append($('<td>')
							.text(data.mouse_battery)))
					.append($('<tr>')
						.append($('<th>')
							.text(i18n.t('bluetooth.trackpad')))
						.append($('<td>')
							.text(data.trackpad_battery)));
			}
			else{
				$('table.mr-bluetooth-table')
					.empty()
					.append($('<tr>')
						.append($('<td>')
							.attr('colspan', 2)
							.text(i18n.t('no_data'))))
			}

		});


		
		
		// Update hash in url
		var updateHash = function(e){
				var url = String(e.target)
				if(url.indexOf("#") != -1)
				{
					var hash = url.substring(url.indexOf("#"));
					// Save scroll position
					var yScroll=document.body.scrollTop;
					window.location.hash = '#tab_'+hash.slice(1);
					document.body.scrollTop=yScroll;
				}
			},
			loadHash = function(){
				// Activate correct tab depending on hash
				var hash = window.location.hash.slice(5);
				if(hash){
					$('.client-tabs a[href="#'+hash+'"]').tab('show');
				}
				else{
					$('.client-tabs a[href="#summary"]').tab('show');
				}
			},
			addTab = function(conf){

				// Add tab link
				$('.client-tabs .divider')
					.before($('<li>')
						.append($('<a>')
							.attr('href', '#'+conf.id)
							.attr('data-toggle', 'tab')
							.on('show.bs.tab', function(){
								// We have to remove the active class from the 
								// previous tab manually, unfortunately
								$('.client-tabs li').removeClass('active');
							})
							.on('shown.bs.tab', updateHash)
							.text(conf.linkTitle)));

				// Add tab
				$('div.tab-content')
					.append($('<div>')
						.attr('id', conf.id)
						.addClass('tab-pane')
						.append($('<h2>')
							.text(conf.tabTitle))
						.append(conf.tabContent));
			} // end addTab

		loadHash();

		// Update hash when changing tab
		$('a[data-toggle="tab"]').on('shown.bs.tab', updateHash)


	});
</script>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap-markdown.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/marked.min.js"></script>

<?php $this->view('partials/foot'); ?>
