		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-umbrella"></i> Warranty status</h3>
				
				</div>

				<div class="list-group">

						<?	$warranty = new Warranty();
						$sql = "SELECT count(id) as count, status from warranty group by status ORDER BY status";
					?>
					<?foreach($warranty->query($sql) as $obj):?> 
					<a href="<?=url('show/listing/warranty#'.$obj->status)?>" class="list-group-item">
						<span class="badge"><?=$obj->count?></span>
						<?=$obj->status?>
					</a>
					<?endforeach?>
		


				<?	$thirtydays = date('Y-m-d', strtotime('+30days'));
					$sql = "select count(id) as count, status from warranty WHERE end_date < '$thirtydays' AND status != 'Expired' AND end_date != '' group by status ORDER BY status";
				?>
					<?foreach($warranty->query($sql) as $obj):?>
					<a href="<?=url('show/listing/warranty')?>" class="list-group-item">
						<span class="badge"><?=$obj->count?></span>
						Expires in 30 days (<?=$obj->status?>)
					</a>
					<?endforeach?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->