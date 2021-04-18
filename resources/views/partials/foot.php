<?php if(Auth::check()):?>

  <div class="container">

    <div style="text-align: right; margin: 10px; color: #bbb; font-size: 80%;">

      <i>MunkiReport <span data-i18n="version">Version</span> <?php echo $GLOBALS['version']; ?></i>

    </div>

  </div>
  
<?php endif?>

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"></h4>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button data-i18n="dialog.cancel" type="button" class="btn btn-default" data-dismiss="modal"></button>
          <button type="button" class="btn btn-primary ok"></button>
        </div>
      </div>
    </div>
  </div>

  <?php foreach($GLOBALS['alerts'] AS $type => $list): ?>

  <div class="mr-alert alert alert-dismissable alert-<?php echo $type; ?>">

    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

    <ul>

    <?php foreach ($list AS $msg): ?>

      <li><?php echo $msg; ?></li>

    <?php endforeach; ?>

    </ul>

  </div>

  <?php endforeach; ?>

<?php if (config('frontend.javascript.use_cdn', false)): ?>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.js"></script>
    <!--    <script src="https://unpkg.com/i18next/dist/umd/i18next.min.js"></script>-->
<?php else: ?>
    <script src="<?php echo asset('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/datatables.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/datatables.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/jszip.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/buttons.bootstrap4.min.js'); ?>"></script>
<?php endif ?>

    <!-- i18next, v1.10.2. Newest does not work -->
    <script src="<?php echo asset('assets/js/i18next.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/moment.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/d3/d3.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/nv.d3.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/jquery.hotkeys/jquery.hotkeys.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/munkireport.settings.js'); ?>"></script>

    <!-- inline scripts -->
    <script>
      $('.mr-alert').prependTo('body>div.container:first');
    </script>
    <script>
    // Inject debug value from php
    mr.debug = <?php echo config('app.debug') ? 'true' : 'false'; ?>;
    <?php $dashboard = getDashboard()->loadAll();?>
    <?php foreach($dashboard->getDropdownData('show/dashboard', $page) as $item): ?>
      <?php if($item->hotkey):?>

        mr.setHotKey('<?php echo $item->hotkey?>', appUrl + '/show/dashboard/<?php echo $item->name?>');

      <?php endif?>
    <?php endforeach?>
    </script>

    <!-- munkireport.custom_js -->
    <?php if(config('_munkireport.custom_js')): ?>
        <script src="<?php echo config('_munkireport.custom_js'); ?>"></script>
    <?php endif; ?>

    <script src="<?php echo asset('assets/js/munkireport.js'); ?>"></script>
  
    <?php if(isset($recaptcha) && conf('recaptchaloginpublickey')):?>
      <script src='https://www.google.com/recaptcha/api.js' async defer></script>
      <script>
          function onSubmit(token) {
            document.getElementById("login-form").submit();
          }
      </script>
    <?php endif?>

    <script>
      // Include csrf in all requests
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>
    <script>
    $(document).on('appUpdate', function(){
      //$.getJSON( appUrl + '/module/notification/runCheck', function( data ) {
          // Maybe add some counter to only run every 10 mins.
          // CHeck if the data contains errors
          // Check if there are desktop notifications
      //});
    });
    </script>
</body>
</html>
