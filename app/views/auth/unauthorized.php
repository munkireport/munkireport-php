<?php $this->view('partials/head'); ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-lg-offset-4">
		        <div class="well">
		        	<form id="login-form" action="<?php echo $loginurl?>" method="get" accept-charset="UTF-8" class="form-horizontal">
						<fieldset>
							<legend>

								<?php echo conf('sitename'); ?>

							</legend>

							<p data-i18n="auth.not_authorized"></p>
							<p><button type="submit" class="btn btn-primary" data-i18n="auth.signin"></button></p>

			            </fieldset>
					</form>
				</div>
			</div>
		</div>
	</div><!-- /container -->
	
	<?php $this->view('partials/foot'); ?>
