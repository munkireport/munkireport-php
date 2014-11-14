		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body">

					<h3 class="panel-title"><i class="fa fa-bug"></i> Duplicated Computer Names</h3>
				
				</div>

				<div class="list-group scroll-box">

				<?php	$machine = new Machine_model();
						$sql = "SELECT computer_name,
								COUNT(*) AS count
								FROM machine
								GROUP BY computer_name
								HAVING count > 1
								ORDER BY count DESC";
						$cnt = 0;
				?>
					<?php foreach($machine->query($sql) as $obj): ?>
						<?php if (empty($obj->computer_name)): ?>
							<a class="list-group-item">Empty
								<span class="badge pull-right"><?php echo $obj->count; ?></span>
							</a>
						<?php else: ?>
							<a href="<?php echo url('show/listing/clients/#'.$obj->computer_name); ?>" class="list-group-item">
								<span class="badge"><?php echo $obj->count; ?></span>
								<?php echo $obj->computer_name; ?>
							</a>
						<?php endif; ?>
						<?php $cnt++; ?>
					<?php endforeach; ?>
					<?php if( ! $cnt): ?>
						<span class="list-group-item">No duplicates found</span>
					<?php endif; ?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->