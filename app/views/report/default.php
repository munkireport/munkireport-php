<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		

		<?$machine = new Machine()?>

		  <h1>Clients <span class="label label-default"><?=$machine->count()?></span></h1>
		  
		  <p>Reports are coming soon..</p>

    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>