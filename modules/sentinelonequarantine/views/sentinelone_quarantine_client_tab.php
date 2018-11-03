<h2 data-i18n="sentinelonequarantine.sentinelone_quarantine"></h2>


<table id="sentinelonequarantine-table" class="table table-condensed table-striped">
    <thead>
        <tr>
            <th data-i18n="sentinelonequarantine.path"></th>
            <th data-i18n="sentinelonequarantine.uuid"></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2" data-i18n="listing.loading"></td>
        </tr>
    </tbody>
</table>

<script>


$(document).on('appReady', function(e, lang) {

    // Get certificate data
    $.getJSON( appUrl + '/module/sentinelonequarantine/get_data/' + serialNumber, function( data ) {
        // Set count of quarantined files
        $('#sentinelonequarantine-cnt').text(data.length);
        if(data.length)
        {
            var tbl = $('#sentinelonequarantine-table tbody');

            tbl.empty();

            // Load data
            $.each(data, function(index, sentinelonequarantine){
                tbl.append($('<tr>')
                    .attr('title', sentinelonequarantine.rs.path)
                    .append($('<td>')
                        .text(sentinelonequarantine.rs.path))
                    .append($('<td>')
                        .text(sentinelonequarantine.rs.uuid)));                      
            });


            // Set correct tab on location hash
            loadHash();

        }
    });
});

</script>
