<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="filesystem-type-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-hdd-o"></i>
			    <span data-i18n="disk_report.filesystem_type"></span>
			    <list-link data-url="/show/listing/disk_report/disk"></list-link>
			</h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/disk_report/get_volume_type', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#filesystem-type-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/disk_report/disk';
		panel.empty();
		
		// Set types
		if(data.hfs != "0"){
			panel.append(' <a href="'+baseUrl+'#hfs" class="btn btn-info"><span class="bigger-150">'+data.hfs+'</span><br>&nbsp;&nbsp;&nbsp;HFS+&nbsp;&nbsp;</a>');
		}
		if(data.apfs != "0"){
			panel.append(' <a href="'+baseUrl+'#apfs" class="btn btn-info"><span class="bigger-150">'+data.apfs+'</span><br>&nbsp;&nbsp;APFS&nbsp;&nbsp;</a>');
		}
		if(data.bootcamp != "0"){
			panel.append(' <a href="'+baseUrl+'#bootcamp" class="btn btn-info"><span class="bigger-150">'+data.bootcamp+'</span><br>'+i18n.t('disk_report.bootcamp')+'</a>');
		}
    });
});
</script>
