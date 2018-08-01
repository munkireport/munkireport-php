<h2>macOSLAPS <button data-i18n="laps.showpassword" class="btn btn-default btn-xs hide" id="laps_show_button"></button></h2>

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
                <th data-i18n="laps.password"></th>
                <td id="laps-password" class="hide"></td>
            </tr>
            <tr>
                <th data-i18n="laps.days_till_expiration"></th>
                <td id="laps-days_till_expiration"></td>
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
                <th data-i18n="laps.pass_length"></th>
                <td id="laps-pass_length"></td>
            </tr>
            <tr>
                <th data-i18n="laps.remote_management"></th>
                <td id="laps-remote_management"></td>
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
            $('#laps_show_button').removeClass('hide');
            
            // Add strings
            $('#laps-useraccount').text(data.useraccount);
            $('#laps-password').text(data.password);
            $('#laps-pass_length').text(data.pass_length);
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
                $('#laps-dateset').html('<span title="'+moment((data.dateset*1000)).fromNow()+'">'+moment((data.dateset*1000)).format('llll')+'</span>');
            }
            if (data.dateexpires){
                $('#laps-dateexpires').html('<span title="'+moment((data.dateexpires*1000)).fromNow()+'">'+moment((data.dateexpires*1000)).format('llll')+'</span>');
            }
        } else {
            $('#laps-msg').html(i18n.t('laps.nodata'));
        }
	});
    
    $('#laps_show_button').click(function (e) {
        // Hide button and unhide password field
        $('#laps-password').removeClass('hide');
        $('#laps_show_button').addClass('hide');
    })
    
});
    
</script>
