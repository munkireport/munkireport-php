<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="pending-widget">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-moon-o"></i>
                <span data-i18n="managedinstalls.widget.pending.title"></span>
                <list-link data-url="/module/managedinstalls/listing/#pending_install"></list-link>
            </h3>
        </div>
        <div class="list-group scroll-box"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>

$(document).on('appUpdate', function(e, lang) {

	var box = $('#pending-widget div.scroll-box'),
        hours = 24; // Hours back

	$.getJSON( appUrl + '/module/managedinstalls/get_clients/pending_install/'+hours, function( data ) {

		box.empty();

		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>',
                    url = appUrl+'/clients/detail/'+d.serial_number+'#tab_munki';

                d.computer_name = d.computer_name || i18n.t('empty');
				box.append('<a href="'+url+'" class="list-group-item">'+d.computer_name+badge+'</a>');
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
		}
	});
});
</script>
