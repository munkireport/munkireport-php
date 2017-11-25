<?php $this->view('partials/head'); ?>

<div class="container">
    <div class="row">
        <h3 class="col-lg-12" data-i18n="system.database.migrations">Database Migrations</h3>
    </div>
    <div class="row">

        <div id="mr-migrations" class="col-lg-12">
            <h4 data-i18n="database.migrations.pending">Migrations Pending</h4>

            <table class="table table-striped"><tr><td data-i18n="loading"></td></tr></table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-default">Run pending migrations</button>
            <button class="btn btn-alert">Rollback database</button>
        </div>
    </div>
</div>  <!-- /container -->

<script>
    $(document).on('appReady', function(e, lang) {
        $.getJSON(appUrl + '/system/migrationsPending', function( data ) {
            var table = $('#mr-migrations table').empty();

            if (data.hasOwnProperty('files_pending')) {
                for (var i = 0; i < data['files_pending'].length; i++) {
                    table.append($('<tr><td></td></tr>').text(data['files_pending'][i]));
                }
            }
        })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                $('#mr-db table tr td')
                    .empty()
                    .addClass('text-danger')
                    .text(i18n.t('errors.loading', {error:err}));
            });
    });
</script>
<?php
$this->view('partials/foot');