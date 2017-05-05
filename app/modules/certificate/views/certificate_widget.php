        <div class="col-lg-4 col-md-6">

            <div class="panel panel-default">

                <div class="panel-heading" data-container="body" title="">

                    <h3 class="panel-title"><i class="fa fa-certificate"></i>
                        <span data-i18n="certificate.title"></span>
                        <list-link data-url="/show/listing/certificate/certificate"></list-link>
                    </h3>

                </div>

                <div class="list-group scroll-box">
                    <a id="cert-ok" href="<?=url('show/listing/certificate/certificate')?>" class="list-group-item list-group-item-success hide">
                        <span class="badge">0</span>
                        <span data-i18n="certificate.ok"></span>
                    </a>
                    <a id="cert-soon" href="<?=url('show/listing/certificate/certificate')?>" class="list-group-item list-group-item-warning hide">
                        <span class="badge">0</span>
                        <span data-i18n="certificate.soon"></span>
                    </a>
                    <a id="cert-expired" href="<?=url('show/listing/certificate/certificate')?>" class="list-group-item list-group-item-danger hide">
                        <span class="badge">0</span>
                        <span data-i18n="certificate.expired"></span>
                    </a>
                    <span id="cert-nodata" data-i18n="no_clients" class="list-group-item"></span>
                </div>

            </div><!-- /panel -->

        </div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/certificate/get_stats', function( data ) {

        // Show no clients span
        $('#cert-nodata').removeClass('hide');

        $.each(data, function(prop, val){
            if(val > 0)
            {
                $('#cert-' + prop).removeClass('hide');
                $('#cert-' + prop + ' span.badge').text(val);

                // Hide no clients span
                $('#cert-nodata').addClass('hide');
            }
            else
            {
                $('#cert-' + prop).addClass('hide');
            }
        });
    });
});


</script>
