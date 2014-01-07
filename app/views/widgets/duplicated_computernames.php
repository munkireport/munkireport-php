		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body">

					<h3 class="panel-title"><i class="icon-bug"></i> Duplicated Computer Names</h3>
				
				</div>

				<div class="list-group scroll-box">

				<?	$machine = new Machine_model();
					$sql = "SELECT computer_name,
							COUNT(*) count
							FROM machine
							GROUP BY computer_name
							HAVING count > 1
							ORDER BY count DESC";
					$cnt = 0;
				?>
					<?foreach($machine->query($sql) as $obj):?>
						<?if (empty($obj->computer_name)):?>
							<a class="list-group-item">Empty
								<span class="badge pull-right"><?=$obj->count?></span>
							</a>
						<?else:?>
							<a href="<?=url('show/listing/clients/#'.$obj->computer_name)?>" class="list-group-item">
								<span class="badge"><?=$obj->count?></span>
								<?=$obj->computer_name?>
							</a>
						<?endif?>
						<?$cnt++?>
					<?endforeach?>
					<?if( ! $cnt):?>
						<span class="list-group-item">No duplicates found</span>
					<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->