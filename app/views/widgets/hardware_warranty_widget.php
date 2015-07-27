		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-umbrella"></i> Hardware Warranty</h3>

				</div>

				<div class="list-group scroll-box">

					<?php
						$warranty = new Warranty_model();
						$filter = get_machine_group_filter();
						$sql = "SELECT count(*) AS count, status
										FROM warranty
										LEFT JOIN reportdata USING (serial_number)
										$filter
										GROUP BY status
										ORDER BY count DESC";
						$class_list = array('Expired' => 'danger', 'Supported' => 'success');
					?>

					<?php foreach($warranty->query($sql) as $obj): ?>
						<?php $status = array_key_exists($obj->status, $class_list) ? $class_list[$obj->status] : 'default'?>
						<a href="<?php echo url('show/listing/warranty#'.$obj->status); ?>" class="list-group-item list-group-item-<?php echo $status; ?>">
							<span class="badge"><?php echo $obj->count; ?></span>
							<?php echo $obj->status; ?>
						</a>
					<?php endforeach; ?>


					<?php
						$thirtydays = date('Y-m-d', strtotime('+30days'));
						$filter = get_machine_group_filter('AND');
						$sql = "SELECT count(*) AS count, status
										FROM warranty
										LEFT JOIN reportdata USING (serial_number)
										WHERE end_date < '$thirtydays' AND status = 'Supported'
										$filter
										GROUP BY status
										ORDER BY count DESC";
					?>

					<?php foreach($warranty->query($sql) as $obj): ?>
						<a href="<?php echo url('show/listing/warranty#'.$obj->status); ?>" class="list-group-item list-group-item-warning">
							<span class="badge"><?php echo $obj->count; ?></span>
							Expires in 30 days
						</a>
					<?php endforeach; ?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
