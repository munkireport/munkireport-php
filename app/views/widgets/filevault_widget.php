		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-lock"></i> Filevault 2 status</h3>
				
				</div>

				<div class="list-group">

				<?	$fv = new filevault_status_model(); 
					$sql = "SELECT count(1) AS count, filevault_status FROM filevault_status GROUP BY filevault_status ORDER BY filevault_status";
					$cnt = 0;
				?>
					<?foreach($fv->query($sql) as $obj):?>
					<?//$status = array_key_exists($obj->status, $class_list) ? $class_list[$obj->status] : 'danger'?>
					<a href="<?=url('show/listing/warranty#'.$obj->status)?>" class="list-group-item list-group-item-<?//=$status?>">
						<span class="badge"><?=$obj->count?></span>
						<?=$obj->filevault_status?>
					<?$cnt++?>
					</a>
					<?endforeach?>
					<?if( ! $cnt):?>
						<span class="list-group-item">No Filevault status available</span>
					<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->