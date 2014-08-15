<?header( "HTTP/1.0 $status_code" )?>
<?$this->view('partials/head')?>

<div class="container">

  <div class="row">

  	<div class="col-xs-4 col-xs-offset-4">

	  <div class="panel panel-danger">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-exclamation-sign"></i> <span data-i18n="errors.title">Error</span></h3>
		
		</div>

		<div class="panel-body">


			<p>

<?switch($status_code):?>
<?case '403': ?>

				<span data-i18n="errors.403">You are not allowed to view this page</span>
<?break?>
<?case '404': ?>

				<span data-i18n="errors.404">Page not found</span>
<?break?>
<?case '426': ?>
				
				<span data-i18n="errors.426">You are required to visit this site using a secure connection.</span>
				<a data-i18n="auth.go_secure" href="<?=secure_url()?>">Go to secure site</a>
					
<?break?>
<?default: ?>
	
				Unknown error
<?endswitch?>


			</p>

		

		</div>

	</div><!-- /panel -->

    </div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>