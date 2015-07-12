 		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-book"></i> Manifests</h3>
				
				</div>

				<div class="list-group scroll-box">

				<?php	$munkireport = new Munkireport_model();
						$filter = get_machine_group_filter();
						$sql = "SELECT COUNT(1) AS count, manifestname 
							FROM munkireport
							LEFT JOIN reportdata USING (serial_number)
							$filter
							GROUP BY manifestname
							ORDER BY count DESC";
				?>
					<?php foreach($munkireport->query($sql) as $obj): ?>
					<?php $obj->manifestname = $obj->manifestname ? $obj->manifestname : 'Unknown'; ?>
					<a href="<?php echo url('show/listing/munki/#'.$obj->manifestname); ?>" class="list-group-item"><?php echo $obj->manifestname; ?>
						<span class="badge pull-right"><?php echo $obj->count; ?></span>
					</a>
					<?php endforeach; ?>

				</div>


			</div><!-- /panel -->

		</div><!-- /col -->
