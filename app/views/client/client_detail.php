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
					<a href="#warranty" data-toggle="tab">Warranty</a>
				</li>
				<li>
					<a href="#apple-software" data-toggle="tab">Apple Software</a>
				</li>
				<li>
					<a href="#third-party-software" data-toggle="tab">Third Party Software</a>
				</li>
				<li>
					<a href="#inventory-items" data-toggle="tab">Inventory Items</a>
				</li>

			</ul>

			<div class="tab-content">

				<div class="tab-pane active" id='munki'>
					<?$this->view('partials/munki')?>
				</div>
	
				<div class="tab-pane" id='warranty'>
					<h2>Warranty</h2>
					<?$this->view('partials/warranty')?>
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
					<h2>Inventory Items</h2>
					<?$this->view('partials/inventory_items')?>
				</div>

			</div>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>