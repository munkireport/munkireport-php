<h2 data-i18n="certificate.title"></h2>


<table id="cert-table" class="table table-condensed table-striped">
    <thead>
        <tr>
            <th data-i18n="certificate.commonname"></th>
            <th data-i18n="certificate.expires"></th>
            <th data-i18n="certificate.expiration_date"></th>
            <th data-i18n="certificate.issuer"></th>
            <th data-i18n="certificate.location"></th>
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
    $.getJSON( appUrl + '/module/certificate/get_data/' + serialNumber, function( data ) {
        if(data.length)
        {
            var tbl = $('#cert-table tbody');

            tbl.empty();

            // Load data
            $.each(data, function(index, cert){
                tbl.append($('<tr>')
                    .attr('title', cert.rs.cert_path)
                    .append($('<td>')
                        .text(cert.rs.cert_cn))
                    .append($('<td>')
                        .html(function(){
                            var date = new Date(cert.rs.cert_exp_time * 1000);
                            var diff = moment().diff(date, 'days');
                            var cls = diff > 0 ? 'danger' : (diff > -90 ? 'warning' : 'success');
                            return('<span class="label label-'+cls+'">'+moment(date).fromNow()+'</span>')
                            }))
                    .append($('<td>')
                        .html(function(){
                            var date = new Date(cert.rs.cert_exp_time * 1000);
                            return moment(date).format('LLLL');
                            }))
                    .append($('<td>')
                        .text(cert.rs.issuer))                        
                    .append($('<td>')
                        .text(cert.rs.cert_location)));   
            });

            // Add tooltips
            $('tr[title]').tooltip();

            // Set correct tab on location hash
            loadHash();

        }
    });
});

</script>
