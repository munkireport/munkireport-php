<div class="col-lg-6 col-md-6">

	<div class="panel panel-default" id="hardware-type-widget">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-desktop"></i>
			    <span data-i18n="machine.hardware_type_title"></span>
    			<list-link data-url="/show/listing/machine/hardware"></list-link>
			</h3>

		</div>

		<div class="panel-body">

			<svg style="width:100%"></svg>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function(e, lang) {
	
	var conf = {
		url: appUrl + '/module/machine/hw', // Url for json
		widget: 'hardware-type-widget', // Widget id
		margin: {top: 20, right: 10, bottom: 20, left: 90},
	};

	mr.addGraph(conf);
	
});
</script>
