<?$this->view('partials/head')?>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<?$this->view('partials/machine_info')?>

			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#munki" data-toggle="tab">Munki</a>
				</li>
				<li>
					<a href="#apple-software" data-toggle="tab">Apple Software</a>
				</li>
				<li>
					<a href="#third-party-software" data-toggle="tab">Third Party Software</a>
				</li>
				<li>
					<a href="#inventory-items" data-toggle="tab">Inventory Items <span id="inventory-cnt" class="badge">.</span></a>
				</li>

			</ul>

			<div class="tab-content">

				<div class="tab-pane active" id='munki'>
					<?$this->view('partials/munki')?>
				</div>
	
				<div class="tab-pane" id='apple-software'>
					<h2>Installed Apple Software</h2>
					<?$this->view('partials/install_history', array('apple'=> TRUE))?>
				</div>
	
				<div class="tab-pane" id='third-party-software'>
					<h2>Installed Third-Party Software</h2>
					<?$this->view('partials/install_history', array('apple'=> FALSE))?>
				</div>
				<div class="tab-pane" id='inventory-items'>
					<?$this->view('partials/inventory_items')?>
				</div>

			</div>

			<script>
			$(document).ready(function() {

				// Activate correct tab depending on hash
				var hash = window.location.hash.slice(5);
				$('.nav-tabs a[href="#'+hash+'"]').tab('show');

				// Update hash depending on tab
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