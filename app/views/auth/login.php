<?$this->view('partials/head')?>

	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-lg-offset-4">
		        <div class="well">
		        	<form action="<?php echo $url?>" method="post" accept-charset="UTF-8" class="form-horizontal">
						<fieldset>
							<legend>
								<?=lang('auth_login')?>

								<?if(empty($_SERVER['HTTPS'])):?>

								- <?=conf('sitename')?><a href="<?=secure_url()?>"><i title="<?=lang('auth_insecure')?>" class="text-danger fa fa-unlock-alt pull-right"></i></a>

								<?else:?>

									<i title="<?=lang('auth_secure')?>" class="text-success fa fa-lock pull-right"></i>

								<?endif?>

							</legend>

					    	<?foreach($GLOBALS['alerts'] AS $type => $list):?>

						    	<?foreach ($list AS $msg):?>

								<p class="text-<?=$type?>"><?=$msg?></p>

								<?endforeach?>

							<?php endforeach?>

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
  <script src="<?=conf('subdirectory')?>assets/js/bootstrap.min.js"></script>

	<script>

	// Add tooltips
	$(document).ready(function() {
		$('[title]').tooltip();
	});

</script>

</body>
</html>