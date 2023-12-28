<?php
/*
    This view should no longer be used but is sometimes referenced directly by a module, so it can't be fully deleted
    until people stop include'ing it
*/

$this->view('partials/head'); ?>

<div class="container-fluid">
    <div class="alert alert-warning" role="alert">
        This module is using an older version of the dashboard template. Bug the author(s) to update the view to a v6 Blade template.
    </div>
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
