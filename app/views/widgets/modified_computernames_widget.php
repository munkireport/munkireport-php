		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div id="modified-computer-names" class="panel-heading" data-container="body" title="Computers where the computer name doesn't match the AD name">

					<h3 class="panel-title"><i class="fa fa-code-fork"></i> Not matching AD Names</h3>

				</div>

				<div class="list-group scroll-box">

				<?php
					$machine = new Machine_model();
					$filter = get_machine_group_filter('AND');
					$sql = "SELECT directoryservice.serial_number, directoryservice.computeraccount,
									machine.serial_number, machine.computer_name
									FROM directoryservice AS directoryservice
									LEFT JOIN machine USING (serial_number)
									LEFT JOIN reportdata USING (serial_number)
									WHERE NOT directoryservice.computeraccount = ''
									".$filter;
					$cnt = 0;
				?>
					<?php foreach($machine->query($sql) as $obj): ?>
						<!--//removing the dollar sign first, lowercase comparison for non-matching-->
						<?php if (strtolower(str_replace('$', '',$obj->computeraccount)) !== strtolower($obj->computer_name)): ?>

							<a href="<?php echo url('clients/detail/'.$obj->serial_number.'#tab_directory-tab'); ?>" class="list-group-item">
								<span class="badge">1</span>
								<?php echo $obj->computer_name?> != <?php echo str_replace('$', '',$obj->computeraccount); ?>
							</a>
							<?php $cnt++; ?>

						<?php endif; ?>

					<?php endforeach; ?>

					<?php if( ! $cnt): ?>

						<span class="list-group-item">All computers match</span>

					<?php endif; ?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
