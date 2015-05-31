<?php $this->view('partials/head', array('stylesheets' => array('bootstrap-markdown.min.css'))) ?>

<?php 

// Tab list, each item should contain: 
//	'view' => path/to/tab
// 'i18n' => i18n identifier matching a localised name
// Optionally:
// 'view_vars' => array with variables to pass to the views
// 'badge' => id of a badge for this tab
$tab_list = array(
	'summary' => array('view' => 'client/machine_info', 'i18n' => 'client.tab.summary'),
	'munki' => array('view' => 'client/munki_tab', 'i18n' => 'client.tab.munki'),
	'serverstats' => array('view_path' => MODULE_PATH . 'servermetrics/views/serverstats_tab', 'i18n' => 'client.tab.serverstats'),
	'apple-software' => array('view' => 'client/install_history_tab', 'view_vars' => array('apple'=> 1), 'i18n' => 'client.tab.apple_software', 'badge' => 'history-cnt-1'),
	'third-party-software' => array('view' => 'client/install_history_tab', 'view_vars' => array('apple'=> 0), 'i18n' => 'client.tab.third_party_software', 'badge' => 'history-cnt-0'),
	'inventory-items' => array('view' => 'client/inventory_items_tab', 'i18n' => 'client.tab.inventory_items', 'badge' => 'inventory-cnt'),
	'network-tab' => array('view' => 'client/network_tab', 'i18n' => 'client.tab.network', 'badge' => 'network-cnt'),
	'directory-tab' => array('view' => 'client/directory_tab', 'i18n' => 'client.tab.ds', 'badge' => 'directory-cnt'),
	'displays-tab' => array('view' => 'client/displays_tab', 'i18n' => 'client.tab.displays', 'badge' => 'displays-cnt'),
	'filevault-tab' => array('view' => 'client/filevault_tab', 'i18n' => 'client.tab.fv_escrow'),
	'bluetooth-tab' => array('view' => 'client/bluetooth_tab', 'i18n' => 'client.tab.bluetooth'),
	'power-tab' => array('view' => 'client/power_tab', 'i18n' => 'client.tab.power'),
	'profile-tab' => array('view' => 'client/profile_tab', 'i18n' => 'client.tab.profiles'),
	'ard-tab' => array('view' => 'client/ard_tab', 'i18n' => 'client.tab.ard')
		)
?>

