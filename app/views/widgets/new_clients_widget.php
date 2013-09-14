		<div class="col-lg-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-star-empty"></i> New clients <span id="new-clients" class="badge pull-right"></span></h3>

				</div>
				<div style="height: 200px; overflow-y: scroll">
				  	<?$queryobj = new Machine();// Generic queryobject?>

				  	<?	$lastweek = time() - 60 * 60 * 24 * 7;
				  		$sql = "SELECT machine.serial_number, computer_name, reg_timestamp FROM machine LEFT JOIN reportdata USING (serial_number) WHERE reg_timestamp > $lastweek ORDER BY reg_timestamp DESC"?>
					<table class="table">
						<?foreach($queryobj->query($sql) as $obj):?> 
						<tr>
							<td><a class="btn btn-xs btn-default" href="<?=url('clients/detail/'.$obj->serial_number)?>"><?=$obj->computer_name?></a></td>
							<td class="text-right"><time datetime="<?=$obj->reg_timestamp?>">...</time></td>
						</tr>
						<?endforeach?>
					</table>
				</div>
			<script>
			$(document).ready(function() {
				
				// New clients + relative time
				var cnt=0;
				$( "time" ).each(function( index ) {
					var date = new Date($(this).attr('datetime') * 1000);
					$(this).html(moment(date).fromNow());
					cnt++;
				});
				$('#new-clients').html(cnt);


				
			});
			</script>

			</div><!-- /panel -->

		</div><!-- /col -->