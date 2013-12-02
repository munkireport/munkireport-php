		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-umbrella"></i> Warranty status</h3>
				
				</div>

				<div class="list-group scroll-box">

					<?	$warranty = new Warranty_model();
						$sql = "SELECT count(id) as count, status from warranty group by status ORDER BY status";
						$class_list = array('Expired' => 'danger');
					?>
					<?foreach($warranty->query($sql) as $obj):?> 

					<?$status = array_key_exists($obj->status, $class_list) ? $class_list[$obj->status] : 'default'?>

					<a href="<?=url('show/listing/warranty#'.$obj->status)?>" class="list-group-item list-group-item-<?=$status?>">
						<span class="badge"><?=$obj->count?></span>
						<?=$obj->status?>
					</a>
					<?endforeach?>
		


				<?	$thirtydays = date('Y-m-d', strtotime('+30days'));
					$sql = "select count(id) as count, status from warranty WHERE end_date < '$thirtydays' AND status != 'Expired' AND end_date != '' group by status ORDER BY status";
				?>
					<?foreach($warranty->query($sql) as $obj):?>

					<?$status = array_key_exists($obj->status, $class_list) ? $class_list[$obj->status] : 'default'?>

					<a href="<?=url('show/listing/warranty#'.$obj->status)?>" class="list-group-item list-group-item-<?=$status?>">
						<span class="badge"><?=$obj->count?></span>
						Expires in 30 days (<?=$obj->status?>)
					</a>
					<?endforeach?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->