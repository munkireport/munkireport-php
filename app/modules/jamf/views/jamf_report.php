<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">
	<div class="row">
		<?php $widget->view($this, 'jamf_enrolled_via_dep'); ?>
		<?php $widget->view($this, 'jamf_user_approved_enrollment'); ?>
		<?php $widget->view($this, 'jamf_user_approved_mdm'); ?>
	</div>
	<div class="row">
		<?php $widget->view($this, 'jamf_mdm_capable'); ?>
		<?php $widget->view($this, 'jamf_purchased_leased'); ?>
		<?php $widget->view($this, 'jamf_automatic_login_disabled'); ?>
	</div>
	<div class="row">
		<?php $widget->view($this, 'jamf_version'); ?>
		<?php $widget->view($this, 'jamf_xprotect_version'); ?>
		<?php $widget->view($this, 'jamf_pending_failed'); ?>
	</div>
	<div class="row">
		<?php $widget->view($this, 'jamf_departments'); ?>
		<?php $widget->view($this, 'jamf_buildings'); ?>
		<?php $widget->view($this, 'jamf_checkin'); ?>
	</div>
</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>