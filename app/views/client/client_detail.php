<?$this->view('partials/head')?>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<?$this->view('client/machine_info')?>

			<ul class="nav nav-tabs">

				<li class="active">
					<a href="#munki" data-toggle="tab"><?=lang('tab_munki')?></a>
				</li>

				<li>
					<a href="#apple-software" data-toggle="tab"><?=lang('tab_apple_software')?></a>
				</li>

				<li>
					<a href="#third-party-software" data-toggle="tab"><?=lang('tab_third_party_software')?></a>
				</li>

				<li>
					<a href="#inventory-items" data-toggle="tab"><?=lang('tab_inventory_items')?> <span id="inventory-cnt" class="badge">.</span></a>
				</li>

				<li>
					<a href="#network-tab" data-toggle="tab"><?=lang('tab_network_interfaces')?> <span id="network-cnt" class="badge">0</span></a>
				</li>
				
				<li>
					<a href="#directory-tab" data-toggle="tab"><?=lang('tab_directory_services')?> <span id="directory-cnt" class="badge">0</span></a>
				</li>

			</ul>

			<div class="tab-content">

				<div class="tab-pane active" id='munki'>
					<?$this->view('client/munki_tab')?>
				</div>
	
				<div class="tab-pane" id='apple-software'>
					<h2><?=lang('installed_apple_software')?></h2>
					<?$this->view('client/install_history_tab', array('apple'=> TRUE))?>
				</div>
	
				<div class="tab-pane" id='third-party-software'>
					<h2><?=lang('installed_third_party_software')?></h2>
					<?$this->view('client/install_history_tab', array('apple'=> FALSE))?>
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

			</div>

			<script>
			$(document).ready(function() {

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
			});
			</script>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>