 		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-laptop"></i> Hardware model breakdown</h3>

				</div>

				<div class="list-group scroll-box">

				<?php	$machine = new Machine_model();
						$filter = get_machine_group_filter();
						$sql = "SELECT count(*) AS count, machine_desc 
							FROM machine
							LEFT JOIN reportdata USING (serial_number)
							$filter
							GROUP BY machine_desc 
							ORDER BY count DESC";
				?>
					<?php foreach($machine->query($sql) as $obj): ?>
					<?php $obj->machine_desc = $obj->machine_desc ? $obj->machine_desc : 'Unknown'; ?>
					<a href="<?php echo url('show/listing/hardware/#'.rawurlencode($obj->machine_desc)); ?>" class="list-group-item"><?php echo $obj->machine_desc; ?>
						<span class="badge pull-right"><?php echo $obj->count; ?></span>
					</a>
					<?php endforeach; ?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
