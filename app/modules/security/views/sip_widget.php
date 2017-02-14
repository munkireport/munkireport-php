		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-lock"></i> <span data-i18n="security.sip_status"></span></h3>

				</div>

				<div class="panel-body text-center">


					<a id="sip-Disabled" class="btn btn-danger hide">
						<span class="sip-count bigger-150"></span><br>
						<span class="sip-label"></span>
						<span data-i18n="security.sip_disabled"></span>
					</a>
					<a id="sip-Active" class="btn btn-success hide">
						<span class="sip-count bigger-150"></span><br>
						<span class="sip-label"></span>
						<span data-i18n="security.sip_active"></span>
					</a>

          <span id="sip-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_sip_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security'
	$('#sip-Disabled').attr('href', url)
	$('#sip-Active').attr('href', url)

        // Show no clients span
        $('#sip-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#sip-' + prop).removeClass('hide');
                $('#sip-' + prop + '>span.sip-count').text(val);

                // Hide no clients span
                $('#sip-nodata').addClass('hide');
            }
            else
            {
                $('#sip-' + prop).addClass('hide');
            }
        });
    });
});


</script>
