<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="disk-type-widget">
		<div class="panel-heading" data-container="body">
			<h3 class="panel-title"><i class="fa fa-hdd-o"></i>  <span data-i18n="disk_report.volume_type"></span></h3>
		</div>
		<div class="panel-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/disk_report/get_disk_type', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#disk-type-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/disk/disk';
		panel.empty();
		
		// Set types
		if(data.ssd){
			panel.append(' <a href="'+baseUrl+'#ssd" class="btn btn-success"><span class="bigger-150">'+data.ssd+'</span> <br>&nbsp;&nbsp;'+i18n.t('disk_report.ssd')+'&nbsp;&nbsp;</a>');
		}
		if(data.fusion){
			panel.append(' <a href="'+baseUrl+'#fusion" class="btn btn-info"><span class="bigger-150">'+data.fusion+'</span><br>'+i18n.t('disk_report.fusion')+'</a>');
		}
		if(data.hdd){
			panel.append(' <a href="'+baseUrl+'#hdd" class="btn btn-info"><span class="bigger-150">'+data.hdd+'</span><br>&nbsp;&nbsp;'+i18n.t('disk_report.hdd')+'&nbsp;&nbsp;</a>');
		}
		if(data.raid){
			panel.append(' <a href="'+baseUrl+'#raid" class="btn btn-info"><span class="bigger-150">'+data.raid+'</span><br>&nbsp;'+i18n.t('disk_report.raid')+'&nbsp;</a>');
		}
//		if(data.bootcamp){
//			panel.append(' <a href="'+baseUrl+'#bootcamp" class="btn btn-info"><span class="bigger-150">'+data.bootcamp+'</span><br>'+i18n.t('disk_report.bootcamp')+'</a>');
//		}
    });
});
</script>
