<div class="well well-small">
	<?$machine = new Machine($serial_number)?>
	<div class="row">
		<div class="col-lg-1">
			<img width="72" height="72" src="https://km.support.apple.com.edgekey.net/kb/securedImage.jsp?configcode=<?=substr($serial_number, 8)?>&amp;size=120x120" />
		</div>
		<div class="col-lg-5">
			<h4>
				<?=$machine->hostname?><br />
			</h4>
			<small class="muted">
				<?=$machine->machine_desc?>
				<?=$machine->machine_model?>
				<br />
				Warranty Coverage: 
				<?if ($warranty['status'] == "Supported"):?>
					<span class='text-success'>Supported until 
						<?=$warranty['end_date']?>
					</span>
				<?else:?>
					<span class='text-danger'><?=$warranty['status']?></span>
				<?endif?>

				</small>
				<hr />
				<a class="btn btn-default btn-xs" href="<?php echo url('clients/recheck_warranty/' . $serial_number);?>">
					Recheck Warranty Status
				</a>
				<?if(Config::get('vnc_link')):?>

				<a class="btn btn-default btn-xs" href="<?printf(Config::get('vnc_link'), $meta['remote_ip'])?>">Remote Control (vnc)</a>
				<?endif?>
		</div>
		<div class="col-lg-6">
			<small>
				<dl class="dl-horizontal">
					<dt>Software</dt>
					<dd>OS X <?=$machine->os_version . ' ('
							. $machine->cpu_arch . ')'?>&nbsp;</dd>
					<dt>CPU Speed</dt>
					<dd><?=$machine->current_processor_speed?> ( <?=$machine->number_processors?> core )</dd>
					<dt>Serial Number</dt>
					<dd><?=$serial_number?>&nbsp;</dd>
					<dt>SMC Version</dt>
					<dd><?=$machine->SMC_version_system?>&nbsp;</dd>
					<dt>Boot ROM</dt>
					<dd><?=$machine->boot_rom_version?>&nbsp;</dd>
					<dt>Memory</dt>
					<dd><?=$machine->physical_memory?>&nbsp;</dd>
					<dt>Hardware UUID</dt>
					<dd><?=$machine->platform_UUID?>&nbsp;</dd>
					<dt>Remote IP Address</dt>
					<dd><?php echo $meta['remote_ip'];?>&nbsp;</dd>
				</dl>
			</small>
		</div>
	</div>
</div>