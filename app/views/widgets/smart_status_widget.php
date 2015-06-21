		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

			  <div class="panel-heading" data-container="body" title="HD SMART Status">

			    <h3 class="panel-title"><i class="fa fa-exclamation-circle"></i> HD SMART Status</h3>

			  </div>

			  <div class="panel-body text-center">

			  <?php
			  	$queryobj = new Machine_model();
						$sql = "SELECT COUNT(CASE WHEN SMARTStatus='Failing' THEN 1 END) AS Failing,
										COUNT(CASE WHEN SMARTStatus='Verified' THEN 1 END) AS Verified,
										COUNT(CASE WHEN SMARTStatus='Not Supported' THEN 1 END) AS Not_Supported
							 			FROM diskreport
							 			LEFT JOIN reportdata USING(serial_number)
							 			".get_machine_group_filter();
					$obj = current($queryobj->query($sql));
				?>

				<?php if($obj->Failing > 0): ?>

					<a href="<?php echo url('show/listing/disk#failing'); ?>" class="btn btn-danger">
						<span class="bigger-150"> <?php echo $obj->Failing; ?> </span><br>
						Failing!
					</a>

				<?php else: ?>

					<?php if($obj->Not_Supported > 0): ?>
						<a href="<?php echo url('show/listing/disk#Not Supported'); ?>" class="btn btn-info">
							<span class="bigger-150"> <?php echo $obj->Not_Supported; ?> </span><br>
							Not Supported
						</a>
					<?php endif; ?>
					<a href="<?php echo url('show/listing/disk'); ?>" class="btn btn-success">
						<span class="bigger-150"> <?php echo $obj->Verified; ?> </span><br>
						Verified
					</a>

				<?php endif; ?>

			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->


