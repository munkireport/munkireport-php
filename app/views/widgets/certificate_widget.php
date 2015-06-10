        <div class="col-lg-4 col-md-6">

            <div class="panel panel-default">

                <div class="panel-heading" data-container="body" title="">

                    <h3 class="panel-title"><i class="fa fa-certificate"></i> <span data-i18n="widget.certificate.title"></span></h3>

                </div>

                <div class="list-group scroll-box">
                    <a id="cert-ok" href="<?=url('show/listing/certificate')?>" class="list-group-item list-group-item-success hide">
                        <span class="badge">0</span>
                        <span data-i18n="widget.certificate.ok"></span>
                    </a>
                    <a id="cert-soon" href="<?=url('show/listing/certificate')?>" class="list-group-item list-group-item-warning hide">
                        <span class="badge">0</span>
                        <span data-i18n="widget.certificate.soon"></span>
                    </a>
                    <a id="cert-expired" href="<?=url('show/listing/certificate')?>" class="list-group-item list-group-item-danger hide">
                        <span class="badge">0</span>
                        <span data-i18n="widget.certificate.expired"></span>
                    </a>
                    <span id="cert-nodata" data-i18n="no_clients" class="list-group-item"></span>
                </div>
        
            </div><!-- /panel -->

        </div><!-- /col -->

<script>
$(document).on('appReady appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/certificate/get_stats', function( data ) {

        // Show no clients span
        $('#cert-nodata').removeClass('hide');

        $.each(data, function(prop, val){
            if(val > 0)
            {
                $('#cert-' + prop).removeClass('hide');
                $('#cert-' + prop + '>.badge').html(val);

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

