<?php $this->view('partials/head'); ?>

    <div class="container">
        <div class="row">
            <div id="mr-migrations" class="col-lg-12 loading">
                <h1><span id="database-update-count">(n/a)</span> <span data-i18n="database.migrations.pending">Database Update(s) Pending</span>
                </h1>
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
            <div id="database-upgrade-log" class="col-lg-12">
                <table class="table table-console">
                    <thead>
                    <tr>
                        <th colspan="1">
                            <a class="disclosure" href="#">
                                <span class="glyphicon glyphicon-chevron-right"></span> <span data-i18n="database.log">Upgrade Log</span>
                            </a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-i18n="database.loghelp">Nothing to show</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>  <!-- /container -->

    <script>


      $(document).on('appReady', function (e, lang) {
        var tbody = $('.table-console tbody');

        function log(message, level) {
          level = level || 'info';
          tbody.append('<tr><td class="log-level-' + level + '">' + message + '</td></tr>');
        }
        
        function disclose () {
          var logDiv = $('#database-upgrade-log');
          var disclosureEl = logDiv.find('.disclosure');
          var logTbl = logDiv.find('table');

          if (!disclosureEl.hasClass('disclosure-active')) {
            disclosureEl.addClass('disclosure-active');
          }

          if (!logTbl.hasClass('disclosure-active')) {
            logTbl.addClass('disclosure-active');
          }
        }

        // Show/Hide the upgrade log
        $('.disclosure').click(function () {
          $(this).toggleClass('disclosure-active');
          $(this).closest('table').toggleClass('disclosure-active');
        });

        $('#db-upgrade').click(function (e) {
          $(this).attr('disabled', true);
          $(this).find('#db-upgrade-label').html('Upgrading&hellip;');
          var $btn = $(this);

          function done () {
            $btn.attr('disabled', false);
            $btn.find('#db-upgrade-label').html('Update');
          }

          log('started update: ' + new Date());

          $.getJSON(appUrl + '/database/migrate', function (data) {
            done();

            if (data.error) {
              disclose();
              log(data.error_message, 'error');

              if (data.error_trace) {
                log('stack trace follows:', 'error');
                data.error_trace.forEach(function(stackItem) {
                  var li = $('<li>in ' + stackItem.file + ':' + stackItem.line + '  ' + stackItem.class + stackItem.type + stackItem.function + '</li>');
                    log('in ' + stackItem.file + ':' + stackItem.line + '  ' + stackItem.class + stackItem.type + stackItem.function + '.' , 'error');
                });
              }
            }

            if (data.notes) {
              tbody.empty();

              for (var i = 0; i < data.notes.length; i++) {
                tbody.append($('<tr><td>' + data.notes[i] + '</td></tr>')); // .text(data.notes[i])
              }
            }
        }).fail(function (jqXHR, textStatus, error) {
          done();
        })
      });

      $.getJSON(appUrl + '/database/migrationsPending', function (data) {
        var tbody = $('.table-console tbody').empty();
        $('.loading').removeClass('loading');

        if (data.error) {
          disclose();
          log(data.error_message, 'error');
        }

        $('#database-update-count').text(data['files_pending'].length);

//        if (data['files_pending'].length) {
//          for (var i = 0; i < data['files_pending'].length; i++) {
//            tbody.append('<tr><td>' + data['files_pending'][i] + '</td></tr>');
//          }
//        }
      })
        .fail(function (jqxhr, textStatus, error) {
          var err = textStatus + ", " + error;
          $('#mr-db table tr td')
            .empty()
            .addClass('text-danger')
            .text(i18n.t('errors.loading', {error: err}));
        });
      })
      ;
    </script>
<?php
$this->view('partials/foot');