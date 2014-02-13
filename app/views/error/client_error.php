<?header( "HTTP/1.0 $status_code" )?>
<?$this->view('partials/head')?>

<div class="container">

  <div class="row">

  	<div class="col-xs-4 col-xs-offset-4">

	  <div class="panel panel-danger">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-exclamation-sign"></i> <?=lang('error')?></h3>
		
		</div>

		<div class="panel-body">


			<p>

			<?switch($status_code)
			{
				case 426:
				
					printf(lang('error_'.$status_code), secure_url());

					break;

				default:

					echo lang('error_'.$status_code);
			}
			?>


			</p>

		

		</div>

	</div><!-- /panel -->

    </div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>