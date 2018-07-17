<div class="col-lg-4 col-md-6">

    <div id="sophos-install-widget" class="panel panel-default">

        <div class="panel-heading">

            <h3 class="panel-title"><i class="fa fa-crosshairs"></i>
                <span data-i18n="sophos.installs-widget"></span>
                <list-link data-url="/show/listing/sophos/sophos"></list-link>
            </h3>

        </div>

        <div class="panel-body text-center">


            <a id="sophos-notinstalled" class="btn btn-danger hide">
                <span class="sophos-count bigger-150"></span><br>
                <span data-i18n="sophos.not-installed"></span>
            </a>
            <a id="sophos-business" class="btn btn-success hide">
                <span class="sophos-count bigger-150"></span><br>
                <span data-i18n="sophos.sophos-business"></span>
            </a>
            <a id="sophos-central" class="btn btn-success hide">
                <span class="sophos-count bigger-150"></span><br>
                <span data-i18n="sophos.sophos-central"></span>
            </a>

            <span id="sophos-nodata" data-i18n="no_clients"></span>

        </div>

    </div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/sophos/get_sophos_install_stats', function( data ) {

        if(data.error){
            //alert(data.error);
            return;
        }

        var url = appUrl + '/show/listing/sophos/sophos#'

        // Set urls
        $('#sophos-notinstalled').attr('href', url + encodeURIComponent('Not Installed'));
        $('#sophos-business').attr('href', url + encodeURIComponent('Sophos Business'));
        $('#sophos-central').attr('href', url + encodeURIComponent('Sophos Central'));

        // Show no clients span
        $('#sophos-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val > 0 || prop == "notinstalled")
            {
                $('#sophos-' + prop).removeClass('hide');
                $('#sophos-' + prop + '>span.sophos-count').text(val);

                // Hide no clients span
                $('#sophos-nodata').addClass('hide');
            }
            else
            {
                $('#sophos-' + prop).addClass('hide');
            }
        });
    });
});

</script>
