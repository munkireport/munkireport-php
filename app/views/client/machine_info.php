<div class="well well-small machine-info">
	<?php $machine = new Machine_model($serial_number); ?>
	<?php $report   = new Reportdata_model($serial_number); ?>
	<?php $disk   = new Disk_report_model($serial_number); ?>
	<?php $warranty   = new Warranty_model($serial_number); ?>
	<?php $localadmin   = new Localadmin_model($serial_number); ?>
	<?php //todo: make 1 query?>
	<div class="row">
		<div class="col-lg-1">
			<img width="72" height="72" src="https://km.support.apple.com.edgekey.net/kb/securedImage.jsp?configcode=<?php echo substr($serial_number, 8); ?>&amp;size=120x120" />
		</div>
		<div class="col-lg-3">
			<h4>
				<?php echo $machine->computer_name; ?><br />
			</h4>
			<small class="muted">
				<?php echo $machine->machine_desc; ?>
				<?php echo $machine->machine_model; ?>
				<br />
				<?php echo $report->long_username; ?>
				<?php if($report->console_user): ?>
				(<?php echo $report->console_user; ?>)
				<?php endif; ?>
				<br />
				Warranty Coverage:
				<?php if ($warranty->status == "Supported"): ?>
					<span class='text-success'>Supported until
						<?php echo $warranty->end_date; ?>
					</span>
				<?php elseif ($warranty->status == "No Applecare"): ?>
					<span class='text-warning'>Supported until
						<?php echo $warranty->end_date; ?>, No Applecare
					</span>
				<?php elseif ($warranty->status == "Unregistered serialnumber"): ?>
					<span class='text-warning'>Unregistered</span>
					<a target="_blank" href="https://selfsolve.apple.com/RegisterProduct.do?productRegister=Y&amp;country=USA&amp;id=<?php echo $serial_number; ?>">Register</a>
				<?php elseif ($warranty->status == "Expired"): ?>
					<span class='text-danger'>Expired on
						<?php echo $warranty->end_date; ?>
					</span>
				<?php else: ?>
					<span class='text-danger'><?php echo $warranty->status; ?></span>
				<?php endif; ?>
				
				<a class="btn btn-default btn-xs" href="<?php echo url('module/warranty/recheck_warranty/' . $serial_number);?>">Recheck Warranty Status</a> <br/>

		</small>

		</div>
		<div class="col-lg-4">
			<small>
				<dl class="dl-horizontal">
					<dt>Software</dt>
					<dd>OS X <span class="osvers"><?php echo $machine->os_version?></span>
						(<?php echo $machine->cpu_arch; ?>)&nbsp;</dd>
					<dt>CPU</dt>
					<dd><?php echo $machine->cpu; ?>&nbsp;</dd>
					<dt>CPU Type</dt>
					<dd><?php echo $machine->number_processors; ?> core&nbsp;</dd>
					<dt data-i18n="serial">Serial Number</dt>
					<dd><?php echo $serial_number; ?>&nbsp;</dd>
					<dt>SMC Version</dt>
					<dd><?php echo $machine->SMC_version_system; ?>&nbsp;</dd>
					<dt>Boot ROM</dt>
					<dd><?php echo $machine->boot_rom_version; ?>&nbsp;</dd>
					<dt data-i18n="memory">Memory</dt>
					<dd><?php echo intval($machine->physical_memory); ?> GB&nbsp;</dd>
					<dt>Hardware UUID</dt>
					<dd><?php echo $machine->platform_UUID; ?>&nbsp;</dd>
					<dt>Remote IP Address</dt>
					<dd><?php echo $report->remote_ip; ?>&nbsp;</dd>
					<dt>Local admin</dt>
					<dd><?php echo $localadmin->users; ?>&nbsp;</dd>
					<dd>
						<div class="btn-group btn-group-xs">
							<?php if(conf('vnc_link')): ?>
								<a class="btn btn-default" href="<?php printf(conf('vnc_link'), $report->remote_ip); ?>">
									Remote Control (vnc)
								</a>
							<?php endif; ?>
							<?php if(conf('ssh_link')): ?>
								<a class="btn btn-default" href="<?php printf(conf('ssh_link'), $report->remote_ip); ?>">
									Remote Control (ssh)
								</a>
							<?php endif; ?>
						</div>
					</dd>

				</dl>
			</small>
		</div>
		<div class="col-lg-4">
			<small>
				<dl class="dl-horizontal">
					<dt>Disk size</dt>
					<dd><?php echo humanreadablesize($disk->TotalSize); ?></dd>
					<dt>Used</dt>
					<dd><?php echo humanreadablesize($disk->TotalSize - $disk->FreeSpace); ?></dd>
					<dt>Free</dt>
					<dd><?php echo humanreadablesize($disk->FreeSpace); ?></dd>
					<dt>SMART Status</dt>
					<dd><?php echo ($disk->SMARTStatus == 'Failing') ? '<span class=text-danger>Failing</span>' : $disk->SMARTStatus; ?></dd>
				</dl>
				<?php require_once(conf('application_path') . "helpers/warranty_helper.php"); ?>
				<dl class="dl-horizontal">
					<dt>Est. Manufacture date</dt>
					<dd><?php echo estimate_manufactured_date($serial_number); ?></dd>
					<dt>Est. Purchase date</dt>
					<dd><?php echo $warranty->purchase_date; ?></dd>
				</dl>

				<dl class="dl-horizontal">
					<dt>Uptime</dt>
					<?php if($report->uptime > 0): ?>
					<dd><time class="absolutetime" title="Booted: <?php echo strftime('%c', $report->timestamp - $report->uptime); ?>" datetime="<?php echo $report->uptime; ?>"><?php echo strftime('%x', $report->timestamp - $report->uptime); ?></time></dd>
					<?php else: ?>
					<dd data-i18n="unavailable">Unavailable</dd>
					<?php endif; ?>
				</dl>

				<dl class="dl-horizontal">
					<dt>Registration date</dt>
					<dd><time title="<?php echo strftime('%c', $report->reg_timestamp); ?>" datetime="<?php echo $report->reg_timestamp; ?>"><?php echo strftime('%x', $report->reg_timestamp); ?></time></dd>
					<dt>Last checkin</dt>
					<dd><time title="<?php echo strftime('%c', $report->timestamp); ?>" datetime="<?php echo $report->timestamp; ?>"><?php echo strftime('%x', $report->timestamp); ?></time></dd>
				</dl>

			</small>
		</div>
	</div>
</div>
