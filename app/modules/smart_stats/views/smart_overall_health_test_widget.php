<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="smart-overall-health-test">

		<div class="panel-heading" data-container="body">

			<h3 class="panel-title"><i class="fa fa-user-md"></i>
			    <span data-i18n="smart_stats.reporttitle"></span>
			    <list-link data-url="/show/listing/smart_stats/smart_stats"></list-link>
			</h3>

		</div>

		<div class="panel-body text-center">

			<a tag="failed" class="btn btn-danger disabled">
				<span class="bigger-150"> 0 </span><br>
				<span data-i18n="smart_stats.failed"></span>
			</a>
			<a tag="unknown" class="btn btn-warning disabled">
				<span class="bigger-150"> 0 </span><br>
				<span data-i18n="smart_stats.unknown"></span>
			</a>
			<a tag="passed" class="btn btn-success disabled">
				<span class="bigger-150"> 0 </span><br>
				<span data-i18n="smart_stats.passed"></span>
			</a>

		</div>
	</div><!-- /panel -->

</div><!-- /col -->


<script>
$(document).on('appReady', function(){

	var panelBody = $('#smart-overall-health-test div.panel-body');

	// Tags
	var tags = ['failed', 'unknown', 'passed'];

	// Set url
	$.each(tags, function(i, tag){
		$('#smart-overall-health-test a[tag="'+tag+'"]')
			.attr('href', appUrl + '/show/listing/smart_stats/smart_stats/#'+tag);
	});

	$(document).on('appUpdate', function(){

		$.getJSON( appUrl + '/module/smart_stats/get_smart_stats', function( data ) {

			$.each(tags, function(i, tag){
				// Set count
				$('#smart-overall-health-test a[tag="'+tag+'"]')
					.toggleClass('disabled', ! data[tag])
					.find('span.bigger-150')
						.text(+data[tag]);
				// Set localized label
				$('#smart-overall-health-test a[tag="'+tag+'"] span.count')
					.text(i18n.t(tag, { count: +data[tag] }));
			});

		});

	});

});

</script>

