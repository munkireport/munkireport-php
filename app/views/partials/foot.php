  <div class="container">

    <div style="text-align: right; margin: 10px; color: #bbb; font-size: 80%;">

      <i>MunkiReport <span data-i18n="version">Version</span> <?php echo $GLOBALS['version']; ?></i>

    </div>

  </div>

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

  <script>
    $('.mr-alert').prependTo('body>div.container:first');
  </script>


  <script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/datatables.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/moment.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/i18next.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/d3/d3.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/nv.d3.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/jquery.hotkeys/jquery.hotkeys.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.settings.js"></script>

  <script>
    // Inject debug value from php
    mr.debug = <?php echo conf('debug') ? 'true' : 'false'; ?>;
    <?php $dashboard = getDashboard()->loadAll();?>
    <?php foreach($dashboard->getDropdownData('show/dashboard', $page) as $item): ?>
      <?php if($item->hotkey):?>
      
    mr.setHotKey('<?php echo $item->hotkey?>', appUrl + '/show/dashboard/<?php echo $item->name?>');
    
      <?php endif?>
    <?php endforeach?>
  </script>
  

  <?php if(conf('custom_js')): ?> 
  <script src="<?php echo conf('custom_js'); ?>"></script>
  <?php endif; ?>

  <script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.js"></script>
  
  <?php if(isset($recaptcha) && conf('recaptchaloginpublickey')):?>
      <script src='https://www.google.com/recaptcha/api.js' async defer></script>
      <script>
          function onSubmit(token) {
            document.getElementById("login-form").submit();
          }
      </script>
  <?php endif?>
  
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