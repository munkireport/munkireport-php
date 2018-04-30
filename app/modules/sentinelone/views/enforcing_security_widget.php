<div class="col-lg-4 col-md-6">

    <div id="enforcing-security-widget" class="panel panel-default">

        <div class="panel-heading">

            <h3 class="panel-title"><i class="fa fa-shield"></i>
                <span data-i18n="sentinelone.enforcing_security"></span>
                <list-link data-url="/show/listing/sentinelone/sentinelone"></list-link>
            </h3>

        </div>

        <div class="panel-body text-center">


            <a id="es-not_enforced" class="btn btn-danger disabled">
                <span class="es-count bigger-150"></span><br>
                <span data-i18n="sentinelone.not_enforced"></span>
            </a>
            <a id="es-enforced" class="btn btn-success disabled">
                <span class="es-count bigger-150"></span><br>
                <span data-i18n="sentinelone.enforced"></span>
            </a>

            <span id="es-nodata" data-i18n=""></span>

        </div>

    </div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/sentinelone/get_enforcing_security_stats', function( data ) {

        if(data.error){
            //alert(data.error);
            return;
        }

        var url = appUrl + '/show/listing/sentinelone/sentinelone#'

        // Set urls
        $('#es-not_enforced').attr('href', url + encodeURIComponent('enforcing_security = 0'));
        $('#es-enforced').attr('href', url + encodeURIComponent('enforcing_security = 1'));

        // Show no clients span
        $('#es-nodata').removeClass('disabled');

        $.each(data.stats, function(prop, val){
            if(val > 0)
            {
                $('#es-' + prop).removeClass('disabled');
                $('#es-' + prop + '>span.es-count').text(val);

                // Hide no clients span
                $('#es-nodata').addClass('disabled');
            }
            else
            {
                $('#es-' + prop).addClass('disabled');
            }
        });
    });
});

</script>
