<?$this->view('partials/head')?>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<?$this->view('client/machine_info')?>

			<ul class="nav nav-tabs">

				<li class="active">
					<a href="#munki" data-toggle="tab" data-i18n="client.tab.munki">Managed Software</a>
				</li>

				<li>
					<a href="#apple-software" data-toggle="tab"><span data-i18n="client.tab.apple_software">Apple Software</span> <span id="history-cnt-1" class="badge">.</span></a>
				</li>

				<li>
					<a href="#third-party-software" data-toggle="tab"><span data-i18n="client.tab.third_party_software">Third party software</span> <span id="history-cnt-0" class="badge">.</span></a>
				</li>

				<li>
					<a href="#inventory-items" data-toggle="tab"><span data-i18n="client.tab.inventory_items">Inventory Items</span> <span id="inventory-cnt" class="badge">.</span></a>
				</li>

				<li>
					<a href="#network-tab" data-toggle="tab"><span data-i18n="client.tab.network">Network Interfaces</span> <span id="network-cnt" class="badge">0</span></a>
				</li>

				<li>
					<a href="#directory-tab" data-toggle="tab"><span data-i18n="client.tab.ds">Directory Services</span> <span id="directory-cnt" class="badge">0</span></a>
				</li>

				<li>
					<a href="#displays-tab" data-toggle="tab"><span data-i18n="client.tab.displays">Displays</span> <span id="displays-cnt" class="badge">0</span></a>
				</li>

				<li>
					<a href="#filevault-tab" data-toggle="tab" data-i18n="client.tab.fv_escrow">FileVault Escrow</a>
				</li>
				<li>
					<a href="#bluetooth-tab" data-toggle="tab"data-i18n="client.tab.bluetooth">Bluetooth</a>
				</li>
				<li>
					<a href="#ard-tab" data-toggle="tab" data-i18n="client.tab.ard">ARD</a>
				</li>

			</ul>

			<div class="tab-content">

				<div class="tab-pane active" id='munki'>
					<?$this->view('client/munki_tab')?>
				</div>

				<div class="tab-pane" id='apple-software'>
					<h2 data-i18n="client.installed_apple_software">Installed Apple Software</h2>
					<?$this->view('client/install_history_tab', array('apple'=> 1))?>
				</div>

				<div class="tab-pane" id='third-party-software'>
					<h2 data-i18n="client.installed_third_party_software">Installed Third Party Software</h2>
					<?$this->view('client/install_history_tab', array('apple'=> 0))?>
				</div>

				<div class="tab-pane" id='inventory-items'>
					<?$this->view('client/inventory_items_tab')?>
				</div>

				<div class="tab-pane" id='network-tab'>
					<?$this->view('client/network_tab')?>
				</div>

				<div class="tab-pane" id='directory-tab'>
					<?$this->view('client/directory_tab')?>
				</div>

				<div class="tab-pane" id='displays-tab'>
					<?$this->view('client/displays_tab')?>
				</div>

				<div class="tab-pane" id='filevault-tab'>
					<?$this->view('client/filevault_tab')?>
				</div>
				<div class="tab-pane" id='bluetooth-tab'>
					<?$this->view('client/bluetooth_tab')?>
				</div>
				<div class="tab-pane" id='ard-tab'>
					<?$this->view('client/ard_tab')?>
				</div>

			</div>

			<script src="<?=conf('subdirectory')?>assets/js/bootstrap-tabdrop.js"></script>

			<script>
			$(document).on('appReady', function(e, lang) {

				// Activate tabdrop
				$('.nav-tabs').tabdrop();

				// Activate correct tab depending on hash
				var hash = window.location.hash.slice(5);
				$('.nav-tabs a[href="#'+hash+'"]').tab('show');

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

				// Set times
				$( "dd time" ).each(function( index ) {
					if($(this).hasClass('absolutetime'))
					{
						seconds = moment().seconds(parseInt($(this).attr('datetime')))
						$(this).html(moment(seconds).fromNow(true));
					}
					else
					{
						$(this).html(moment($(this).attr('datetime') * 1000).fromNow());
					}
					$(this).tooltip().css('cursor', 'pointer');
				});
			});
			</script>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>
