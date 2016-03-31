		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body" title="FindMyMac status">

			    	<h3 class="panel-title"><i class="fa fa-sitemap"></i> 
							<span data-i18n="findmymac.widget.title"></span>
						</h3>


				</div>
				<div class="list-group scroll-box">

					<?php	$munkireport = new findmymac_model();
							$sql = "SELECT status, COUNT(1) AS count
									FROM findmymac
									GROUP BY status
									ORDER BY COUNT DESC";
					?>
						<?php foreach($munkireport->query($sql) as $obj): ?>
							<?php if (empty($obj->status)):?>
								<a class="list-group-item"><span data-i18n="unknown">Unknown</span>
									<span class="badge pull-right"><?php echo $obj->count; ?></span>
								</a>
							<?php else: ?>
								<a href="<?php echo url('show/listing/findmymac/#'.$obj->status); ?>" class="list-group-item"><?php echo $obj->status; ?>
									<span class="badge pull-right"><?php echo $obj->count; ?></span>
								</a>
							<?php endif; ?>
						<?php endforeach; ?>

				</div><!-- /scroll-box -->

			</div><!-- /panel -->

		</div><!-- /col -->