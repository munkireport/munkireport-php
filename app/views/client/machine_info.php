<div class="well well-small machine-info">
	<?$machine = new Machine_model($serial_number)?>
	<?$report   = new Reportdata_model($serial_number)?>
	<?$disk   = new Disk_report_model($serial_number)?>
	<?$warranty   = new Warranty_model($serial_number)?>
	<?$localadmin   = new Localadmin_model($serial_number)?>
	<?//todo: make 1 query?>
	<div class="row">
		<div class="col-lg-1">
			<img width="72" height="72" src="https://km.support.apple.com.edgekey.net/kb/securedImage.jsp?configcode=<?=substr($serial_number, 8)?>&amp;size=120x120" />
		</div>
		<div class="col-lg-3">
			<h4>
				<?=$machine->computer_name?><br />
			</h4>
			<small class="muted">
				<?=$machine->machine_desc?>
				<?=$machine->machine_model?>
				<br />
				<?=$report->long_username?>
				<?if($report->console_user):?>
				(<?=$report->console_user?>)
				<?endif?>
				<br />
				Warranty Coverage:
				<?if ($warranty->status == "Supported"):?>
					<span class='text-success'>Supported until
						<?=$warranty->end_date?>
					</span>
				<?elseif ($warranty->status == "No Applecare"):?>
					<span class='text-warning'>Supported until
						<?=$warranty->end_date?>, No Applecare
					</span>
				<?elseif ($warranty->status == "Unregistered serialnumber"):?>
					<span class='text-warning'>Unregistered</span>
					<a target="_blank" href="https://selfsolve.apple.com/RegisterProduct.do?productRegister=Y&amp;country=USA&amp;id=<?=$serial_number?>">Register</a>
				<?elseif ($warranty->status == "Expired"):?>
					<span class='text-danger'>Expired on
						<?=$warranty->end_date?>
					</span>
				<?else:?>
					<span class='text-danger'><?=$warranty->status?></span>
				<?endif?>
				
				<a class="btn btn-default btn-xs" href="<?php echo url('module/warranty/recheck_warranty/' . $serial_number);?>">Recheck Warranty Status</a> <br/>

		</small>

		</div>
		<div class="col-lg-4">
			<small>
				<dl class="dl-horizontal">
					<dt>Software</dt>
					<dd>OS X <?=$machine->os_version . ' ('
							. $machine->cpu_arch . ')'?>&nbsp;</dd>
					<dt>CPU</dt>
					<dd><?=$machine->cpu?>&nbsp;</dd>
					<dt>CPU Type</dt>
					<dd><?=$machine->number_processors?> core&nbsp;</dd>
					<dt data-i18n="serial">Serial Number</dt>
					<dd><?=$serial_number?>&nbsp;</dd>
					<dt>SMC Version</dt>
					<dd><?=$machine->SMC_version_system?>&nbsp;</dd>
					<dt>Boot ROM</dt>
					<dd><?=$machine->boot_rom_version?>&nbsp;</dd>
					<dt data-i18n="memory">Memory</dt>
					<dd><?=intval($machine->physical_memory)?> GB&nbsp;</dd>
					<dt>Hardware UUID</dt>
					<dd><?=$machine->platform_UUID?>&nbsp;</dd>
					<dt>Remote IP Address</dt>
					<dd><?=$report->remote_ip?>&nbsp;</dd>
					<dt>Local admin</dt>
					<dd><?=$localadmin->users?>&nbsp;</dd>
					<dd>
						<div class="btn-group btn-group-xs">
							<?if(conf('vnc_link')):?>
								<a class="btn btn-default" href="<?printf(conf('vnc_link'), $report->remote_ip)?>">
									Remote Control (vnc)
								</a>
							<?endif?>
							<?if(conf('ssh_link')):?>
								<a class="btn btn-default" href="<?printf(conf('ssh_link'), $report->remote_ip)?>">
									Remote Control (ssh)
								</a>
							<?endif?>
						</div>
					</dd>

				</dl>
			</small>
		</div>
		<div class="col-lg-4">
			<small>
				<dl class="dl-horizontal">
					<dt>Disk size</dt>
					<dd><?=humanreadablesize($disk->TotalSize)?></dd>
					<dt>Used</dt>
					<dd><?=humanreadablesize($disk->TotalSize - $disk->FreeSpace)?></dd>
					<dt>Free</dt>
					<dd><?=humanreadablesize($disk->FreeSpace)?></dd>
					<dt>SMART Status</dt>
					<dd><?=($disk->SMARTStatus == 'Failing') ? '<span class=text-danger>Failing</span>' : $disk->SMARTStatus ?></dd>
				</dl>
				<?require_once(conf('application_path') . "helpers/warranty_helper.php")?>
				<dl class="dl-horizontal">
					<dt>Est. Manufacture date</dt>
					<dd><?=estimate_manufactured_date($serial_number)?></dd>
					<dt>Est. Purchase date</dt>
					<dd><?=$warranty->purchase_date?></dd>
				</dl>

				<dl class="dl-horizontal">
					<dt>Uptime</dt>
					<?if($report->uptime > 0):?>
					<dd><time class="absolutetime" title="Booted: <?=strftime('%c', $report->timestamp - $report->uptime)?>" datetime="<?=$report->uptime?>"><?=strftime('%x', $report->timestamp - $report->uptime)?></time></dd>
					<?else:?>
					<dd data-i18n="unavailable">Unavailable</dd>
					<?endif?>
				</dl>

				<dl class="dl-horizontal">
					<dt>Registration date</dt>
					<dd><time title="<?=strftime('%c', $report->reg_timestamp)?>" datetime="<?=$report->reg_timestamp?>"><?=strftime('%x', $report->reg_timestamp)?></time></dd>
					<dt>Last checkin</dt>
					<dd><time title="<?=strftime('%c', $report->timestamp)?>" datetime="<?=$report->timestamp?>"><?=strftime('%x', $report->timestamp)?></time></dd>
				</dl>

			</small>
		</div>
	</div>
</div>
