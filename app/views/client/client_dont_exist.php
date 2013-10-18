<?$this->view('partials/head')?>
<div class="container">
	<div class="row">
		<div class="col-sm-offset-4 col-sm-4">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<h3 class="panel-title">Error</h3>
				</div>
				<div class="panel-body">
				Client with serial number <?=$serial_number?> does not exist
				</div>
			</div>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>
