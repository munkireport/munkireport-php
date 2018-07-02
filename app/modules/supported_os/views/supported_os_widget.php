<div class="col-md-6">
	<div class="panel panel-default" id="supported-os-widget">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-apple"></i>
			    <span data-i18n="supported_os.listingtitle"></span>
			    <list-link data-url="/show/listing/supported_os/supported_os"></list-link>
			</h3>
		</div>
		<div class="panel-body">
			<svg style="width:100%"></svg>
		</div>
	</div><!-- /panel -->
</div><!-- /col-md-6 -->

<script>
$(document).on('appReady', function(e, lang) {

	var conf = {
		url: appUrl + '/module/supported_os/os', // URL for JSON
		widget: 'supported-os-widget', // Widget ID
		elementClickCallback: function(e){
			var label = mr.integerToVersion(e.data.label);
			window.location.href = appUrl + '/show/listing/supported_os/supported_os#' + label;
		},
		labelModifier: function(label){
			return mr.integerToVersion(label)
		}
	};

	mr.addGraph(conf);

});

</script>
