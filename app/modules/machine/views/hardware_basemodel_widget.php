<div class="col-lg-4 col-md-6">

<div class="panel panel-default" id="hardware-basemodel-widget" onclick="location.href=appUrl+'/show/listing/machine/hardware/'">

    <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-laptop"></i> <span data-i18n="machine.base_model_widget_title"></span></h3>

    </div>

    <div class="panel-body">
        
        <svg style="width:100%"></svg>
        
    </div>

</div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appReady', function(e, lang) {
	
	var conf = {
		url: appUrl + '/module/machine/get_model_stats/summary', // Url for json
		widget: 'hardware-basemodel-widget', // Widget id
        margin: {top: 20, right: 10, bottom: 20, left: 150}, // Adjust margins
	};

	mr.addGraph(conf);

});

</script>

