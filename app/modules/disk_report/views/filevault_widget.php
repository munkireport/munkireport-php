<div class="col-lg-4 col-md-6">

	<div id="filevault-status-widget" class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-lock"></i> <span data-i18n="disk_report.filevault_widget_title"></span></h3>

		</div>

		<div class="panel-body text-center">


			<a id="fv-unencrypted" class="btn btn-danger hide">
				<span class="disk-count bigger-150"></span><br>
				<span data-i18n="unencrypted"></span>
			</a>
			<a id="fv-encrypted" class="btn btn-success hide">
				<span class="disk-count bigger-150"></span><br>
				<span data-i18n="encrypted"></span>
			</a>

			<span id="fv-nodata" data-i18n="no_clients"></span>

		</div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

	$.getJSON( appUrl + '/module/disk_report/get_filevault_stats', function( data ) {

		if(data.error){
			//alert(data.error);
			return;
		}

		var url = appUrl + '/show/listing/security/security#'

		// Set urls
		$('#fv-unencrypted').attr('href', url + encodeURIComponent('encrypted = 0'))
		$('#fv-encrypted').attr('href', url + encodeURIComponent('encrypted = 1'))

		// Show no clients span
		$('#fv-nodata').removeClass('hide');

		$.each(data.stats, function(prop, val){
			if(val > 0)
			{
				$('#fv-' + prop).removeClass('hide');
				$('#fv-' + prop + '>span.disk-count').text(val);

				// Hide no clients span
				$('#fv-nodata').addClass('hide');
			}
			else
			{
				$('#fv-' + prop).addClass('hide');
			}
		});
	});
});

</script>
