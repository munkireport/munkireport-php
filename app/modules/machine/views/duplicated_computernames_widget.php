<div class="col-lg-4">

	<div class="panel panel-default" id="duplicated-computernames-widget">

		<div class="panel-heading" data-container="body">

			<h3 class="panel-title"><i class="fa fa-bug"></i> <span data-i18n="widget.duplicate_computernames.title"></span></h3>

		</div>

		<div class="list-group scroll-box"></div>

	</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

	var box = $('#duplicated-computernames-widget div.scroll-box');

	$.getJSON( appUrl + '/module/machine/get_duplicate_computernames', function( data ) {

		box.empty();

		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
				if( ! d.computer_name){
					box.append('<a class="list-group-item">'+i18n.t('empty')+badge+'</a>')
				}
				else{
					box.append('<a href="'+appUrl+'/show/listing/reportdata/clients/#'+d.computer_name+'" class="list-group-item">'+d.computer_name+badge+'</a>')
				}
			})
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('widget.duplicate_computernames.notfound')+'</span>');
		}
	});
});
</script>
