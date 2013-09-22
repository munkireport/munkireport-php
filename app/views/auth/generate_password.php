<?$this->view('partials/head')?>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
		        <div class="well">
		        	<form action="" method="post" accept-charset="UTF-8" class="form-horizontal">
						<fieldset>
							<legend>Generate password hash</legend>
						<?if($reason == 'noauth'):?>
						    <div class="alert alert-danger">No authentication information found, please add an account to the config file.</div>
						<?endif?>
					    <?php if(isset($generated_pwd)):?>
							<!-- Modal -->
							<div class="modal" role="dialog" aria-labelledby="myModalLabel" style="display: block">
								<div class="modal-dialog">
								  <div class="modal-content">
								    <div class="modal-header">
								      <h4 class="modal-title">Generated hash</h4>
								    </div>
								    <div class="modal-body has-success">
								      	<label class="control-label" for="genpwd">Add this line to config.php:</label>
										<input type="text" id="genpwd" name="genpwd" class="form-control" value="$auth_config['<?php echo $login?>'] = '<?php echo $generated_pwd?>';"></input><br/>
								    </div>
								    <div class="modal-footer">
								      <a href="" class="btn btn-default">Generate another</a>
								      <a href="<?=url()?>" class="btn btn-default" data-dismiss="modal">Back to site</a>
								    </div>
								  </div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						<?php endif?>
				    		<div class="form-group">
								<label for="loginusername" class="col-lg-3 control-label">Username</label>
								<div class="col-lg-9">
									<input type="text" id="loginusername" name="login" class="form-control" value="" placeholder="Username">
								</div>
							</div>
							<div class="form-group">
								<label for="loginpassword" class="col-lg-3 control-label">Password</label>
								<div class="col-lg-9">
									<input type="password" id="loginpassword" name="password" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-3">
								<button type="submit" class="btn btn-primary">Generate</button> 
								<a href="<?=url()?>" class="btn btn-default" data-dismiss="modal">Back to site</a>	
								</div>
							</div>
						<?php //endif?>				
			            </fieldset>
			            <p class="text-right text-muted"><small>MunkiReport version <?=$GLOBALS['version']?></small></p>
					</form>
				</div>
			</div>
		</div>
	</div>

</body>
</html>