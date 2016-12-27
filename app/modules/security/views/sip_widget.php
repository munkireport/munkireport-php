		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-lock"></i> <span data-i18n="sip_status"></span></h3>

				</div>

				<div class="panel-body text-center">


					<a id="sip-Disabled" class="btn btn-danger hide">
						<span class="sip-count bigger-150"></span><br>
						<span class="sip-label"></span>
						<span data-i18n="sip.disabled"></span>
					</a>
					<a id="sip-Active" class="btn btn-success hide">
						<span class="sip-count bigger-150"></span><br>
						<span class="sip-label"></span>
						<span data-i18n="sip.enabled"></span>
					</a>

          <span id="sip-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_sip_stats', function( data ) {
	console.log( data );
    	if(data.error){
    		//alert(data.error);
    		return;
    	}

			// Set labels
			$('#sip-Disabled span.sip-label').text('SIP Disabled') //TODO make this locale based
			$('#sip-Active span.sip-label').text('SIP Active') // TODO make this locale based


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
