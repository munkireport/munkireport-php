<?$this->view('partials/head')?>

<div class="container">

	<div style="margin: auto" class="loginform">
		<form action="" method="post" accept-charset="UTF-8" >
		    <h2><span>Generate password hash</span></h2>
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
				    <div class="modal-body">
				      	<label for="genpwd">Add this line to config.php:</label>
						<input type="text" id="genpwd" name="genpwd" class="form-control" value="$auth_config['<?php echo $login?>'] = '<?php echo $generated_pwd?>';"></input><br/>
				    </div>
				    <div class="modal-footer">
				      <button type="submit" class="btn btn-default">Start over</button>
				      <a href="<?=url()?>" class="btn btn-default" data-dismiss="modal">Back to site</a>
				    </div>
				  </div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		<?php else:?>
			<label for="loginusername">Username:</label><input type="text" id="loginusername" name="login" class="text" value="<?php echo $login?>"></input><br/>
			<label for="loginpassword">Password:</label><input type="password" id="loginpassword" name="password" class="text"></input>
			<button type="submit" class="btn btn-default">Generate</button>
		<?php endif?>
		</form>
	</div>

</div>

<?$this->view('partials/foot')?>