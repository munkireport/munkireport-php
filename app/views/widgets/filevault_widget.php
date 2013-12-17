		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-lock"></i> Filevault 2 status</h3>
				
				</div>

				<div class="list-group">

				<?php
					$fv = new filevault_status_model(); 
					$sql = "SELECT count(1) AS count,
							filevault_status FROM filevault_status
							GROUP BY filevault_status
							ORDER BY filevault_status";
					$cnt = 0;
				
					foreach($fv->query($sql) as $obj) {
							if (empty($obj->filevault_status)) {
								echo '<a class="list-group-item">' . lang('unknown') . '<span class="badge pull-right">' .$obj->count . '</span></a>';
							} else {
								echo '<a href=' . url('show/listing/security#'.$obj->filevault_status) . 
								' class="list-group-item"><span class="badge">' . $obj->count . '</span>' . $obj->filevault_status . '</a>';
							}
							$cnt++;
					}

					if (! $cnt){
						echo '<span class="list-group-item">No Filevault status available</span>';
					}
				?>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->