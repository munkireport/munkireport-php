	<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="certificate-group-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-certificate"></i>
    			<span data-i18n="certificate.cert_groups"></span>
    			<list-link data-url="/show/listing/certificate/certificate"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#certificate-group-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/certificate/get_certificates', function( data ) {
		
		box.empty();
		if(data.length){
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/certificate/certificate/#'+d.cert_cn+'" class="list-group-item">'+d.cert_cn+badge+'</a>')
			});
		}
		else{
			box.append('<span class="list-group-item">'+i18n.t('certificate.nocertificate')+'</span>');
		}
	});
});	
</script>

