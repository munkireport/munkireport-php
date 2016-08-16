<?php $this->view('partials/head'); ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-lg-offset-4">
		        <div class="well">
		        	<form action="<?php echo $url?>" method="post" accept-charset="UTF-8" class="form-horizontal">
						<fieldset>
							<legend>

								<?php echo conf('sitename'); ?>

								<?php if(empty($_SERVER['HTTPS'])): ?>

									<a href="<?php echo secure_url(); ?>"><i data-i18n="[title]auth.insecure" title="Insecure connection, switch to secure" class="text-danger fa fa-unlock-alt pull-right"></i></a>

								<?php else: ?>

									<i data-i18n="[title]auth.secure" title="You're using a secure connection" class="text-success fa fa-lock pull-right"></i>

								<?php endif; ?>

							</legend>

					    	<?php foreach($GLOBALS['alerts'] AS $type => $list): ?>

						    	<?php foreach ($list AS $msg): ?>

								<p class="text-<?php echo $type; ?>"><?php echo $msg; ?></p>

								<?php endforeach; ?>

							<?php endforeach; ?>

							<div class="form-group">
								<label for="loginusername" class="col-md-5 control-label"><span data-i18n="auth.username">Username</span></label>
								<div class="col-md-7">
									<input type="text" id="loginusername" name="login" class="form-control" value="<?php echo $login; ?>" data-i18n="[placeholder]auth.username">
								</div>
							</div>
							<div class="form-group">
								<label for="loginpassword" class="col-md-5 control-label"><span data-i18n="auth.password">Password</span></label>
								<div class="col-md-7">
									<input type="password" id="loginpassword" name="password" class="form-control">
								</div>
							</div>
			                                <?php
			                                	if (!empty(conf('recaptchaloginpublickey')))
			                                	{
			                                    	echo "<div class=\"form-group\"><div class=\"col-lg-10 col-lg-offset-2\">";
								echo "<div class=\"g-recaptcha\" data-sitekey=\"";
			                                    	echo conf('recaptchaloginpublickey');
			                                    	echo "\"></div></div></div>\r\n";
			                                	}
			                            	?>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-3">
								<button type="submit" class="btn btn-primary" data-i18n="auth.signin">Sign in</button> 
								</div>
							</div>
			            </fieldset>
			            <p class="text-right text-muted"><small>MunkiReport <span data-i18n="version">Version</span> <?php echo $GLOBALS['version']; ?></small></p>
					</form>
				</div>
			</div>
		</div>
	</div><!-- /container -->
  <script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap.min.js"></script>
  <script src="<?php echo conf('subdirectory'); ?>assets/js/i18next.min.js"></script>
  <script>
    $.i18n.init({
        useLocalStorage: false,
        debug: true,
        resGetPath: "<?php echo conf('subdirectory'); ?>assets/locales/__lng__.json",
        fallbackLng: 'en'
    }, function() {
        $('form').i18n();
        $('[title]').tooltip();
    });

</script>

</body>
</html>
