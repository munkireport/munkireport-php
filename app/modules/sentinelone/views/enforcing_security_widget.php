<div class="col-lg-4 col-md-6">

    <div id="enforcing-security-widget" class="panel panel-default">

        <div class="panel-heading">

            <h3 class="panel-title"><i class="fa fa-shield"></i>
                <span data-i18n="sentinelone.enforcing_security"></span>
                <list-link data-url="/show/listing/sentinelone/sentinelone"></list-link>
            </h3>

        </div>

        <div class="panel-body text-center">


            <a id="es-threats_not_present" class="btn btn-danger hide">
                <span class="es-count bigger-150"></span><br>
                <span data-i18n="sentinelone.not_enforced"></span>
            </a>
            <a id="es-threats_present" class="btn btn-success hide">
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
        $('#es-threats_not_present').attr('href', url + encodeURIComponent('enforcing_security = 0'));
        $('#es-threats_present').attr('href', url + encodeURIComponent('enforcing_security = 1'));

        // Show no clients span
        $('#es-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val > 0)
            {
                $('#es-' + prop).removeClass('hide');
                $('#es-' + prop + '>span.es-count').text(val);

                // Hide no clients span
                $('#es-nodata').addClass('hide');
            }
            else
            {
                $('#es-' + prop).addClass('hide');
            }
        });
    });
});

</script>
