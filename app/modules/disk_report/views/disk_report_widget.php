		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-hdd-o"></i> <span data-i18n="free_disk_space"></span></h3>

				</div>

				<div class="panel-body text-center">


					<a id="disk-danger" class="btn btn-danger hide">
						<span class="disk-count bigger-150"></span><br>
						<span class="disk-label"></span>
					</a>
					<a id="disk-warning" class="btn btn-warning hide">
						<span class="disk-count bigger-150"></span><br>
						<span class="disk-label"></span>
					</a>
					<a id="disk-success" class="btn btn-success hide">
						<span class="disk-count bigger-150"></span><br>
						<span class="disk-label"></span>
					</a>

          <span id="disk-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/disk_report/get_stats', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

			// Get limits
			var dangerThreshold = data.thresholds.danger+'GB',
					warningThreshhold = data.thresholds.warning+'GB',
					url = appUrl + '/show/listing/disk_report/disk#'

			// Set urls
			$('#disk-danger').attr('href', url + encodeURIComponent('freespace < '+dangerThreshold))
			$('#disk-warning').attr('href', url + encodeURIComponent(dangerThreshold+' freespace '+warningThreshhold))
			$('#disk-success').attr('href', url + encodeURIComponent('freespace > '+warningThreshhold))

			// Set labels
			$('#disk-danger span.disk-label').text('< '+dangerThreshold)
			$('#disk-warning span.disk-label').text('< '+warningThreshhold)
			$('#disk-success span.disk-label').text(warningThreshhold+' +')

			// encodeURIComponent

        // Show no clients span
        $('#disk-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val > 0)
            {
                $('#disk-' + prop).removeClass('hide');
                $('#disk-' + prop + '>span.disk-count').text(val);

                // Hide no clients span
                $('#disk-nodata').addClass('hide');
            }
            else
            {
                $('#disk-' + prop).addClass('hide');
            }
        });
    });
});


</script>
