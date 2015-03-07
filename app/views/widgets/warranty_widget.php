		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-umbrella"></i> Warranty status</h3>
				
				</div>

				<div class="list-group">

				<?php	$warranty = new Warranty_model();
						$thirtydays = date('Y-m-d', strtotime('+30days'));
						$yesterday = date('Y-m-d', strtotime('-1day'));
						$class_list = array('Supported' => 'warning');
						$cnt = 0;
						$sql = "SELECT count(id) AS count, status 
								FROM warranty 
								WHERE (end_date BETWEEN '$yesterday' AND '$thirtydays') 
								AND status != 'Expired'
								GROUP BY status 
								ORDER BY count DESC";
				?>
					<?php foreach($warranty->query($sql) as $obj): ?>
					<?php $status = array_key_exists($obj->status, $class_list) ? $class_list[$obj->status] : 'danger'; ?>
					<a href="<?php echo url('show/listing/warranty#'.$obj->status); ?>" class="list-group-item list-group-item-<?php echo $status; ?>">
						<span class="badge"><?php echo $obj->count; ?></span>
						Expires in 30 days (<?php echo $obj->status; ?>)
					<?php $cnt++; ?>
					</a>
					<?php endforeach; ?>
					<?php if( ! $cnt): ?>
						<span class="list-group-item">No warranty alerts</span>
					<?php endif ?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->