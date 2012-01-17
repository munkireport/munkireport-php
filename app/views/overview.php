<?$this->view('partials/head')?>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('.clientlist').dataTable({
			"bFilter": false,
			"bInfo": false,
			"bPaginate": false,
			"bStateSave": true,
			"aaSorting": [[3,'desc']]
		});
	} );
</script>

<h2>Errors</h2>

<?if ( ! $error_clients):?>
    <p><i>No errors.</i></p>
<?else:?>
	<?$obj = new View();$obj->view('partials/client_table', array('clients' => $error_clients))?>
<?endif?>

<h2>Warnings</h2>

<?if ( ! $warning_clients):?>
    <p><i>No errors.</i></p>
<?else:?>
	<?$obj->view('partials/client_table', array('clients' => $warning_clients))?>
<?endif?>


<h2>Activity</h2>

<?if ( ! $activity_clients):?>
    <p><i>No active clients.</i></p>
<?else:?>
	<?$obj->view('partials/client_activity', array('clients' => $activity_clients))?>
<?endif?>

<p><i>No active packages.</i></p>


<h2>Client List</h2>

<ul>
  <li><a href="<?=url('show/client_list')?>">View all clients</a></li>
</ul>

<?$this->view('partials/foot')?>
