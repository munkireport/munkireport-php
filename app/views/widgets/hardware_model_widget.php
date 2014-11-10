 		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-laptop"></i> Hardware model breakdown</h3>

				</div>

				<div class="list-group scroll-box">

				<?	$machine = new Machine_model();
					$sql = "SELECT count(id) AS count, machine_desc FROM machine GROUP BY machine_desc ORDER BY count DESC";
				?>
					<?foreach($machine->query($sql) as $obj):?>
					<?$obj->machine_desc = $obj->machine_desc ? $obj->machine_desc : 'Unknown';?>
					<a href="<?=url('show/listing/hardware/#'.rawurlencode($obj->machine_desc))?>" class="list-group-item"><?=$obj->machine_desc?>
						<span class="badge pull-right"><?=$obj->count?></span>
					</a>
					<?endforeach?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
