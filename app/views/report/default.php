<?$this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
))?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		

		<?$machine = new Machine()?>

		  <h3>Clients <span class='label label-primary'><?=$machine->count()?></span></h3>
		  
		  <p>Reports are coming soon..</p>



    </div> <!-- /span 12 -->

    <div class="col-lg-4">
		<h2>Hardware breakdown</h2>
		<?$machine = new Machine();
			$sql = "select count(id) as count, machine_desc from machine group by machine_desc";
			?>
		<table class="table table-striped table-condensed">
			<?foreach($machine->query($sql) as $obj):?>
			<tr>
				<td>
					<?=$obj->machine_desc?>
					<span class="badge pull-right"><?=$obj->count?></span>
				</td>
			</tr>
			<?endforeach?>
		</table>
		
		
	</div><!-- /span 4 -->

	<div class="col-lg-4">
		<h2>OS breakdown</h2>
		<? $sql = "select count(id) as count, os_version from machine group by os_version ORDER BY os_version";
			?>
		<table class="table table-striped table-condensed">
			<?foreach($machine->query($sql) as $obj):?>
			<tr>
				<td>
					<?=$obj->os_version?>
					<span class="badge pull-right"><?=$obj->count?></span>
				</td>
			</tr>
			<?endforeach?>
		</table>
	</div><!-- /span 4 -->

	<div class="col-lg-4">
		<h2>Manifests</h2>
		<? $sql = "select count(1) as count, manifestname from munkireport group by manifestname ORDER BY count";
			?>
		<table class="table table-striped table-condensed">
			<?foreach($machine->query($sql) as $obj):?>
			<tr>
				<td>
					<a class="btn btn-default btn-xs" href="<?=url('show/listing/munki/#'.$obj->manifestname)?>"><?=$obj->manifestname?></a>
					<span class="badge pull-right"><?=$obj->count?></span>
				</td>
			</tr>
			<?endforeach?>
		</table>
	</div><!-- /span 4 -->


  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>