$(document).on('appReady ', function(e, lang) {

    $.getJSON( appUrl + '/module/machine/report/' + serialNumber, function( data ) {

        // Set properties based on class
        $.each(data, function(prop, val){
            $('.machine-'+prop).text(val);
        });

        // Convert computer_model to link to everymac.com TODO: make this optional/configurable
        var mmodel = $('.machine-machine_model').text();
        $('.machine-machine_model')
            .html($('<a target="_blank">')
                .attr('href', "https://www.everymac.com/ultimate-mac-lookup/?search_keywords="+mmodel)
                .text(mmodel)
            );

        // Set computer name value and title
		$('.mr-computer_name_input')
            .val(data.computer_name)
            .attr('title', data.computer_name)
            .data('placement', 'bottom')
            .tooltip();

        // Format OS Version
        $('.machine-os_version').text(mr.integerToVersion(data.os_version));

        // Uptime
        if(data.uptime > 0){
            var uptime = moment((data.timestamp - data.uptime) * 1000);
            $('.machine-uptime').html('<time title="'+i18n.t('boot_time')+': '+uptime.format('LLLL')+'">'+uptime.fromNow(true)+'</time>');
        }else{
            $('.machine-uptime').text(i18n.t('unavailable'));
        }
    });
});

$(document).on('appReady appUpdate', function(e, lang) {
    // Get reportdata
    $.getJSON( appUrl + '/module/reportdata/report/' + serialNumber, function( data ) {

        // Set properties based on class
        $.each(data, function(prop, val){
            $('.reportdata-'+prop).text(val);
        });
        
        // Registration date
        var msecs = moment(data.reg_timestamp * 1000);
        $('.reportdata-reg_date').html('<time title="'+msecs.format('LLLL')+'" >'+msecs.fromNow()+'</time>');

        // Check-in date
        var msecs = moment(data.timestamp * 1000);
        $('.reportdata-check-in_date').html('<time title="'+msecs.format('LLLL')+'" >'+msecs.fromNow()+'</time>');

        // Remote IP
        $('.reportdata-remote_ip').text(data.remote_ip);

        // Get machinegroup name
        $.getJSON(appUrl + '/unit/get_machine_groups', function( data ){
            var machine_group = parseInt($('.machine-machine_group').text())
            var name = data.find(x => parseInt(x.groupid) === machine_group).name;
            $('.machine-machine_group').text(name)
        })

        // Status
        var machineStatus = { '0': 'in_use', '1': 'archived'};
        $('.reportdata-archive_status').text(
            i18n.t('machine.status.' + machineStatus[$('.reportdata-archive_status').text()])
        );

    });
});
