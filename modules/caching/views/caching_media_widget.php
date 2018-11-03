<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="caching-media-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-music"></i>
			    <span data-i18n="caching.widget_media_title"></span>
			    <list-link data-url="/show/listing/caching/caching"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/caching/caching_media_widget', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#caching-media-widget div.panel-body'),
		baseUrl = appUrl + '/show/listing/caching/caching';
		panel.empty();

		// Set statuses
        if(data.books != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.books, 2)+'</span><br>'+i18n.t('caching.booksdata')+'</a>');
		} else if(data.books) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.books, 2)+'</span><br>'+i18n.t('caching.booksdata')+'</a>');
        }
        
		if(data.music != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.music, 2)+'</span><br>'+i18n.t('caching.musicdata')+'</a>');
		} else if(data.music) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.music, 2)+'</span><br>'+i18n.t('caching.musicdata')+'</a>');
        }
        
		if(data.movies != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.movies, 2)+'</span><br>'+i18n.t('caching.moviesdata')+'</a>');
		} else if(data.movies) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.movies, 2)+'</span><br>'+i18n.t('caching.moviesdata')+'</a>');
        }

    });
});
</script>
