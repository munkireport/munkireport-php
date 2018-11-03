<?php foreach(conf('apps_to_track', array()) as $string):?>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default app-widget" data-ident="<?php echo $string;?>">
        <div class="panel-heading" data-container="body">
            <h3 class="panel-title"><i class="fa fa-tachometer"></i> <span></span></h3>
        </div>
        <div class="list-group scroll-box"></div>
    </div><!-- /panel -->
</div><!-- /col -->
<?php endforeach?>

<script>
$(document).on('appReady', function(){

    var baseUrl = appUrl + '/module/inventory/appVersions/',
        apps = [];
    // Compile list of apps
    $('.app-widget').each(function(){
        var appName = $(this).data('ident');
        // Set tooltip
        $(this).find('div.panel-heading')
            .attr('title', i18n.t('inventory.app.versions', {app: appName}))
            .tooltip();
        // Create url
        var url = appUrl + '/module/inventory/items/' + appName;
        // Set title
        $(this).find('.panel-title span')
            .text(appName)
            .after('<a href="'+url+'" class="btn btn-xs pull-right"><i class="fa fa-list"></i></a>');
        // Add to app list
        apps.push({
            name: appName,
            list: $(this).find('div.list-group')
        });
    });

    $(document).on('appUpdate', function(){

        // Get data for apps
        $.each(apps, function(i, app){
            $.getJSON(baseUrl + app.name, function(data){
                // Empty old list
                app.list.empty();
                // Make a list item for every datapoint (d)
                // Sort on version number (prefix with x to disable float comparison)
                data.sort(function(a,b){
                    return mr.naturalSort('x'+b.version, 'x'+a.version);
                });

                $.each(data, function(j, d){
                    app.list.append($('<a>')
                        .addClass('list-group-item')
                        .attr('href', appUrl + '/module/inventory/items/' + app.name + '/' + d.version)
                        .text(d.version)
                        .append($('<span>')
                            .addClass('badge pull-right')
                            .text(d.count)));
                });
            });
        });
    }); // end appUpdate
});

</script>
