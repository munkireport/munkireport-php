		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading" data-container="body" title="Clients registered this week">

					<h3 class="panel-title"><i class="fa fa-star-o"></i> New clients <span id="new-clients" class="badge pull-right"></span></h3>

				</div>

				<div class="list-group scroll-box">
				  	<?$queryobj = new Machine_model();// Generic queryobject?>

				  	<?	$lastweek = time() - 60 * 60 * 24 * 7;$cnt=0;
				  		$sql = "SELECT machine.serial_number, computer_name, reg_timestamp FROM machine LEFT JOIN reportdata USING (serial_number) WHERE reg_timestamp > $lastweek ORDER BY reg_timestamp DESC"?>
					<?foreach($queryobj->query($sql) as $obj):?> 
					<a class="list-group-item" href="<?=url('clients/detail/'.$obj->serial_number)?>"><?=$obj->computer_name?>
						<span class="pull-right"><time datetime="<?=$obj->reg_timestamp?>">...</time></span>
					</a>
					<?$cnt++?>
					<?endforeach?>
					<?if( ! $cnt):?>
					<span class="list-group-item">No new clients</span>
					<?endif?>
				</div>
			<script>
			$(document).on('appReady', function() {
				
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