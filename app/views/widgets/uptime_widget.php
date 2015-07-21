		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body">

					<h3 class="panel-title"><i class="fa fa-power-off"></i> Uptime</h3>

				</div>

				<div class="panel-body text-center">

					<?php //FIXME use query to count totals!
						$machine = new Reportdata_model();
						$in_green = 0;
						$in_yellow = 0;
						$in_red = 0;
						$filter = get_machine_group_filter('AND');
						$sql = "SELECT timestamp, uptime
										FROM reportdata
										WHERE uptime <> '0'
										$filter
										ORDER BY timestamp DESC";

						foreach ($machine->query($sql) as $obj) {

							if ( $obj->uptime <= 86400 ){
								$in_green += 1;
							} elseif ( $obj->uptime >= 604800 ) {
								$in_red += 1;
							} else {
								$in_yellow += 1;
							}

						} // end foreach

					?>

					<a href="<?php echo url('show/listing/clients'); ?>" class="btn btn-danger">
						<span class="bigger-150"> <?php echo $in_red; ?> </span><br>
						7 Days +
					</a>
					<a href="<?php echo url('show/listing/clients'); ?>" class="btn btn-warning">
						<span class="bigger-150"> <?php echo $in_yellow; ?> </span><br>
						< 7 Days
					</a>
					<a href="<?php echo url('show/listing/clients'); ?>" class="btn btn-success">
						<span class="bigger-150"> <?php echo $in_green; ?> </span><br>
						< 1 Day
					</a>
					
				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
