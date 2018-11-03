<div class="col-lg-4 col-md-6">

    <div id="firewall-state-widget" class="panel panel-default">

        <div class="panel-heading">

            <h3 class="panel-title"><i class="fa fa-fire"></i>
                <span data-i18n="security.firewall_state"></span>
                <list-link data-url="/show/listing/security/security"></list-link>
            </h3>

        </div>

        <div class="panel-body text-center">


            <a id="fw-disabled" class="btn btn-danger hide">
                <span class="fw-count bigger-150"></span><br>
                <span data-i18n="disabled"></span>
            </a>
            <a id="fw-enabled" class="btn btn-success hide">
                <span class="fw-count bigger-150"></span><br>
                <span data-i18n="enabled"></span>
            </a>
            <a id="fw-blockall" class="btn btn-success hide">
                <span class="fw-count bigger-150"></span><br>
                <span data-i18n="security.block_all"></span>
            </a>

            <span id="fw-nodata" data-i18n="no_clients"></span>

        </div>

    </div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_firewall_state_stats', function( data ) {

        if(data.error){
            //alert(data.error);
            return;
        }

        var url = appUrl + '/show/listing/security/security#'

        // Set urls
        $('#fw-disabled').attr('href', url + encodeURIComponent('firewall = 0'));
        $('#fw-enabled').attr('href', url + encodeURIComponent('firewall = 1'));
        $('#fw-blockall').attr('href', url + encodeURIComponent('firewall = 2'));

        // Show no clients span
        $('#fw-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val > 0)
            {
                $('#fw-' + prop).removeClass('hide');
                $('#fw-' + prop + '>span.fw-count').text(val);

                // Hide no clients span
                $('#fw-nodata').addClass('hide');
            }
            else
            {
                $('#fw-' + prop).addClass('hide');
            }
        });
    });
});

</script>
