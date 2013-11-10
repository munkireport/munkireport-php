<?$this->view('partials/head')?>

	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-lg-offset-4">
		        <div class="well">
		        	<form action="<?php echo $url?>" method="post" accept-charset="UTF-8" class="form-horizontal">
						<fieldset>
							<legend><?=lang('auth_login')?></legend>
					    	<?php if (isset($error)):?>
							<p class="text-danger"><?php echo $error?></p>
							<?php endif?>
							<div class="form-group">
								<label for="loginusername" class="col-md-5 control-label"><?=lang('username')?></label>
								<div class="col-md-7">
									<input type="text" id="loginusername" name="login" class="form-control" value="<?php echo $login?>" placeholder="<?=lang('username')?>">
								</div>
							</div>
							<div class="form-group">
								<label for="loginpassword" class="col-md-5 control-label"><?=lang('password')?></label>
								<div class="col-md-7">
									<input type="password" id="loginpassword" name="password" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-3">
								<button type="submit" class="btn btn-primary"><?=lang('auth_signin')?></button> 
								</div>
							</div>
			            </fieldset>
			            <p class="text-right text-muted"><small>MunkiReport <?=lang('version')?> <?=$GLOBALS['version']?></small></p>
					</form>
				</div>
			</div>
		</div>
	</div><!-- /container -->

</body>
</html>