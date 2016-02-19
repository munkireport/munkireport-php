		<div class="col-lg-4 col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading" data-container="body" title="Counts Supported, Vintage, Obsolete, and Unknown support status">
					<h3 class="panel-title"><i class="fa fa-umbrella"></i> <span data-i18n="widget.warrantysupport.warrentysupportstatus"></span></h3>
				</div>

				<div class="panel-body text-center">
					<?php
						$obsolete = new gsx_model();
						$filter = get_machine_group_filter('AND');
						$sql = "SELECT COUNT( CASE WHEN isObsolete = 'Yes' THEN 1 END ) AS total
								FROM gsx
								LEFT JOIN reportdata USING (serial_number)
								".get_machine_group_filter();
						$obso = current($obsolete->query($sql));

						$vintage = new gsx_model();
						$sql = "SELECT COUNT( CASE WHEN isVintage = 'Yes' THEN 1 END ) AS total
								FROM gsx
								LEFT JOIN reportdata USING (serial_number)
								".get_machine_group_filter();
						$vint = current($vintage->query($sql));

						$supported = new gsx_model();
						$sql = "SELECT COUNT( CASE WHEN isVintage = 'No' AND isObsolete = 'No' AND warrantyStatus IS NOT NULL THEN 1 END ) AS total
								FROM gsx
								LEFT JOIN reportdata USING (serial_number)
								".get_machine_group_filter();
						$supp = current($supported->query($sql));

						$unknown = new gsx_model();
						$sql = "SELECT COUNT( CASE WHEN isVintage IS NULL AND isObsolete IS NULL THEN 1 END ) AS total
								FROM gsx
								LEFT JOIN reportdata USING (serial_number)
								".get_machine_group_filter();
						$ukwn = current($unknown->query($sql));

					?>
				<?php if($obso): ?>
					<a href="<?php echo url('show/listing/gsx'); ?>" class="btn btn-danger">
						<span class="bigger-150"> <?php echo $obso->total; ?> </span><br>
				        <span data-i18n="gsx.obsolete"></span>
					</a>
				<?php endif ?>
				<?php if($vint): ?>
					<a href="<?php echo url('show/listing/gsx'); ?>" class="btn btn-warning">
						<span class="bigger-150"> <?php echo $vint->total; ?> </span><br>
                        <span data-i18n="gsx.vintage"></span>
                    </a>
				<?php endif ?>
				<?php if($supp): ?>
					<a href="<?php echo url('show/listing/gsx'); ?>" class="btn btn-success">
						<span class="bigger-150"> <?php echo $supp->total; ?> </span><br>
                        <span data-i18n="gsx.supported"></span>
					</a>
				<?php endif ?>
				<?php if($ukwn): ?>
					<a href="<?php echo url('show/listing/gsx'); ?>" class="btn btn-info">
						<span class="bigger-150"> <?php echo $ukwn->total; ?> </span><br>
                        <span data-i18n="unknown"></span>
					</a>
            	<?php endif ?>
				</div>
			</div><!-- /panel -->
		</div><!-- /col -->