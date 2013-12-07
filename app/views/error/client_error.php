<?header( "HTTP/1.0 $status_code" )?>
<?$this->view('partials/head')?>

<div class="container">

  <div class="row">

  	<div class="col-xs-4 col-xs-offset-4">

	  <div class="panel panel-danger">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="icon-exclamation-sign"></i> <?=lang('error')?></h3>
		
		</div>

		<div class="panel-body">
			
			<?if(isset($msg) && $msg):?>

			<?=$msg?>

			<?else:?>

			<p><?=lang('error_'.$status_code)?></p>

			<?endif?>

		</div>

	</div><!-- /panel -->

    </div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>