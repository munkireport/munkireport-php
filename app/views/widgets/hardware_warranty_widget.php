		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-umbrella"></i> Hardware Warranty</h3>

				</div>

				<div class="list-group scroll-box">

					<?php
						$warranty = new Warranty_model();
						$sql = "SELECT count(id) AS count, status
										FROM warranty
										GROUP BY status
										ORDER BY count DESC";
						$class_list = array('Expired' => 'danger', 'Supported' => 'success');
					?>

					<?foreach($warranty->query($sql) as $obj):?>
						<?$status = array_key_exists($obj->status, $class_list) ? $class_list[$obj->status] : 'default'?>
						<a href="<?=url('show/listing/warranty#'.$obj->status)?>" class="list-group-item list-group-item-<?=$status?>">
							<span class="badge"><?=$obj->count?></span>
							<?=$obj->status?>
						</a>
					<?endforeach?>


					<?php
						$thirtydays = date('Y-m-d', strtotime('+30days'));
						$sql = "SELECT count(id) AS count, status
										FROM warranty
										WHERE end_date < '$thirtydays' AND status = 'Supported'
										GROUP BY status
										ORDER BY count DESC";
					?>

					<?foreach($warranty->query($sql) as $obj):?>
						<a href="<?=url('show/listing/warranty#'.$obj->status)?>" class="list-group-item list-group-item-warning">
							<span class="badge"><?=$obj->count?></span>
							Expires in 30 days
						</a>
					<?endforeach?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
