		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-lock"></i> Filevault 2 status</h3>
				
				</div>

				<div class="list-group scroll-box">

				<?php	$fv = new filevault_status_model();
						$filter = get_machine_group_filter();
						$sql = "SELECT count(1) AS count,
								filevault_status 
								FROM filevault_status
								LEFT JOIN reportdata USING (serial_number)
								$filter
								GROUP BY filevault_status
								ORDER BY count DESC";
						$cnt = 0;
				?>
					<?php foreach($fv->query($sql) as $obj): ?>
							<?php if (empty($obj->filevault_status)):?>
								<a class="list-group-item"><span data-i18n="unknown">Unknown</span>
									<span class="badge pull-right"><?php echo $obj->count; ?></span>
								</a>
							<?php else: ?>
								<a href="<?php echo url('show/listing/security#'.$obj->filevault_status); ?>" class="list-group-item">
									<span class="badge"><?php echo $obj->count; ?></span>
									<?php echo $obj->filevault_status; ?>
								</a>
							<?php endif; ?>
							<?php $cnt++; ?>
					<?php endforeach; ?>
					<?php if( ! $cnt): ?>
						<span class="list-group-item">No Filevault status available</span>
						<?php endif; ?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->