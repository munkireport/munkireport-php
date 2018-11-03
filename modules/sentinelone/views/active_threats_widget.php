<div class="col-lg-4 col-md-6">

    <div id="active-threats-widget" class="panel panel-default">

        <div class="panel-heading">

            <h3 class="panel-title"><i class="fa fa-meh-o"></i>
                <span data-i18n="sentinelone.active_threats"></span>
                <list-link data-url="/show/listing/sentinelone/sentinelone"></list-link>
            </h3>

        </div>

        <div class="panel-body text-center">

            <a id="at-threats_present" class="btn btn-danger disabled">
                <span class="at-count bigger-150"></span><br>
                <span data-i18n="sentinelone.threats_present"></span>
            </a>
            <a id="at-threats_not_present" class="btn btn-success disabled">
                <span class="at-count bigger-150"></span><br>
                <span data-i18n="sentinelone.no_active_threats"></span>
            </a>

            <span id="at-nodata" data-i18n=""></span>

        </div>

    </div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/sentinelone/get_active_threats_stats', function( data ) {

        if(data.error){
            //alert(data.error);
            return;
        }

        var url = appUrl + '/show/listing/sentinelone/sentinelone#'

        // Set urls
        $('#at-threats_not_present').attr('href', url + encodeURIComponent('active_threats_present = 0'));
        $('#at-threats_present').attr('href', url + encodeURIComponent('active_threats_present = 1'));

        // Show no clients span
        $('#at-nodata').removeClass('disabled');

        $.each(data.stats, function(prop, val){
            if(val > 0)
            {
                $('#at-' + prop).removeClass('disabled');
                $('#at-' + prop + '>span.at-count').text(val);

                // Hide no clients span
                $('#at-nodata').addClass('disabled');
            }
            else
            {
                $('#at-' + prop).addClass('disabled');
            }
        });
    });
});

</script>
