  <div class="container">

    <div style="text-align: right; margin: 10px; color: #bbb; font-size: 80%;">

      <i>MunkiReport <span data-i18n="version">Version</span> <?php echo $GLOBALS['version']; ?></i>

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
	var munkireport = { debug: <?php echo conf('debug') ? 'true' : 'false'; ?>, subdirectory: "<?php echo conf('subdirectory'); ?>" }
  </script>


  <script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/datatables.bootstrap.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/moment.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/flotr2.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/i18next.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/d3.v3.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/nv.d3.min.js"></script>
  <script>


  </script>
  <?php if(conf('custom_js')): ?> 
  <script src="<?php echo conf('custom_js'); ?>"></script>
  <?php endif; ?>

  <script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.js"></script>

</body>
</html>