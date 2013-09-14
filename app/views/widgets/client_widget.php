		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-group"></i> Clients</h3>
				
				</div>

				<div class="panel-body text-center">

					<?$queryobj = new Machine();// Generic queryobject?>

					<a href="<?=url('show/listing/clients')?>" class="btn btn-info">
					<?$sql = "select COUNT(id) as count from machine"?>
					<?if($obj = current($queryobj->query($sql))):?>
						<span class="bigger-150"> <?=$client_total = $obj->count?> </span>
						<br>
						Clients
					<?endif?>
					</a>
					<span class="btn btn-success">
					<?$hour_ago = time() - 3600;
						$sql = "select COUNT(id) as count from reportdata WHERE timestamp > $hour_ago";?>
					<?if($obj = current($queryobj->query($sql))):?>
						<span class="bigger-150"> <?=$obj->count?> </span>
						<br>
						Req/hour
					<?endif?>
					</span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->