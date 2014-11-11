<?$this->view('partials/head')?>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
		        <div class="well">
		        	<form action="" method="post" accept-charset="UTF-8" class="form-horizontal">
						<fieldset>
							<legend data-i18n="auth.generate_hash">
								Generate password hash
							</legend>
							<?foreach($GLOBALS['alerts'] AS $type => $list):?>

						    	<?foreach ($list AS $msg):?>

								<p class="text-<?=$type?>"><?=$msg?></p>

								<?endforeach?>

							<?php endforeach?>

						<?if($reason == 'noauth'):?>
						    <div class="alert alert-danger" data-i18n="auth.noauth_found">No authentication information found, please add an account to the config file.</div>
						<?endif?>
					    <?php if(isset($generated_pwd)):?>
							<!-- Modal -->
							<div class="modal" role="dialog" aria-labelledby="myModalLabel" style="display: block">
								<div class="modal-dialog">
								  <div class="modal-content">
								    <div class="modal-header">
								      <h4 class="modal-title" data-i18n="auth.generated_hash">Generated hash</h4>
								    </div>
								    <div class="modal-body has-success">
								      	<label class="control-label" for="genpwd" data-i18n="auth.addtoconfig">Add this line to config.php:</label>
										<input type="text" id="genpwd" name="genpwd" class="form-control" value="$auth_config['<?php echo $login?>'] = '<?php echo $generated_pwd?>';"></input><br/>
								    </div>
								    <div class="modal-footer">
								      <a href="" class="btn btn-default" data-i18n="auth.gen_other">Generate another</a>
								      <a href="<?=url()?>" class="btn btn-default" data-dismiss="modal" data-i18n="auth.back_to_site">Back to site</a>
								    </div>
								  </div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						<?php endif?>
				    		<div class="form-group">
								<label for="loginusername" class="col-lg-5 control-label"><span data-i18n="auth.username">Username</span></label>
								<div class="col-lg-7">
									<input type="text" id="loginusername" name="login" class="form-control" value="" data-i18n="[placeholder]auth.username" placeholder="Username">
								</div>
							</div>
							<div class="form-group">
								<label for="loginpassword" class="col-lg-5 control-label"><span data-i18n="auth.password">Password</span></label>
								<div class="col-lg-7">
									<input type="password" id="loginpassword" name="password" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-3">
								<button type="submit" class="btn btn-primary" data-i18n="auth.generate">Generate</button> 
								<a href="<?=url()?>" class="btn btn-default" data-dismiss="modal" data-i18n="auth.back_to_site">Back to site</a>	
								</div>
							</div>
						<?php //endif?>				
			            </fieldset>
			            <p class="text-right text-muted"><small>MunkiReport <span data-i18n="version">Version</span> <?=$GLOBALS['version']?></small></p>
					</form>
				</div>
			</div>
		</div>
	</div>

	  <script src="<?=conf('subdirectory')?>assets/js/i18next.min.js"></script>
  <script>
    $.i18n.init({
        useLocalStorage: false,
        debug: true,
        resGetPath: "<?=conf('subdirectory')?>assets/locales/__lng__.json",
        fallbackLng: 'en'
    }, function() {
        $('form').i18n();
    });

</script>


</body>
</html>