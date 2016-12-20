<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="munki-versions-widget">

		<div class="panel-heading" data-container="body" data-i18n="[title]widget.munki_versions.tooltip">

	    	<h3 class="panel-title"><i class="fa fa-sitemap"></i> <span data-i18n="widget.munki_versions.title"></span></h3>

		</div>
		
		<div class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){
	
	$.getJSON( appUrl + '/module/munkireport/get_versions', function( data ) {
        
        var list = $('#munki-versions-widget div.scroll-box').empty();
        
        if(data.length){
			// Sort on version number
			data.sort(function(a,b){
				return mr.naturalSort(b.version, a.version);
			});
            $.each(data, function(i,d){
                var badge = '<span class="badge pull-right">'+d.count+'</span>';
				d.version = d.version || i18n.t('unknown');
                list.append('<a href="'+appUrl+'/show/listing/munki/#'+d.version+'" class="list-group-item">'+d.version+badge+'</a>')
            });
        }
        else{
            list.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
        }

    });
});
</script>