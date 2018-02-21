		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-terminal"></i>
					    <span data-i18n="security.ssh_state"></span>
					    <list-link data-url="/show/listing/security/security"></list-link>
					</h3>

				</div>

				<div class="panel-body text-center">


					<a id="ssh-enabled" class="btn btn-danger hide">
						<span class="ssh-count bigger-150"></span><br>
						<span class="ssh-label"></span>
						<span data-i18n="enabled"></span>
					</a>
					<a id="ssh-disabled" class="btn btn-success hide">
						<span class="ssh-count bigger-150"></span><br>
						<span class="ssh-label"></span>
						<span data-i18n="disabled"></span>
					</a>

          <span id="ssh-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_ssh_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security/security'
	$('#ssh-disabled').attr('href', url)
	$('#ssh-enabled').attr('href', url)

        // Show no clients span
        $('#ssh-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#ssh-' + prop).removeClass('hide');
                $('#ssh-' + prop + '>span.ssh-count').text(val);

                // Hide no clients span
                $('#ssh-nodata').addClass('hide');
            }
            else
            {
                $('#ssh-' + prop).addClass('hide');
            }
        });
    });
});


</script>
