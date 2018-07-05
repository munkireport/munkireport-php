<?php $this->view('partials/head', array(
    "scripts" => array(
        "clients/client_list.js"
    )
)); ?>

<div class="container">

     <div class="row">

        <?php $widget->view($this, 'agent_running'); ?>

        <?php $widget->view($this, 'active_threats'); ?>

    </div> <!-- /row -->

    <div class="row">

        <?php $widget->view($this, 'enforcing_security'); ?>

        <?php $widget->view($this, 'self_protection'); ?>

    </div> <!-- /row -->

    <div class="row">

        <?php $widget->view($this, 'version'); ?>

        <?php $widget->view($this, 'mgmt_url'); ?>

    </div> <!-- /row -->


</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
