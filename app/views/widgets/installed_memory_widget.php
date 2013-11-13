		<div class="col-lg-4 col-md-6">
			
			<div class="panel panel-default">
				
				<div class="panel-heading">
					
					<h3 class="panel-title"><i class="icon-tasks"></i> Installed Memory</h3>
					
				</div>
				
				<div class="panel-body text-center">
					
					<?php
						$machine = new Machine();
						$in_green = 0;
						$in_yellow = 0;
						$in_red = 0;
						$sql = "SELECT physical_memory, count(1) as count
							FROM machine 
							GROUP BY physical_memory
							ORDER BY physical_memory DESC";
							
						foreach ($machine->query($sql) as $obj) {
							
							// with intval for the memory column should be robust enough for clients not converted yet to int
							
							if (intval($obj->physical_memory) >= 8 ){
								
								$in_green += $obj->count ;
								
							} elseif (intval($obj->physical_memory) < 4 ) {
								
								$in_red += $obj->count ;
								
							} else {
								
								$in_yellow += $obj->count ;
								
							}
						} // end foreach
					?>
					
					<a href="<?=url('show/listing/hardware')?>" class="btn btn-success">
						<span class="bigger-150"> <?=$in_green?> </span><br>
             					8GB +
           				</a>
           				<a href="<?=url('show/listing/hardware')?>" class="btn btn-warning">
             					<span class="bigger-150"> <?=$in_yellow?> </span><br>
             					4GB +
           				</a>
           				<a href="<?=url('show/listing/hardware')?>" class="btn btn-danger">
             					<span class="bigger-150"> <?=$in_red?> </span><br>
             					< 4GB
          				</a>
					
				</div>
				
			</div><!-- /panel -->
			
		</div><!-- /col -->
