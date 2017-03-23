<div class="col-md-6">

	<div class="panel panel-default" id="memory-widget" onclick="location.href=appUrl+'/show/listing/machine/hardware/'">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-lightbulb-o"></i> <span data-i18n="machine.memory.title"></span></h3>
		
		</div>

		<div class="panel-body">

			<svg style="width:100%"></svg>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function(e, lang) {

	var conf = {
		url: appUrl + '/module/machine/get_memory_stats', // Url for json
		widget: 'memory-widget', // Widget id
		margin: {top: 20, right: 10, bottom: 20, left: 70},
		elementClickCallback: function(e){
			var label = e.data.label;
			window.location.href = appUrl + '/show/listing/machine/hardware#' + encodeURIComponent('memory = ') + parseInt(label) + 'GB' ;
		},
		labelModifier: function(label){
			return label + ' GB';
		}
	};

	mr.addGraph(conf);

});
</script>
