		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body" title="Total number of known external displays">

					<h3 class="panel-title"><i class="fa fa-expand"></i> External Displays Count</h3>

				</div>

				<div class="panel-body text-center">
					<?php
						$queryobj = new displays_info_model();
						$sql = "SELECT COUNT(CASE WHEN type='1' THEN 1 END) AS total
						 			FROM displays
						 			LEFT JOIN reportdata USING (serial_number)
						 			".get_machine_group_filter();
						$obj = current($queryobj->query($sql));
					?>
				<?php if($obj): ?>
					<a href="<?php echo url('show/listing/displays'); ?>" class="btn btn-success">
						<span class="bigger-150"> <?php echo $obj->total; ?> </span><br>
						Displays
					</a>
				<?php endif ?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
