<div class="col-lg-4 col-md-6">

    <div id="agent-running-widget" class="panel panel-default">

        <div class="panel-heading">

            <h3 class="panel-title"><i class="fa fa-check-circle"></i>
                <span data-i18n="sentinelone.agent_running"></span>
                <list-link data-url="/show/listing/sentinelone/sentinelone"></list-link>
            </h3>

        </div>

        <div class="panel-body text-center">


            <a id="ar-not_running" class="btn btn-danger disabled">
                <span class="ar-count bigger-150"></span><br>
                <span data-i18n="sentinelone.not_running"></span>
            </a>
            <a id="ar-running" class="btn btn-success disabled">
                <span class="ar-count bigger-150"></span><br>
                <span data-i18n="sentinelone.running"></span>
            </a>

            <span id="ar-nodata" data-i18n=""></span>

        </div>
        
    </div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/sentinelone/get_agent_running_stats', function( data ) {

        if(data.error){
            //alert(data.error);
            return;
        }
        
        var url = appUrl + '/show/listing/sentinelone/sentinelone#'

        // Set urls
        $('#ar-not_running').attr('href', url + encodeURIComponent('agent_running = 0'));
        $('#ar-running').attr('href', url + encodeURIComponent('agent_running = 1'));

        // Show no clients span
        $('#ar-nodata').removeClass('disabled');

        $.each(data.stats, function(prop, val){
            if(val > 0)
            {
                $('#ar-' + prop).removeClass('disabled');
                $('#ar-' + prop + '>span.ar-count').text(val);

                // Hide no clients span
                $('#ar-nodata').addClass('disabled');
            }
            else
            {
                $('#ar-' + prop).addClass('disabled');
            }
        });
    });
});

</script>

