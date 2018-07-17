    <div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="mgmt-url-widget">
        <div class="panel-heading" data-container="body" >
            <h3 class="panel-title"><i class="fa fa-compass"></i>
                <span data-i18n="sentinelone.mgmt_url"></span>
                <list-link data-url="/show/listing/sentinelone/sentinelone"></list-link>
            </h3>
        </div>
        <div class="list-group scroll-box"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
    
    var box = $('#mgmt-url-widget div.scroll-box');
    
    $.getJSON( appUrl + '/module/sentinelone/get_mgmt_url', function( data ) {
        
        box.empty();
        if(data.length){
            $.each(data, function(i,d){
                var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/sentinelone/sentinelone/#'+d.mgmt_url+'" class="list-group-item">'+d.mgmt_url+badge+'</a>')
            });
        }
        else{
            box.append('<span class="list-group-item">'+i18n.t('sentinelone.nourl')+'</span>');
        }
    });
});    
</script>

