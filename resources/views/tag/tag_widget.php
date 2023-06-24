<div class="col-lg-4 col-md-6">
    <div class="card" id="tag-widget">
        <div class="card-header">
            <i class="fa fa-tags"></i>
            <span data-i18n="tag.widget_title"></span>
            <a href="/module/tag/listing" class="pull-right text-reset"><i class="fa fa-list"></i></a>
        </div>
        <div class="scroll-box">
            <ul class="list-group"></ul>
        </div>
    </div><!-- /card -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){

$.getJSON( appUrl + '/module/tag/all_tags/add_count', function( data ) {
    
    var list = $('#tag-widget div.scroll-box ul.list-group').empty();
    
    if(data.length){
        $.each(data, function(i,d){
            var badge = '<span class="badge badge-secondary pull-right">'+d.cnt+'</span>';
            list.append('<a href="'+appUrl+'/module/tag/listing#tag%20=%20'+d.tag+'" class="list-group-item">'+d.tag+badge+'</a>')
        });
    }
    else{
        list.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
    }

});
});
</script>

