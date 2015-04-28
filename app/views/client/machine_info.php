<div class="well well-small machine-info">
	<?php $machine = new Machine_model($serial_number); ?>
	<?php $report   = new Reportdata_model($serial_number); ?>
	<?php //todo: make 1 query?>
	<div class="row">
		<div class="col-lg-1">
			<img width="72" height="72" src="https://km.support.apple.com.edgekey.net/kb/securedImage.jsp?configcode=<?php echo substr($serial_number, 8); ?>&amp;size=120x120" />
		</div>
		<div class="col-lg-3">
			<h4 id="computer_name">
				<br />
			</h4>
			<small class="muted">
				<span id="machine_desc"></span>
				<span id="machine_model"></span>
				<br />
				<span id="long_username"></span>
				(<span id="console_user"></span>)
				<br />
				<span data-i18n="warranty.coverage"></span>:
				<span id="warranty_status"></span>
				
				<a class="btn btn-default btn-xs" href="<?php echo url('module/warranty/recheck_warranty/' . $serial_number);?>">Recheck Warranty Status</a> <br/>

		</small>

		</div>
		<div class="col-lg-4">
			<small>
				<dl class="dl-horizontal">
					<dt data-i18n="software"></dt>
					<dd>OS X <span class="osvers"><?php echo $machine->os_version?></span>
						(<?php echo $machine->cpu_arch; ?>)&nbsp;</dd>
					<dt>CPU</dt>
					<dd><span id="cpu"></span>&nbsp;</dd>
					<dt>CPU Type</dt>
					<dd><span id="number_processors"></span> core&nbsp;</dd>
					<dt data-i18n="serial"></dt>
					<dd><span id="serial_number"></span>&nbsp;</dd>
					<dt>SMC Version</dt>
					<dd><span id="SMC_version_system"></span>&nbsp;</dd>
					<dt>Boot ROM</dt>
					<dd><span id="boot_rom_version"></span>&nbsp;</dd>
					<dt data-i18n="memory"></dt>
					<dd><span id="physical_memory"></span> GB</dd>
					<dt>Hardware UUID</dt>
					<dd><span id="platform_UUID"></span>&nbsp;</dd>
					<dt>Remote IP Address</dt>
					<dd><span id="remote_ip"></span>&nbsp;</dd>
					<dt>Local admin</dt>
					<dd><span id="users"></span>&nbsp;</dd>
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
					<dd><span id="TotalSize"></span>&nbsp;</dd>
					<dt>Used</dt>
					<dd><span id="UsedSize"></span>&nbsp;</dd>
					<dt>Free</dt>
					<dd><span id="FreeSpace"></span>&nbsp;</dd>
					<dt>SMART Status</dt>
					<dd><span id="SMARTStatus">&nbsp;</span></dd>
				</dl>
				<dl class="dl-horizontal">
					<dt>Est. Manufacture date</dt>
					<dd><span id="manufacture_date"></span>&nbsp;</dd>
					<dt>Est. Purchase date</dt>
					<dd><span id="purchase_date"></span>&nbsp;</dd>
				</dl>

				<dl class="dl-horizontal">
					<dt>Uptime</dt>
					<?php if($report->uptime > 0): ?>
					<dd><time class="absolutetime" title="Booted: <?php echo strftime('%c', $report->timestamp - $report->uptime); ?>" datetime="<?php echo $report->uptime; ?>"><?php echo strftime('%x', $report->timestamp - $report->uptime); ?></time></dd>
					<?php else: ?>
					<dd data-i18n="unavailable"></dd>
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
