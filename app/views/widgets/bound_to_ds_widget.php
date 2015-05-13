		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

			  <div class="panel-heading" data-container="body" data-i18n="[title]widget.bound_to_ds.info" title="Totals since this data is being collected">

			    <h3 class="panel-title"><i class="fa fa-bullseye"></i> <span data-i18n="widget.bound_to_ds.title">Bound to a Directory Service</span></h3>

			  </div>

			  <div class="panel-body text-center">
			  	<?php
			  	$queryobj = new directory_service_model();
				$sql = "SELECT COUNT(1) as total,
								COUNT(CASE WHEN (which_directory_service LIKE 'Active Directory'
								OR which_directory_service LIKE 'LDAPv3') THEN 1 END) AS arebound
								FROM directoryservice
								LEFT JOIN machine USING(serial_number)
								".get_machine_group_filter();
				$obj = current($queryobj->query($sql));
				?>
				<?php if($obj): ?>
				<a href="<?php echo url('show/listing/directoryservice'); ?>" class="btn btn-success">
					<span class="bigger-150"> <?php echo $obj->arebound; ?> </span><br>
					<span data-i18n="widget.bound_to_ds.bound">Bound</span>
				</a>
				<a href="<?php echo url('show/listing/directoryservice'); ?>" class="btn btn-danger">
					<span class="bigger-150"> <?php echo $obj->total - $obj->arebound; ?> </span><br>
					<span data-i18n="widget.bound_to_ds.notbound">Not Bound</span>
				</a>
				<?php endif; ?>

			  </div>

			</div><!-- /panel -->

		</div><!-- /col -->
