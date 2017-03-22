<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="time-machine-status-widget">
        <div class="panel-heading" data-container="body" title="">
            <h3 class="panel-title"><i class="fa fa-clock-o"></i> <span data-i18n="timemachine.timemachinewidget"></span> <span class="counter badge pull-right"></span></h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/timemachine/get_stats', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#time-machine-status-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/timemachine/timemachine';
		panel.empty();
		
		// Set statuses
		if(data.today){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.today+'</span><br>'+i18n.t('timemachine.today')+'</a>');
		}
		if(data.lastweek){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-warning"><span class="bigger-150">'+data.lastweek+'</span><br>'+i18n.t('timemachine.lastweek')+'</a>');
		}
		if(data.week_plus){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data.week_plus+'</span><br>'+i18n.t('timemachine.week_plus')+'</a>');
		}
        
        $('#time-machine-status-widget .counter').html(data.total);
    });
});
</script>
