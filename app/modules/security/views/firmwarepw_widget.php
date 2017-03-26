		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-lock"></i>
    					<span data-i18n="security.firmwarepw"></span>
        				<list-link data-url="/show/listing/security/security"></list-link>
					</h3>

				</div>

				<div class="panel-body text-center">


					<a id="firmwarepw-disabled" class="btn btn-danger hide">
						<span class="firmwarepw-count bigger-150"></span><br>
						<span class="firmwarepw-label"></span>
						<span data-i18n="disabled"></span>
					</a>
					<a id="firmwarepw-enabled" class="btn btn-success hide">
						<span class="firmwarepw-count bigger-150"></span><br>
						<span class="firmwarepw-label"></span>
						<span data-i18n="enabled"></span>
					</a>

          <span id="firmwarepw-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_firmwarepw_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security/security'
	$('#firmwarepw-disabled').attr('href', url)
	$('#firmwarepw-enabled').attr('href', url)

        // Show no clients span
        $('#firmwarepw-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#firmwarepw-' + prop).removeClass('hide');
                $('#firmwarepw-' + prop + '>span.firmwarepw-count').text(val);

                // Hide no clients span
                $('#firmwarepw-nodata').addClass('hide');
            }
            else
            {
                $('#firmwarepw-' + prop).addClass('hide');
            }
        });
    });
});


</script>
