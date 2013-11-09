 		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-apple"></i> Pending Apple Updates</h3>
				
				</div>

				<div class="list-group scroll-box">

				<?php
					$mr = new Munkireport; 
					$sql = "SELECT serial_number, report_plist FROM munkireport where pendinginstalls > 0";
					// Get compression (fixme: we should be able to read this from the model) 
					$compress = function_exists('gzdeflate');
					
					//loop through all the plists
					foreach($mr->query($sql) as $obj){
		
            			$report_plist = unserialize( $compress ? gzinflate( $obj->report_plist ) : $obj->report_plist );
            			
            			//loop inside the plist to get the updates and fill the updates_array with the displayed names
                		foreach($report_plist['AppleUpdates'] AS $update){
                			$updates_array[] = $update['apple_product_name'] . ' ' . $update['version_to_install'];
                		}
                		
					}
					//group the updates by count now that the loops are done
					$updates_array = array_count_values($updates_array);
					arsort($updates_array);
				?> 
				<?foreach(array_keys($updates_array) as $obj):?>


					<a href="<?=url('show/listing/munki#pendinginstalls')?>" class="list-group-item">
					<!--//echo first the key names (update name) and then their values (count) -->
                	<?=$obj?>
                	<span class="badge pull-right"><?=$updates_array[$obj]?></span>
            		</a>

				<?endforeach?>
				</div>

			</div><!-- /panel -->

		</div><!-- /col -->