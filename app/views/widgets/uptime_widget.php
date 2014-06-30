		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body" title="Totals since this data is being collected">

					<h3 class="panel-title"><i class="fa fa-history"></i> Uptime</h3>

				</div>

				<div class="panel-body text-center">

					<?php
						$machine = new Reportdata_model();
						$in_green = 0;
						$in_yellow = 0;
						$in_red = 0;
						$sql = "SELECT timestamp, uptime
										FROM reportdata
										WHERE uptime <> '0'
										ORDER BY timestamp DESC";

						foreach ($machine->query($sql) as $obj) {

							if ( (time() - ($obj->timestamp - $obj->uptime)) <= 86400 ){
								$in_green += 1;
							} elseif ( (time() - ($obj->timestamp - $obj->uptime)) >= 604800 ) {
								$in_red += 1;
							} else {
								$in_yellow += 1;
							}

						} // end foreach

					?>

					<a class="btn btn-success">
						<span class="bigger-150"> <?=$in_green?> </span><br>
						> 1 Day
					</a>

					<a class="btn btn-warning">
						<span class="bigger-150"> <?=$in_yellow?> </span><br>
						> 7 Days
					</a>

					<a class="btn btn-danger">
						<span class="bigger-150"> <?=$in_red?> </span><br>
						7 Days +
					</a>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
