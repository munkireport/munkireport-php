<?php $this->view('partials/head'); ?>

<div class="container">

	<?php foreach($dashboard_layout AS $row):?>

	<div class="row">

		<?php foreach($row as $item => $data):?>

			<?php if(is_array($data['widget']) && array_key_exists('widget', $data)):?>

				<?php $widget->view($this, $data['widget'], $data); ?>

			<?php else:?>

				<?php $widget->view($this, $item, $data); ?>

			<?php endif?>

		<?php endforeach?>

	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
