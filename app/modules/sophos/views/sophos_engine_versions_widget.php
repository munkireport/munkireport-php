<div class="col-md-6">

    <div class="panel panel-default" id="sophos-engine-versions-widget">

        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-crosshairs"></i>
            <span data-i18n="sophos.engine-versions-title"></span>
            <list-link data-url="/show/listing/sophos/sophos"></list-link>
            </h3>

        </div>

    <div class="panel body">
        <svg style="width:100%"></svg>

    </div>

    </div>

</div>

<script>
$(document).on('appReady', function(e, lang) {

    var conf = {
        url: appUrl + '/module/sophos/get_sophos_engine_version_stats',
        widget: 'sophos-engine-versions-widget',
        elementClickCallback: function(e){
            var label = mr.integerToVersion(e.data.label);
            window.location.href = appUrl + '/show/listing/sophos/sophos#' + label;
        },
        labelModifier: function(label){
            return mr.integerToVersion(label)
        }
    };

    mr.addGraph(conf);

});
</script>
