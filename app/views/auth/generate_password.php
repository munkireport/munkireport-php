<?$this->view('partials/head')?>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
		        <div class="well">
		        	<form action="" method="post" accept-charset="UTF-8" class="form-horizontal">
						<fieldset>
							<legend><?=lang('auth_generate_hash')?></legend>
						<?if($reason == 'noauth'):?>
						    <div class="alert alert-danger"><?=lang('auth_noauth_found')?></div>
						<?endif?>
					    <?php if(isset($generated_pwd)):?>
							<!-- Modal -->
							<div class="modal" role="dialog" aria-labelledby="myModalLabel" style="display: block">
								<div class="modal-dialog">
								  <div class="modal-content">
								    <div class="modal-header">
								      <h4 class="modal-title"><?=lang('auth_generated_hash')?></h4>
								    </div>
								    <div class="modal-body has-success">
								      	<label class="control-label" for="genpwd"><?=lang('auth_addtoconfig')?></label>
										<input type="text" id="genpwd" name="genpwd" class="form-control" value="$auth_config['<?php echo $login?>'] = '<?php echo $generated_pwd?>';"></input><br/>
								    </div>
								    <div class="modal-footer">
								      <a href="" class="btn btn-default"><?=lang('auth_gen_other')?></a>
								      <a href="<?=url()?>" class="btn btn-default" data-dismiss="modal"><?=lang('back_to_site')?></a>
								    </div>
								  </div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						<?php endif?>
				    		<div class="form-group">
								<label for="loginusername" class="col-lg-5 control-label"><?=lang('username')?></label>
								<div class="col-lg-7">
									<input type="text" id="loginusername" name="login" class="form-control" value="" placeholder="<?=lang('username')?>">
								</div>
							</div>
							<div class="form-group">
								<label for="loginpassword" class="col-lg-5 control-label"><?=lang('password')?></label>
								<div class="col-lg-7">
									<input type="password" id="loginpassword" name="password" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-3">
								<button type="submit" class="btn btn-primary"><?=lang('auth_generate')?></button> 
								<a href="<?=url()?>" class="btn btn-default" data-dismiss="modal"><?=lang('back_to_site')?></a>	
								</div>
							</div>
						<?php //endif?>				
			            </fieldset>
			            <p class="text-right text-muted"><small>MunkiReport <?=lang('version')?> <?=$GLOBALS['version']?></small></p>
					</form>
				</div>
			</div>
		</div>
	</div>

</body>
</html>