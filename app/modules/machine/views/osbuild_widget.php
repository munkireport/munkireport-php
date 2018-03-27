<div class="col-md-6">

	<div class="panel panel-default" id="osbuild-widget">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-apple"></i>
			    <span data-i18n="machine.osbuild.title"></span>
			    <list-link data-url="/show/listing/reportdata/clients"></list-link>
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
		url: appUrl + '/module/machine/osbuild', // Url for json
		widget: 'osbuild-widget', // Widget id
		elementClickCallback: function(e){
			var label = e.data.label;
			window.location.href = appUrl + '/show/listing/reportdata/clients#' + label;
		},
		labelModifier: function(label){
			return label
		}
	};

	mr.addGraph(conf);

});

</script>
