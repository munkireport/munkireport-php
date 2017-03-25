        <div class="col-lg-4 col-md-6">

            <div class="panel panel-default">

                <div class="panel-heading" data-container="body" title="" onclick="location.href=appUrl+'/show/listing/timemachine/timemachine/'">

                    <h3 class="panel-title"><i class="fa fa-clock-o"></i> <span data-i18n="timemachine.timemachine"></span></h3>

                </div>

                <div class="list-group scroll-box">
                    <a id="tm-today" href="<?=url('show/listing/timemachine/timemachine')?>" class="list-group-item list-group-item-success hide">
                        <span class="badge">0</span>
                        <span data-i18n="backup.today"></span>
                    </a>
                    <a id="tm-lastweek" href="<?=url('show/listing/timemachine/timemachine')?>" class="list-group-item list-group-item-warning hide">
                        <span class="badge">0</span>
                        <span data-i18n="backup.lastweek"></span>
                    </a>
                    <a id="tm-week_plus" href="<?=url('show/listing/timemachine/timemachine')?>" class="list-group-item list-group-item-danger hide">
                        <span class="badge">0</span>
                        <span data-i18n="backup.week_plus"></span>
                    </a>
                    <span id="tm-nodata" data-i18n="no_clients" class="list-group-item"></span>
                </div>

            </div><!-- /panel -->

        </div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/timemachine/get_stats', function( data ) {

        // Show no clients span
        $('#tm-nodata').removeClass('hide');

        $.each(data, function(prop, val){
            if(val > 0)
            {
                $('#tm-' + prop).removeClass('hide');
                $('#tm-' + prop + ' > .badge').text(val);

                // Hide no clients span
                $('#tm-nodata').addClass('hide');
            }
            else
            {
                $('#tm-' + prop).addClass('hide');
            }
        });
    });
});


</script>
