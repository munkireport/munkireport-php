<div class="col-lg-4 col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading" data-container="body">
            <h3 class="panel-title"><i class="fa fa-clock-o"></i> 
                <span data-i18n="backup2go.widget_title"></span>
                <list-link data-url="/show/listing/backup2go/backup2go/"></list-link>
            </h3>
        </div>

        <div class="list-group scroll-box">
            <a id="b2g-b2g_ok" href="<?=url('/show/listing/backup2go/backup2go')?>" class="list-group-item list-group-item-success hide">
                <span class="badge">0</span>
                <span data-i18n="backup.ok"></span>
            </a>
            <a id="b2g-b2g_warning" href="<?=url('/show/listing/backup2go/backup2go')?>" class="list-group-item list-group-item-warning hide">
                <span class="badge">0</span>
                <span data-i18n="backup.four_weeks_less"></span>
            </a>
            <a id="b2g-b2g_danger" href="<?=url('/show/listing/backup2go/backup2go')?>" class="list-group-item list-group-item-danger hide">
                <span class="badge">0</span>
                <span data-i18n="backup.four_weeks_plus"></span>
            </a>
            <span id="b2g-nodata" data-i18n="no_clients" class="list-group-item"></span>
        </div>
    </div>
</div>

<script>
$(document).on('appReady appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/backup2go/get_stats', function( data ) {
        // Show no clients span
        $('#b2g-nodata').removeClass('hide');

        //load colour classes into list
        $.each(data, function(prop, val){
            if(val > 0){
                $('#b2g-' + prop).removeClass('hide');
                $('#b2g-' + prop + ' > .badge').text(val);

                // Hide no clients span
                $('#b2g-nodata').addClass('hide');
            }
            else {
                $('#b2g-' + prop).addClass('hide');
            }
        });
    });
});
</script>
