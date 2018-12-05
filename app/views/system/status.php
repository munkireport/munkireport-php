<?php $this->view('partials/head'); ?>

<div class="container">

    <div class="row">

        <h3 class="col-lg-12" data-i18n="system.status"></h3>

    </div>

    <div class="row">

        <div id="mr-phpinfo" class="col-lg-6">

            <h4 data-i18n="php.php"></h4>

            <table class="table table-striped"><tr><td data-i18n="loading"></td></tr></table>

        </div>

        <div id="mr-db" class="col-lg-6">

            <h4 data-i18n="database"></h4>

            <table class="table table-striped"><tr><td data-i18n="loading"></td></tr></table>

        </div>
    </div>

</div>  <!-- /container -->

<script>
$(document).on('appReady', function(e, lang) {

    // Get database info
    $.getJSON( appUrl + '/system/DataBaseInfo', function( data ) {
        var table = $('#mr-db table').empty();
        for(var prop in data) {
            if(data[prop] === false){
                data[prop] = '<span class="label label-danger">No</span>';
            }
            if(data[prop] === true){
                data[prop] = '<span class="label label-success">Yes</span>';
            }
            if(prop == 'db.size' && data[prop]){
              data[prop] = parseFloat(data[prop]).toFixed(2) + ' MB'
            }

            table.append($('<tr>')
                .append($('<th>')
                    .html(i18n.t(prop)))
                .append($('<td>')
                    .html(data[prop])))
        }
    })
    .fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        $('#mr-db table tr td')
            .empty()
            .addClass('text-danger')
            .text(i18n.t('errors.loading', {error:err}));
    });

    // Get php info
    $.getJSON( appUrl + '/system/phpInfo', function( data ) {
        var table = $('#mr-phpinfo table').empty();

        //console.log(data);

        // Create php info dom structure
        var phpinfo = $('<table>').addClass('table table-striped');
        for(var section in data) {
            phpinfo
                .append($('<tr>')
                    .append($('<th>')
                        .attr('colspan', 2)
                        .addClass('info')
                        .append($('<h4>')
                            .text(section))))

            for(var sectiondata in data[section]){
                phpinfo.append($('<tr>')
                    .append($('<th>')
                        .text(sectiondata))
                    .append($('<td>')
                        .html(data[section][sectiondata])));
            }

        }

        // There is a difference between servers on how to find PHP version
        var phpVersion = data.Core ? data.Core['PHP Version'] : (data.phpinfo ? data.phpinfo[0] : 'Could not find version');

        // Get Core variables
        var coreVars = data.Core ? data.Core : (data['HTTP Headers Information'] ? data['HTTP Headers Information'] : {});

        // Create table with required php items
        var list = {
            'php.version': phpVersion,
            'php.dom': data.dom ? data.dom['DOM/XML'] : false || false,
            'php.soap': data.soap ? data.soap['Soap Client'] : false || false,
            'php.allow_url_fopen': coreVars['allow_url_fopen'] || false,
            'php.pdo': data.PDO ? data.PDO['PDO support'] : false || false,
            'php.pdodrivers': data.PDO ? data.PDO['PDO drivers'] : false || false
        };
        for(var prop in list) {

            if(list[prop] === false){
                list[prop] = '<span class="label label-danger">No</span>';
            }
            if(list[prop] === true){
                list[prop] = '<span class="label label-success">Yes</span>';
            }

            table.append($('<tr>')
                .append($('<th>')
                .html(i18n.t(prop)))
                .append($('<td>')
                    .html(list[prop])))

        }

        table.after($('<button>')
            .addClass('btn btn-info')
            .text(i18n.t('php.moreinfo'))
            .click(function(){
                // Create large modal
                $('#myModal .modal-dialog').addClass('modal-lg');
                $('#myModal .modal-title')
        			.empty()
        			.append(i18n.t("php.moreinfo"))
        		$('#myModal .modal-body')
        			.empty()
        			.append(phpinfo);

        		$('#myModal button.ok').text(i18n.t("dialog.close"));

        		// Set ok button
        		$('#myModal button.ok')
        			.off()
        			.click(function(){$('#myModal').modal('hide')});

                $('#myModal').modal('show');
            }))
    })
    .fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        $('#mr-phpinfo table tr td')
            .empty()
            .addClass('text-danger')
            .text(i18n.t('errors.loading', {error:err}));
    });
});
</script>


<?php $this->view('partials/foot'); ?>
