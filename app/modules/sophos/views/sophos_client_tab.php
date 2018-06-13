<h2 data-i18n="sophos.sophos"></h2>

<div id="sophos-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<div id="sophos-view" class="row hide">
    <div class="col-md-5">
        <table class="table table-striped">
        <tr>
            <th data-i18n="sophos.install-status" class="mr-install-status"></th>
            <td id="sophos-install-status"></td>
        </tr>
        <tr>
            <th data-i18n="sophos.running-label" class="mr-running-status"></th>
            <td id="sophos-running-status"></td>
        </tr>
        <tr>
            <th data-i18n="sophos.product-version" class="mr-product-version"></th>
            <td id="sophos-product-version"></td>
        </tr>
        <tr>
            <th data-i18n="sophos.engine-version" class="mr-engine-version"></th>
            <td id="sophos-engine-version"></td>
        </tr>
        <tr>
            <th data-i18n="sophos.virus-data-version" class="mr-virus-data-version"></th>
            <td id="sophos-virus-data-version"></td>
        </tr>
        <tr>
            <th data-i18n="sophos.ui-version" class="mr-ui-version"></th>
            <td id="sophos-ui-version"></td>
        </tr>
        </table>
    </div>
    <div class="col-md-6">
    </div>
</div>

<script>
$(document).on('appReady', function(e, lang) {

    // Get data
    $.getJSON( appUrl + '/module/sophos/get_data/' + serialNumber, function( data ) {

            // Hide loading msg
            $('#sophos-msg').text('');
            $('#sophos-view').removeClass('hide');

            // Add strings
            $('#sophos-install-status').text(data.installed);
            $('#sophos-running-status').text(data.running);
            $('#sophos-product-version').text(data.product_version);
            $('#sophos-engine-version').text(data.engine_version);
            $('#sophos-virus-data-version').text(data.virus_data_version);
            $('#sophos-ui-version').text(data.user_interface_version);

            // Set running status
            if(data.running == "1") {
                 $('#sophos-running-status').text(i18n.t('sophos.running-status'));
            } else if(data.running == "0") {
                 $('#sophos-running-status').text(i18n.t('sophos.not-running-status'));
            } else{
                 $('sophos-running-status').text("");
            }
    });

});

</script>
