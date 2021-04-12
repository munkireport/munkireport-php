<div class="col-lg-4">
    <div class="row">
        <div class="col-xs-6">
            <img id="apple_hardware_icon" class="img-responsive">
        </div>
        <div class="col-xs-6" style="font-size: 1.4em; overflow: hidden">
            <span class="label label-info">macOS <span class="machine-os_version"></span></span><br>
            <span class="label label-info"><span class="machine-physical_memory"></span> GB</span><br>
            <span class="label label-info"><span class="machine-serial_number"></span></span><br>
            <span class="label label-info"><span class="reportdata-remote_ip"></span></span><br>
        </div>
    </div>
    <span class="machine-machine_desc"></span> <a class="machine-refresh-desc" href=""><i class="fa fa-refresh"></i></a>
</div>

<script>

    var apple_hardware_icon_url = "<?=conf('apple_hardware_icon_url')?>";
    $('#apple_hardware_icon')
        .attr('src', apple_hardware_icon_url.replace('%s&amp;', serialNumber.substring(8) + '&' ))
    
    // ------------------------------------ Refresh machine description
	$('.machine-refresh-desc')
        .attr('href', appUrl + '/module/machine/model_lookup/' + serialNumber)
        .click(function(e){
            e.preventDefault();
            // show that we're doing a lookup
            $('.machine-machine_desc').text(i18n.t('loading'));
            $.getJSON( appUrl + '/module/machine/model_lookup/' + serialNumber, function( data ) {
                if(data['error'] == ''){
                    $('.machine-machine_desc').text(data['model']);
                }
                else{
                    $('.machine-machine_desc').text(data['error']);
                }
            });
        });
</script>