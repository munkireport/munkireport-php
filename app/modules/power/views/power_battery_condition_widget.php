		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default" onclick="location.href=appUrl+'/show/listing/power/batteries/'">

				<div class="panel-heading" data-container="body" data-i18n="[title]power.widget.tooltip">

					<h3 class="panel-title"><i class="fa fa-flash"></i> <span data-i18n="power.widget.title"></span></h3>

				</div>

				<div class="list-group scroll-box">
					<a id="power-now" href="<?=url('show/listing/power/batteries#Replace%20Now')?>" class="list-group-item list-group-item-danger hide">
						<span class="badge">0</span>
						<span data-i18n="power.widget.now"></span>
					</a>
					<a id="power-service" href="<?=url('show/listing/power/batteries#Service%20Battery')?>" class="list-group-item list-group-item-warning hide">
						<span class="badge">0</span>
						<span data-i18n="power.widget.service"></span>
					</a>
					<a id="power-soon" href="<?=url('show/listing/power/batteries#Replace%20Soon')?>" class="list-group-item list-group-item-warning hide">
						<span class="badge">0</span>
						<span data-i18n="power.widget.soon"></span>
					</a>
					<a id="power-normal" href="<?=url('show/listing/power/batteries#Normal')?>" class="list-group-item list-group-item-success hide">
						<span class="badge">0</span>
						<span data-i18n="power.widget.normal"></span>
					</a>
					<a id="power-missing" href="<?=url('show/listing/power/batteries#No%20Battery')?>" class="list-group-item list-group-item-danger hide">
						<span class="badge">0</span>
						<span data-i18n="power.widget.nobattery"></span>
					</a>
					<span id="power-nodata" data-i18n="no_clients" class="list-group-item"></span>
				</div>

<script>
$(document).on('appReady appUpdate', function(e, lang) {

	$.getJSON( appUrl + '/module/power/conditions', function( data ) {

		// Show no clients span
		$('#power-nodata').removeClass('hide');

		$.each(data, function(prop, val){
			if(val > 0)
			{
				$('#power-' + prop).removeClass('hide');
				$('#power-' + prop + '>.badge').html(val);

				// Hide no clients span
				$('#power-nodata').addClass('hide');
			}
			else
			{
				$('#power-' + prop).addClass('hide');
			}
		});
	});
});


</script>




			</div><!-- /panel -->

		</div><!-- /col -->
