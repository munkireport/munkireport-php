<h2>macOSLAPS</h2>

<div id="laps-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<div id="laps-view" class="row hide">
    <div class="col-md-3">
        <table class="table table-striped">
            <tr>
                <th data-i18n="laps.script_enabled"></th>
                <td id="laps-script_enabled"></td>
            </tr>
            <tr>
                <th data-i18n="laps.useraccount"></th>
                <td id="laps-useraccount"></td>
            </tr>
            <tr>
                <th data-i18n="laps.dateset"></th>
                <td id="laps-dateset"></td>
            </tr>
            <tr>
                <th data-i18n="laps.dateexpires"></th>
                <td id="laps-dateexpires"></td>
            </tr>
            <tr>
                <th data-i18n="laps.days_till_expiration"></th>
                <td id="laps-days_till_expiration"></td>
            </tr>
            <tr>
                <th data-i18n="laps.pass_length"></th>
                <td id="laps-pass_length"></td>
            </tr>
            <tr>
                <th data-i18n="laps.alpha_numeric_only"></th>
                <td id="laps-alpha_numeric_only"></td>
            </tr>
            <tr>
                <th data-i18n="laps.keychain_remove"></th>
                <td id="laps-keychain_remove"></td>
            </tr>
            <tr>
                <th data-i18n="laps.remote_management"></th>
                <td id="laps-remote_management"></td>
            </tr>
            <tr id="password-row" class="hide">
                <th data-i18n="laps.password"></th>
                <td id="laps-password"><button data-i18n="laps.show_password" class="btn btn-default btn-xs" id="laps_show_button" style="padding-top: 0px;padding-bottom: 0px;"></button></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
    </div>
</div>

<script>
$(document).on('appReady', function(e, lang) {

	// Get laps data
	$.getJSON( appUrl + '/module/laps/get_data/' + serialNumber, function( data ) {    
        if (data.id !== 0){
            // Hide loading and show button
            $('#laps-msg').text('');
            $('#laps-view').removeClass('hide');
        
            // Only show the button if password decryption is enabled and if user is authorized
            if ("<?php echo conf('laps_password_decrypt_enabled'); ?>" == true && data.password_view === 1) {
                $('#password-row').removeClass('hide');
            }
            
            // Add strings
            $('#laps-useraccount').text(data.useraccount);
            $('#laps-pass_length').text(data.pass_length+" "+i18n.t('laps.characters'));
            $('#laps-days_till_expiration').text(data.days_till_expiration+" "+i18n.t('date.day_plural'));
            
            // Format booleans
			if(data.script_enabled === "1" || data.script_enabled === 1) {
				 $('#laps-script_enabled').text(i18n.t('yes'));
			} else if(data.script_enabled === "0" || data.script_enabled === 0) {
				 $('#laps-script_enabled').text(i18n.t('no'));
			} else{
				 $('#laps-script_enabled').text("");
			}
            
            if(data.alpha_numeric_only === "1" || data.alpha_numeric_only === 1) {
				 $('#laps-alpha_numeric_only').text(i18n.t('yes'));
			} else if(data.alpha_numeric_only === "0" || data.alpha_numeric_only === 0) {
				 $('#laps-alpha_numeric_only').text(i18n.t('no'));
			} else{
				 $('#laps-alpha_numeric_only').text("");
			}
                    
            
            if(data.keychain_remove === "1" || data.keychain_remove === 1) {
				 $('#laps-keychain_remove').text(i18n.t('yes'));
			} else if(data.keychain_remove === "0" || data.keychain_remove === 0) {
				 $('#laps-keychain_remove').text(i18n.t('no'));
			} else{
				 $('#laps-keychain_remove').text("");
			}
            
            if(data.remote_management === "1" || data.remote_management === 1) {
				 $('#laps-remote_management').text(i18n.t('enabled'));
			} else if(data.remote_management === "0" || data.remote_management === 0) {
				 $('#laps-remote_management').text(i18n.t('disabled'));
			} else{
				 $('#laps-remote_management').text("");
			}
            
            // Format dates
            if (data.dateset){
                $('#laps-dateset').html('<time title="'+moment((data.dateset*1000)).fromNow()+'" style="cursor: pointer;">'+moment((data.dateset*1000)).format('llll')+'</time>');
            }
            if (data.dateexpires){
                $('#laps-dateexpires').html('<time title="'+moment((data.dateexpires*1000)).fromNow()+'" style="cursor: pointer;">'+moment((data.dateexpires*1000)).format('llll')+'</time>');
            }
        } else {
            $('#laps-msg').html(i18n.t('laps.nodata'));
        }
	});
    
    $('#laps_show_button').click(function (e) {        
        // Disable get password button
        $('#laps_show_button').addClass('hide disable');
        $.getJSON(appUrl + '/module/laps/get_audit/'+serialNumber, function (processdata) {

            var timestamp = Math.round((new Date()).getTime() / 1000);
            var username="<?php echo $_SESSION['user']; ?>";
            var remote_ip = "<?php echo getRemoteAddress(); ?>";
            var user_agent = navigator.userAgent;

            // If not already an audit trail, make one
            if (processdata.audit == null){
                // Make first audit marker and turn into JSON
                var send_info = JSON.stringify([{"timestamp":timestamp,"username":username,"remote_ip":remote_ip,"user_agent":user_agent,"action":"show_password"}]);
            } else {
                // Get previous audit markers
                var stored_info = JSON.parse(processdata.audit);
                // Add latest audit marker
                stored_info.push({"timestamp":timestamp,"username":username,"remote_ip":remote_ip,"user_agent":user_agent,"action":"show_password"});
                // Turn audit trail into JSON
                var send_info = JSON.stringify(stored_info);
            }
            
            // Post to audit trail
            xhr = new XMLHttpRequest();
            var url = appUrl + '/module/laps/save_audit/'+serialNumber;
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-type", "application/json");
            xhr.onreadystatechange = function () { 
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Get password in JSON format
                    $.getJSON(appUrl + '/module/laps/get_password/'+serialNumber, function (processdata) {
                        // Only after action has been recorded is the password filled in and unhidden
                        $('#laps-password').text(processdata.password);
//                        $('#laps-password-row').removeClass('hide');
                    })
//                } else {
//                    // On error
//                    $('#laps-password').text(i18n.t('laps.password_error'));
//                    $('#laps-password-row').removeClass('hide');
                }
            }
            xhr.send(send_info);
        })
    });
});
    
</script>
