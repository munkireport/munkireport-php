<div class="col-sm-6">

	<div class="panel panel-default" id='hardware-age-widget'>

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-clock-o"></i> <span data-i18n="widget.age.title"></span></h3>

		</div>

		<div class="panel-body">

			<svg style="width:100%"></svg>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function() {
	
	var conf = {
		url: appUrl + '/module/warranty/age', // Url for json
		widget: 'hardware-age-widget', // Widget id
		margin: {top: 20, right: 10, bottom: 20, left: 70},
		elementClickCallback: function(e){
			window.location.href = appUrl + '/show/listing/warranty';
		},
		labelModifier: function(label){
			return label + ' ' + i18n.t('date.year');
		}
	};

	mr.addGraph(conf);

});
</script>
