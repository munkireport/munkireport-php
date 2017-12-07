<?php $this->view('partials/head'); ?>

<div class="container">
    <div class="row">
        <div id="mr-migrations" class="col-lg-12">
            <h1><span id="database-update-count">(n/a)</span> <span data-i18n="database.migrations.pending">Database Update(s) Pending</span></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <button id="db-upgrade" class="btn btn-primary">
                <span id="db-upgrade-label" data-i18n="database.update">Update</span>
            </button>
        </div>
    </div>
    <div class="row">
        <div id="database-upgrade-log" class="col-lg-6">
            <a class="disclosure" href="#">
                <span class="glyphicon glyphicon-chevron-right"></span> <span data-i18n="database.log">Upgrade Log</span>
            </a>

            <table class="table table-console">
                <tr><td data-i18n="database.loghelp">Nothing to show</td></tr>
            </table>
        </div>
    </div>

</div>  <!-- /container -->

<script>
    $(document).on('appReady', function(e, lang) {

        // Show/Hide the upgrade log
        $('.disclosure').click(function() {
            $(this).toggleClass('disclosure-active');
            $(this.parentNode).toggleClass('disclosure-active');
        });

        $('#db-upgrade').click(function(e) {
            $(this).attr('disabled', true);
            $(this).find('#db-upgrade-label').html('Upgrading&hellip;');
            var $btn = $(this);

            function done() {
                $btn.attr('disabled', false);
                $btn.find('#db-upgrade-label').html('Upgrade now');
            }

            $.getJSON(appUrl + '/system/migrate', function(data) {
                done();
                var table = $('.table-console').empty();

                if (data.error) {


                    table.append($('<tr><td class="log-error">' + data.error + '</td></tr>'));
                } else {
                    if (data.notes) {
                        var table = $('.table-console').empty();

                        for (var i = 0; i < data.notes.length; i++) {
                            table.append($('<tr><td>' + data.notes[i] + '</td></tr>')); // .text(data.notes[i])
                        }
                    }
                }


            }).fail(function(jqXHR, textStatus, error) {
                done();
            })
        });
        
        $.getJSON(appUrl + '/system/migrationsPending', function( data ) {
            var table = $('#mr-migrations table').empty();

            if (data.error) {
                  
            } else {

            }

            $('#database-update-count').text(data['files_pending'].length);

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