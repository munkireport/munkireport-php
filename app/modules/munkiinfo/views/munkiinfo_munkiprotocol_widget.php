<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="munkiinfo-munkiprotocol-widget">
	  <div class="panel-heading" data-container="body" data-i18n="[title]munkiinfo.munkiprotocol.tooltip">
	    <h3 class="panel-title"><i class="fa fa-magic"></i>
	        <span data-i18n="munkiinfo.munkiprotocol.title"></span>
	        <list-link data-url="/show/listing/munkireport/munki"></list-link>
	    </h3>
	  </div>
	  <div class="panel-body text-center">
        <a tag="http" class="btn btn-danger disabled">
            <span class="bigger-150"> 0 </span><br>
            <span data-i18n="munkiinfo.munkiprotocol.http"></span>
        </a>
        <a tag="https" class="btn btn-success disabled">
            <span class="bigger-150"> 0 </span><br>
            <span data-i18n="munkiinfo.munkiprotocol.https"></span>
        </a>
        <a tag="localrepo" class="btn btn-info disabled">
            <span class="bigger-150"> 0 </span><br>
            <span data-i18n="munkiinfo.munkiprotocol.localrepo"></span>
        </a>
	  </div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appReady', function(){

	var panelBody = $('#munkiinfo-munkiprotocol-widget div.panel-body');

	// Tags
	var tags = ['http', 'https', 'localrepo'];

	// Set url
	$.each(tags, function(i, tag){
		$('#munkiinfo-munkiprotocol-widget a[tag="'+tag+'"]')
			.attr('href', appUrl + '/show/listing/munkiinfo/munkiinfo/#munkiprotocol');
	});

	$(document).on('appUpdate', function(){

		$.getJSON( appUrl + '/module/munkiinfo/get_protocol_stats', function( data ) {

			$.each(tags, function(i, tag){
				// Set count
				$('#munkiinfo-munkiprotocol-widget a[tag="'+tag+'"]')
					.toggleClass('disabled', ! data[tag])
					.find('span.bigger-150')
						.text(+data[tag]);
				// Set localized label
				$('#munkiinfo-munkiprotocol-widget a[tag="'+tag+'"] span.count')
					.text(i18n.t(tag, { count: +data[tag] }));
			});

		});

	});

});

</script>
