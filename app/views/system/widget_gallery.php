<?php $this->view('partials/head'); ?>

<script>
  var loadAllModuleLocales = true
</script>

<div class="container">

    <div class="row">
        <h3 class="col-lg-12" style="margin-top: 0px;margin-bottom: 0px;">
          <span  data-i18n="widget.gallery"></span>
          <span id="total-count" class='label label-primary'>
            <?php echo count($dashboard_layout); ?>
          </span>
        </h3>
    </div>
    

	<?php foreach($dashboard_layout AS $item => $data):?>

	<div class="row">

      <div class="col-lg-12" id="<?=$data['widget_obj']->name?>_gallery">
          <h2><?=$data['widget_obj']->name?></h2>
      </div> <!-- /col-lg-12 -->

			<?php if(array_key_exists('widget', $data)):?>

				<?php $widget->view($this, $data['widget'], $data); ?>

			<?php else:?>

				<?php $widget->view($this, $item, $data); ?>

			<?php endif?>

            <div class="col-md-6">
            <table class="table table-striped">
                <tr>
                    <th data-i18n="widget.module"></th>
                    <td>
                      <?php echo $data['widget_obj']->module; ?>
                      <?php if($data['widget_obj']->active): ?>
                        <span class="label label-success pull-right" data-i18n="active"></span>
                      <?php else: ?>
                        <span class="label label-danger pull-right" data-i18n="inactive"></span>
                      <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <th data-i18n="widget.file_name"></th>
                    <?php if($data['widget_obj']->type == 'yaml'):?>
                    <td><?php echo $data['widget_obj']->view; ?>.yml</td>
                    <?php else:?>
                    <td><?php echo $data['widget_obj']->view; ?>.php</td>
                    <?php endif?>
                </tr>
                <tr>
                    <th data-i18n="widget.path"></th>
                    <td><?php echo $data['widget_obj']->widget_file; ?></td>
                </tr>
            </table>
        </div> <!-- /col-md-3 -->
	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>
<?php $this->view('partials/foot'); ?>
