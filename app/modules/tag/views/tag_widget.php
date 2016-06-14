<div class="col-lg-4 col-md-6">

<div class="panel panel-default" id="tag-widget">

    <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-tags"></i> <span data-i18n="tag.widget_title"></span></h3>
    
    </div>

    <div class="list-group scroll-box"></div>

</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){

$.getJSON( appUrl + '/module/tag/all_tags/add_count', function( data ) {
    
    var list = $('#tag-widget div.scroll-box').empty();
    
    if(data.length){
        $.each(data, function(i,d){
            var badge = '<span class="badge pull-right">'+d.cnt+'</span>';
            list.append('<a href="'+appUrl+'/module/tag/listing#tag%20=%20'+d.tag+'" class="list-group-item">'+d.tag+badge+'</a>')
        });
    }
    else{
        list.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
    }

});
});
</script>

