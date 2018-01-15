		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-lock"></i>
    					<span data-i18n="security.skel.kext-loading"></span>
    					<list-link data-url="/show/listing/security/security"></list-link>
					</h3>

				</div>

				<div class="panel-body text-center">


					<a id="skel-disabled" class="btn btn-info hide">
						<span class="skel-count bigger-150"></span><br>
						<span class="skel-label"></span>
						<span data-i18n="security.skel.all-approved"></span>
					</a>
					<a id="skel-enabled" class="btn btn-info hide">
						<span class="skel-count bigger-150"></span><br>
						<span class="skel-label"></span>
						<span data-i18n="security.skel.user-approved"></span>
					</a>

          <span id="skel-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_skel_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security/security'
	$('#skel-disabled').attr('href', url)
	$('#skel-enabled').attr('href', url)

        // Show no clients span
        $('#skel-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#skel-' + prop).removeClass('hide');
                $('#skel-' + prop + '>span.skel-count').text(val);

                // Hide no clients span
                $('#skel-nodata').addClass('hide');
            }
            else
            {
                $('#skel-' + prop).addClass('hide');
            }
        });
    });
});


</script>
