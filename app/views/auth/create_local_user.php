<?php $this->view('partials/head'); ?>
<div class="container">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="well">
        <form action="" method="post" accept-charset="UTF-8" class="form-horizontal">
          <fieldset>
            <legend data-i18n="auth.create_local_user">
            </legend>
            <?php $this->view('partials/alerts'); ?>
            <div class="form-group">
              <label for="loginusername" class="col-lg-5 control-label">
                <span data-i18n="username"></span>
              </label>
              <div class="col-lg-7">
                <input type="text" id="loginusername" name="login" class="form-control" value="" data-i18n="[placeholder]username" placeholder="Username">
                </div>
              </div>
              <div class="form-group">
                <label for="loginpassword" class="col-lg-5 control-label">
                  <span data-i18n="auth.password"></span>
                </label>
                <div class="col-lg-7">
                  <input type="password" id="loginpassword" name="password" class="form-control" autocomplete="new-password">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-10 col-lg-offset-3">
                    <button type="submit" class="btn btn-primary" data-i18n="auth.download_account">
                    </button>
                    <a href="<?php echo url(); ?>" class="btn btn-default" data-i18n="auth.back_to_site">
                    </a>
                  </div>
                </div>
                <?php //endif; ?>
              </fieldset>
              <p class="text-right text-muted">
                <small>MunkiReport <span data-i18n="version">
                </span>
                <?php echo $GLOBALS['version']; ?>
              </small>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/i18next.min.js"></script>
  <script>
    $.i18n.init({
      useLocalStorage: false,
      debug: true,
      useDataAttrOptions: true,
      resGetPath: "<?php echo conf('subdirectory'); ?>assets/locales/__lng__.json",
      fallbackLng: 'en'
    }, function() {
      $('div.container').i18n();
    });
  </script>
</body>
</html>
