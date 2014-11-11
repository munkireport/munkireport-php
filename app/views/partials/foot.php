  <div class="container">

    <div style="text-align: right; margin: 10px; color: #bbb; font-size: 80%;">

      <i>MunkiReport <span data-i18n="version">Version</span> <?=$GLOBALS['version']?></i>

    </div>

  </div>

  <?foreach($GLOBALS['alerts'] AS $type => $list):?>

  <div class="mr-alert alert alert-dismissable alert-<?=$type?>">

    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

    <ul>

    <?foreach ($list AS $msg):?>

      <li><?=$msg?></li>

    <?endforeach?>

    </ul>

  </div>

  <?endforeach?>

  <script>
    $('.mr-alert').prependTo('body>div.container:first');
	var munkireport = { debug: <?=conf('debug') ? 'true' : 'false'?>, subdirectory: "<?=conf('subdirectory')?>" }
  </script>


  <script src="<?=conf('subdirectory')?>assets/js/bootstrap.min.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/jquery.dataTables.min.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/datatables.bootstrap.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/moment.min.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/flotr2.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/i18next.min.js"></script>
  <script>


  </script>
  <?if(conf('custom_js')):?> 
  <script src="<?=conf('custom_js')?>"></script>
  <?endif?>

  <script src="<?=conf('subdirectory')?>assets/js/munkireport.js"></script>

</body>
</html>