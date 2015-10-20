<?php foreach(conf('apps_to_track', array()) as $string):?> 
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default app-widget" data-ident="<?php echo $string;?>">
        <div class="panel-heading" data-container="body" title="Known versions of <?php echo $string;?>">
            <h3 class="panel-title"><i class="fa fa-tachometer"></i> <span class="app"><?php echo $string;?></span></h3>
        </div>
        <div class="list-group scroll-box"></div>
    </div><!-- /panel -->
</div><!-- /col -->
<?php endforeach?>

<script>
$(document).on('appUpdate', function(){
    var baseUrl = appUrl + '/module/inventory/appVersions/';
    // Compile list of apps
    $('.app-widget span.app').each(function(){
        var app = $(this).text()
        // Get data for app
        $.getJSON(baseUrl + app, function(data){
            var lg = $(".app-widget[data-ident='"+app+"'] div.list-group");
            // Make a list item for every datapoint (d)
            $.each(data, function(i, d){
                lg.append($('<a>')
                    .addClass('list-group-item')
                    .attr('href', appUrl + '/module/inventory/items/' + app + '/' + d.version)
                    .text(d.version)
                    .append($('<span>')
                        .addClass('badge pull-right')
                        .text(d.count)));
            });
        });
    });
});
</script>

