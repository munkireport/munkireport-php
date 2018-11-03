<?php $this->view('partials/head', array(
    "scripts" => array(
        "clients/client_list.js"
    )
)); ?>

<div class="container">

    <div class="row">

        <?php $widget->view($this, 'sophos_product_versions'); ?>

        <?php $widget->view($this, 'sophos_engine_versions'); ?>

    </div> <!-- /row -->

    <div class="row">

        <?php $widget->view($this, 'sophos_virus_data_versions'); ?>

        <?php $widget->view($this, 'sophos_user_interface_versions'); ?>

    </div> <!-- /row -->

    <div class="row">

        <?php $widget->view($this, 'sophos_installs'); ?>

    </div> <!-- /row -->

</div> <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
