<h2>macOSLAPS <button data-i18n="laps.showpassword" class="btn btn-default btn-xs hide" id="laps_show_button"></button></h2>

<div id="laps-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<div id="laps-view" class="row hide">
    <div class="col-md-3">
        <table class="table table-striped">
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
        </table>
    </div>
    <div class="col-md-6">
    </div>
</div>

<script>
$(document).on('appReady', function(e, lang) {

	// Get laps data
	$.getJSON( appUrl + '/module/laps/get_data/' + serialNumber, function( data ) {
        
        if (data.length != 0){
            // Hide loading and show button
            $('#laps-msg').text('');
            $('#laps-view').removeClass('hide');
            $('#laps_show_button').removeClass('hide');
            
            // Add strings
            $('#laps-useraccount').text(data.useraccount);
            $('#laps-password').text(data.password);

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
        // Disable button and unhide password field
        $('#laps-password').removeClass('hide');
        $('#laps_show_button').addClass('disabled');
    })
    
});
    
</script>
