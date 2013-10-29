		<div class="col-lg-6 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-globe"></i> Network locations</h3>
				
				</div>

				<div class="panel-body">
					
					<div style="height: 200px" id="ip-plot"></div>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

		<script>
		$(function(){
			var parms = {}; // Override network settings in config.php

			drawGraph("<?=url('flot/ip')?>", '#ip-plot', pieOptions, parms);

		});
		</script>