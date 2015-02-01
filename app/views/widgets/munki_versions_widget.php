		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body" title="Munki versions among all clients">

			    	<h3 class="panel-title"><i class="fa fa-sitemap"></i> Munki versions</h3>

				</div>
				<div class="list-group scroll-box">

					<?php	$munkireport = new Munkireport_model();
							$sql = "SELECT version, COUNT(1) AS count
									FROM munkireport
									GROUP BY version
									ORDER BY COUNT DESC";
					?>
						<?php foreach($munkireport->query($sql) as $obj): ?>
							<?php if (empty($obj->version)):?>
								<a class="list-group-item"><span data-i18n="unknown">Unknown</span>
									<span class="badge pull-right"><?php echo $obj->count; ?></span>
								</a>
							<?php else: ?>
								<a href="<?php echo url('show/listing/munki/#'.$obj->version); ?>" class="list-group-item"><?php echo $obj->version; ?>
									<span class="badge pull-right"><?php echo $obj->count; ?></span>
								</a>
							<?php endif; ?>
						<?php endforeach; ?>

				</div><!-- /scroll-box -->

			</div><!-- /panel -->

		</div><!-- /col -->