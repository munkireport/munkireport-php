<?php $this->view('partials/head'); ?>

    <div class="container">
        <div class="row">
            <div id="mr-migrations" class="col-lg-12 loading">
                <h1>Upgrade Database</h1>
                <h3>Click the update button to begin database upgrade.</h3>
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

        function log (message, level) {
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

          tbody.empty();
          log('Started update: ' + new Date());

          $.getJSON(appUrl + '/database/migrate', function (data) {
            done();

            if (data.notes) {
              for (var i = 0; i < data.notes.length; i++) {
                tbody.append($('<tr><td>' + data.notes[i] + '</td></tr>')); // .text(data.notes[i])
              }
            }

            if (data.notes) {
              for (var i = 0; i < data.notes.length; i++) {
                tbody.append($('<tr><td>' + data.notes[i] + '</td></tr>')); // .text(data.notes[i])
              }
            }

            if (data.error) {
              disclose();
              log(data.error, 'error');

              if (data.error_trace) {
                log('stack trace follows:', 'error');
                data.error_trace.forEach(function (stackItem) {
                  log('in ' + stackItem.file + ':' + stackItem.line + '  ' + stackItem.class + stackItem.type + stackItem.function + '.', 'error');
                });
              }
            }

          }).fail(function (jqXHR, textStatus, error) {
            log(error, 'error');
            done();
          })
        });
      });
    </script>
<?php
$this->view('partials/foot');