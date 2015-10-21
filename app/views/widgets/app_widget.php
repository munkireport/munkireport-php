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
            .attr('title', i18n.t('widget.app.versions', {app: appName}))
            .tooltip();
        // Set title
        $(this).find('.panel-title span').text(appName);
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
                $.each(data, function(j, d){
                    app.list.append($('<a>')
                        .addClass('list-group-item')
                        .attr('href', appUrl + '/module/inventory/items/' + app + '/' + d.version)
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

