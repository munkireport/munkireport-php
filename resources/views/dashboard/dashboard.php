<?php $this->view('partials/head'); ?>

<div class="container-fluid">

	<?php foreach($dashboard_layout AS $row):?>

	<div class="row pt-4">
        <?php
        foreach ($row as $item => $data) {
            if(is_array($data) && array_key_exists('widget', $data)) {
                $widget->view($this, $data['widget'], $data);
            } else {
                $widget->view($this, $item, $data);
            }
        }
        ?>
	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script src="<?php echo asset('assets/js/munkireport.autoupdate.js'); ?>"></script>

<?php $this->view('partials/foot'); ?>
