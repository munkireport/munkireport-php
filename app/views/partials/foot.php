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
  </script>


  <script src="<?=conf('subdirectory')?>assets/js/bootstrap.min.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/jquery.dataTables.min.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/datatables.bootstrap.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/moment.min.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/flotr2.js"></script>
  <script src="<?=conf('subdirectory')?>assets/js/i18next.min.js"></script>
  <script>
    $.i18n.init({
        useLocalStorage: false,
        debug: true,
        resGetPath: "<?=conf('subdirectory')?>assets/locales/__lng__.json",
        fallbackLng: 'en',
        useDataAttrOptions: true
    }, function() {
        $('.nav, .panel, table, .alert, .tab-content, .machine-info').i18n();
        // Add tooltips after translation
        $('[title]').tooltip();
        // Set the current locale in moment.js
        moment.locale([i18n.lng(), 'en'])

        // Activate current lang dropdown
        $('.locale a[data-i18n=\'nav.lang.' + i18n.lng() + '\']').parent().addClass('active')
        // Trigger appReady
        $(document).trigger('appReady');
    });
  </script>
  <?if(conf('custom_js')):?> 
  <script src="<?=conf('custom_js')?>"></script>
  <?endif?>

  <script src="<?=conf('subdirectory')?>assets/js/munkireport.js"></script>

</body>
</html>