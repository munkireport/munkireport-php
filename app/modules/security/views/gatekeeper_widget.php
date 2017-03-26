		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-lock"></i>
					    <span data-i18n="security.gatekeeper"></span>
					    <list-link data-url="/show/listing/security/security"></list-link>
					</h3>

				</div>

				<div class="panel-body text-center">


					<a id="gatekeeper-Disabled" class="btn btn-danger hide">
						<span class="gatekeeper-count bigger-150"></span><br>
						<span class="gatekeeper-label"></span>
						<span data-i18n="disabled"></span>
					</a>
					<a id="gatekeeper-Active" class="btn btn-success hide">
						<span class="gatekeeper-count bigger-150"></span><br>
						<span class="gatekeeper-label"></span>
						<span data-i18n="active"></span>
					</a>

          <span id="gatekeeper-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_gatekeeper_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security/security'
	$('#gatekeeper-Disabled').attr('href', url)
	$('#gatekeeper-Active').attr('href', url)

        // Show no clients span
        $('#gatekeeper-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#gatekeeper-' + prop).removeClass('hide');
                $('#gatekeeper-' + prop + '>span.gatekeeper-count').text(val);

                // Hide no clients span
                $('#gatekeeper-nodata').addClass('hide');
            }
            else
            {
                $('#gatekeeper-' + prop).addClass('hide');
            }
        });
    });
});


</script>
