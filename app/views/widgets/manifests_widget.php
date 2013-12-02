 		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-book"></i> Manifests</h3>
				
				</div>

				<div class="list-group scroll-box">

				<?	$munkireport = new Munkireport_model();
					$sql = "SELECT count(1) AS count, manifestname FROM munkireport GROUP BY manifestname ORDER BY count DESC";
				?>
					<?foreach($munkireport->query($sql) as $obj):?>
					<?$obj->manifestname = $obj->manifestname ? $obj->manifestname : 'Unknown';?>
					<a href="<?=url('show/listing/munki/#'.$obj->manifestname)?>" class="list-group-item"><?=$obj->manifestname?>
						<span class="badge pull-right"><?=$obj->count?></span>
					</a>
					<?endforeach?>

				</div>


			</div><!-- /panel -->

		</div><!-- /col -->