<div class="container">
	<div class="row">

		<div class="col-lg-12">

			<div class="panel panel-default">
		        <div class="panel-heading">
		            <h3 class="panel-title clearfix">
		            	<div class="pull-left" style="padding-top:7.5px">
			            	<i class="fa fa-laptop fa-fw"></i>
			            	<span id="computer_name"></span>
			            </div>
		            	<div class="btn-group pull-right">
							<a data-section="machine" id="add-comment" href="#" class="btn btn-default">
								<span class="fa fa-comment"></span>
							</a>
						</div>
						<div class="btn-group pull-right" style="margin-right:5px">
							<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							Show <span class="caret"></span>
							</a>
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
								<li><a href="#">Generate report</a></li>
							</ul>
						</div>
		            </h3>
		        </div>
		    </div><!-- /panel -->

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

				// Fix for using a regular dropdown for tabs
				$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

					// Remove 'active' class from all li's
					$(e.target).closest('ul').children().removeClass('active');

					// Add 'active' to current li
					$(e.target).parent().addClass('active');
				  
				})

				var serial_number = '<?php echo $serial_number?>',
					addComment = function(){

						var section = $(this).data('section'),
							editor = '',
							saveComment = function(){

								// add parsed text to hidden field
								$('#myModal input[name="html"]').val(editor.parseContent());

								// Get formdata
								var formData = $('#myModal form').serializeArray();
								
								// Save comment
								var jqxhr = $.post( baseUrl + "index.php?/module/comment/save", formData);

								jqxhr.done(function(data){

									// Dismiss modal
									$('#myModal').modal('hide');
								})

							}

						$('#myModal .modal-body')
							.empty()
							.append($('<form>')
								.submit(saveComment)
								.append($('<input>')
									.attr('type', 'submit')
									.addClass('invisible'))
								.append($('<input>')
									.attr('type', 'hidden')
									.attr('name', 'serial_number')
									.val(serial_number))
								.append($('<input>')
									.attr('type', 'hidden')
									.attr('name', 'section')
									.val(section))
								.append($('<input>')
									.attr('type', 'hidden')
									.attr('name', 'html'))
								.append($('<div>')
									.addClass('form-group')
									.append($('<label>')
										.text(i18n.t("dialog.comment.label")))
									.append($('<textarea>')
										.attr('name', 'text')
										.attr('rows', 10)
										.addClass('form-control'))));

						$.getJSON( baseUrl + 'index.php?/module/comment/retrieve/' + serial_number + '/' + section, function( data ) {
							data.text = data.text || ''
							$('textarea').text(data.text)
							$('textarea').markdown({
								autofocus:false,
								savable:false,
								iconlibrary: 'fa',
								fullscreen:{enable:true},
								onShow: function(e){
									// Store a reference to the editor
									editor = e;
								}
							});
						});


						$('#myModal button.ok')
							.text(i18n.t("dialog.save"))
							.off()
							.click(saveComment);
						$('#myModal .modal-title').text(i18n.t("dialog.comment.add"));
						$('#myModal').modal('show');
					}

				// Comments
				$('#add-comment').click(addComment);

				$('div.comment')
					.empty()
					.append('Comments')

				// Get client data
				$.getJSON( baseUrl + 'index.php?/clients/get_data/' + serial_number, function( data ) {
					console.log(data);
					machineData = data[0];

					// Set properties based on id
					$.each(machineData, function(prop, val){
						$('#'+prop).html(val);
					});

					// Format OS Version
					$('#os_version').html(integer_to_version(machineData.os_version));


					// Format filesizes
					$('#TotalSize').html(fileSize(machineData.TotalSize, 1));
					$('#UsedSize').html(fileSize(machineData.TotalSize - machineData.FreeSpace, 1));
					$('#FreeSpace').html(fileSize(machineData.FreeSpace, 1));

					// Smart status
					$('#SMARTStatus').html(machineData.SMARTStatus);
					if(machineData.SMARTStatus == 'Failing'){
						$('#SMARTStatus').addClass('label label-danger');
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
							msg = msg + ' <a target="_blank" href="https://selfsolve.apple.com/RegisterProduct.do?productRegister=Y&amp;country=USA&amp;id='+machineData.serial_number+'">Register</a>'
							break;
						case 'Expired':
							cls = 'text-danger';
							msg = i18n.t("warranty.expired", {date:machineData.end_date});
							break;
					}

					
					$('#warranty_status').addClass(cls).html(msg);

					// Uptime
					if(machineData.uptime > 0){
						var uptime = moment((machineData.timestamp - machineData.uptime) * 1000);
						$('#uptime').html('<time title="'+i18n.t('boot_time')+': '+uptime.format('LLLL')+'">'+uptime.fromNow(true)+'</time>');
					}else{
						$('#uptime').html(i18n.t('unavailable'));
					}

					// Registration date
					var msecs = moment(machineData.reg_timestamp * 1000);
					$('#reg_date').append('<time title="'+msecs.format('LLLL')+'" datetime="<?php echo $report->reg_timestamp; ?>">'+msecs.fromNow()+'</time>');

					// Check-in date
					var msecs = moment(machineData.timestamp * 1000);
					$('#check-in_date').append('<time title="'+msecs.format('LLLL')+'" datetime="<?php echo $report->reg_timestamp; ?>">'+msecs.fromNow()+'</time>');

					// Set tooltips
					$( "dd time" ).each(function( index ) {
							$(this).tooltip().css('cursor', 'pointer');
					});
					
					// Remote control links
					$.getJSON( baseUrl + 'index.php?/clients/get_links', function( links ) {
						$.each(links, function(prop, val){
							$('#client_links').append('<a class="btn btn-default" href="'+(val.replace(/%s/, machineData.remote_ip))+'">'+i18n.t('remote_control')+' ('+prop+')</a>');
						});
					});


				});

				// Get estimate_manufactured_date
				$.getJSON( baseUrl + 'index.php?/module/warranty/estimate_manufactured_date/' + serial_number, function( data ) {
					$('#manufacture_date').html(data.date)
				});

				// Get certificate data
				$.getJSON( baseUrl + 'index.php?/module/certificate/get_data/' + serial_number, function( data ) {
					console.log(data);
				});

				// Activate correct tab depending on hash
				var hash = window.location.hash.slice(5);
				if(hash){
					$('.client-tabs a[href="#'+hash+'"]').tab('show');
				}
				else{
					$('.client-tabs a[href="#summary"]').tab('show');
				}
				

				// Update hash when changing tab
				$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
					var url = String(e.target)
					if(url.indexOf("#") != -1)
					{
						var hash = url.substring(url.indexOf("#"));
						// Save scroll position
						var yScroll=document.body.scrollTop;
						window.location.hash = '#tab_'+hash.slice(1);
						document.body.scrollTop=yScroll;
					}
				})


			});
			</script>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap-markdown.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/marked.min.js"></script>

<?php $this->view('partials/foot'); ?>
