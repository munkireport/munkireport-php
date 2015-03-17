		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" title="Bluetooth devices with less than 15% battery life">

					<h3 class="panel-title"><i class="fa fa-bolt"></i> <span data-i18n="bluetooth_battery_widget">Bluetooth battery status</span></h3>

				</div>

				<div class="list-group scroll-box">
					<?php $queryobj = new Bluetooth_model(); ?>
					<?php $cnt=0;
								$sql = "SELECT bluetooth.serial_number, machine.computer_name,
													bluetooth.keyboard_battery, bluetooth.mouse_battery,
													bluetooth.trackpad_battery
												FROM bluetooth
													INNER JOIN
													machine ON bluetooth.serial_number = machine.serial_number
												WHERE (bluetooth.keyboard_battery BETWEEN 0 AND 14)
													OR (bluetooth.mouse_battery BETWEEN 0 AND 14)
													OR (bluetooth.trackpad_battery BETWEEN 0 AND 14)"; ?>

					<?php foreach($queryobj->query($sql) as $obj): ?>
						<a class="list-group-item" href="<?php echo url('clients/detail/'.$obj->serial_number).'#tab_bluetooth-tab'; ?>"><?php echo $obj->computer_name; ?>
							<span class="pull-right">
								<?php if ($obj->keyboard_battery > 39) {
											echo "<span class='label label-success'><i class='fa fa-keyboard-o'></i></span>";
										} elseif ($obj->keyboard_battery > 14 ) {
											echo "<span class='label label-warning'><i class='fa fa-keyboard-o'></i></span>";
										} elseif ($obj->keyboard_battery > -1) {
											echo "<span class='label label-danger'><i class='fa fa-keyboard-o'></i></span>";
										}
								?>
								<?php if ($obj->mouse_battery > 39) {
											echo "<span class='label label-success'><i class='fa fa-hand-o-up'></i></span>";
										} elseif ($obj->mouse_battery > 14 ) {
											echo "<span class='label label-warning'><i class='fa fa-hand-o-up'></i></span>";
										} elseif ($obj->mouse_battery > -1) {
											echo "<span class='label label-danger'><i class='fa fa-hand-o-up'></i></span>";
										}
								?>
								<?php if ($obj->trackpad_battery > 39) {
											echo "<span class='label label-success'><i class='fa fa-square-o'></i></span>";
										} elseif ($obj->trackpad_battery > 14 ) {
											echo "<span class='label label-warning'><i class='fa fa-square-o'></i></span>";
										} elseif ($obj->trackpad_battery > -1) {
											echo "<span class='label label-danger'><i class='fa fa-square-o'></i></span>";
										}
								?>
							</span>
						</a>
						<?php $cnt++; ?>
					<?php endforeach; ?>

					<?php if( ! $cnt): ?>
						<span class="list-group-item">No bluetooth devices with low battery</span>
					<?php endif; ?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
