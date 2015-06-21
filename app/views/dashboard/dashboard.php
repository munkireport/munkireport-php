<?php $this->view('partials/head'); ?>

<div class="container">

	<?foreach(conf('dashboard_layout', array()) AS $row):?>

	<div class="row">

		<?foreach($row as $item):?>

		<?php $this->view("widgets/${item}_widget"); ?>

		<?endforeach?>

	</div> <!-- /row -->

	<?endforeach?>

</div>	<!-- /container -->

<script>

// Automatically refresh widgets
$(document).on('appReady', function(e, lang) {

	var delay = 60; // seconds
	var refresh = function(){

		$(document).trigger('appUpdate');

		setTimeout(refresh, delay * 1000);
	}

	refresh();

});

</script>

<?php $this->view('partials/foot'); ?>
