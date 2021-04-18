<?php $this->view('partials/head'); ?>

<div class="container-fluid">

	<?php foreach($dashboard_layout AS $row):?>

	<div class="row pt-4">

		<?php foreach($row as $item => $data):?>

			<?php if(array_key_exists('widget', $data)):?>

				<?php $widget->view($this, $data['widget'], $data); ?>

			<?php else:?>

				<?php $widget->view($this, $item, $data); ?>

			<?php endif?>

		<?php endforeach?>

	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script src="<?php echo asset('assets/js/munkireport.autoupdate.js'); ?>"></script>

<?php $this->view('partials/foot'); ?>
