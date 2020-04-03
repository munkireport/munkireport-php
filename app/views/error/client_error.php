<?php header( "HTTP/1.0 $status_code" ); ?>
<?php $this->view('partials/head'); ?>

<div class="container">

  <div class="row">

  	<div class="col-xs-4 col-xs-offset-4">

	  <div class="panel panel-danger">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-exclamation-sign"></i> <span data-i18n="errors.title">Error</span></h3>
		
		</div>

		<div class="panel-body">


			<p>

<?php switch($status_code): ?>
<?php case '403': ?>

				<span data-i18n="errors.403">You are not allowed to view this page</span>
<?php break; ?>
<?php case '404': ?>

				<span data-i18n="errors.404">Page not found</span>
<?php break; ?>
<?php case '426': ?>
				
				<span data-i18n="errors.426">You are required to visit this site using a secure connection.</span>
				<a data-i18n="auth.go_secure" href="<?php echo secure_url(); ?>">Go to secure site</a>
					
<?php break; ?>
<?php case '503': ?>

				<span data-i18n="errors.503">MunkiReport is down for maintenance.</span>
<?php break; ?>
<?php default: ?>
	
				Unknown error
<?php endswitch ?>


			</p>
            
            <p>
              <?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?>
            </p>

		

		</div>

	</div><!-- /panel -->

    </div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>
