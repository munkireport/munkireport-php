<<<<<<< HEAD
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="certificate-expiration-widget">
        <div class="panel-heading" data-container="body" title="">
            <h3 class="panel-title"><i class="fa fa-certificate"></i> <span data-i18n="certificate.title"></span></h3>
        </div>
		<div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->
=======
        <div class="col-lg-4 col-md-6">

            <div class="panel panel-default">

                <div class="panel-heading" data-container="body" title="">

                    <h3 class="panel-title"><i class="fa fa-certificate"></i>
                        <span data-i18n="certificate.title"></span>
                        <list-link data-url="show/listing/certificate/certificate"></list-link>
                    </h3>

                </div>

                <div class="list-group scroll-box">
                    <a id="cert-ok" href="<?=url('show/listing/certificate/certificate')?>" class="list-group-item list-group-item-success hide">
                        <span class="badge">0</span>
                        <span data-i18n="certificate.ok"></span>
                    </a>
                    <a id="cert-soon" href="<?=url('show/listing/certificate/certificate')?>" class="list-group-item list-group-item-warning hide">
                        <span class="badge">0</span>
                        <span data-i18n="certificate.soon"></span>
                    </a>
                    <a id="cert-expired" href="<?=url('show/listing/certificate/certificate')?>" class="list-group-item list-group-item-danger hide">
                        <span class="badge">0</span>
                        <span data-i18n="certificate.expired"></span>
                    </a>
                    <span id="cert-nodata" data-i18n="no_clients" class="list-group-item"></span>
                </div>

            </div><!-- /panel -->

        </div><!-- /col -->
>>>>>>> origin/widget-rewrites3

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/certificate/get_stats', function( data ) {
        
    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#certificate-expiration-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/certificate/certificate';
		panel.empty();

		// Set statuses
        if(data.expired){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data.expired+'</span><br>'+i18n.t('certificate.expired')+'</a>');
		}
		if(data.soon){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-warning"><span class="bigger-150">'+data.soon+'</span><br>'+i18n.t('certificate.soon')+'</a>');
		}
		if(data.ok){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.ok+'</span><br>'+i18n.t('certificate.ok')+'</a>');
		}
    });
});
</script>