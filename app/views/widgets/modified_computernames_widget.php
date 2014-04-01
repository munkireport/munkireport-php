		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body" title="Computers where the computer name doesn't match the AD name">

					<h3 class="panel-title"><i class="fa fa-code-fork"></i> Not matching AD Names</h3>

				</div>

				<div class="list-group scroll-box">

				<?php
					$machine = new Machine_model();
					$sql = "SELECT directoryservice.serial_number, directoryservice.computeraccount,
									machine.serial_number, machine.computer_name
									FROM directoryservice AS directoryservice
									INNER JOIN machine as machine
									ON directoryservice.serial_number = machine.serial_number
									WHERE NOT directoryservice.computeraccount = ''";
					$cnt = 0;
				?>
					<?foreach($machine->query($sql) as $obj):?>
						<!--//removing the dollar sign first, lowercase comparison for non-matching-->
						<?if (strtolower(str_replace('$', '',$obj->computeraccount)) !== strtolower($obj->computer_name)):?>

							<a href="<?=url('clients/detail/'.$obj->serial_number.'#tab_directory-tab')?>" class="list-group-item">
								<span class="badge">1</span>
								<?=$obj->computer_name?> != <?=str_replace('$', '',$obj->computeraccount)?>
							</a>
							<?$cnt++?>

						<?endif?>

					<?endforeach?>

					<?if( ! $cnt):?>

						<span class="list-group-item">All computers match</span>

					<?endif?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->
