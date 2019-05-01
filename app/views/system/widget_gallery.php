<?php $this->view('partials/head'); ?>

<script>
  var loadAllModuleLocales = true
</script>
<div class="container">
	<div class="row">
        <h3 class="col-lg-12" style="margin-top: 0px;margin-bottom: 0px;">
          <span  data-i18n="widget.gallery"></span>
          <span id="total-count" class='label label-primary'>
            <?php echo count($widgetList); ?>
          </span>
        </h3>
    </div>

    <?php foreach($widgetList AS $row):?>

	<div class="row">
        <div class="col-lg-12" id="<?php echo substr($row->name, 0, -7); ?>_gallery">
            <h2><?php echo substr($row->name, 0, -7); ?></h2>
        </div> <!-- /col-lg-12 -->

        <?php $this->view($row->name, $row->vars, $row->path); ?>

        <div class="col-md-4">
            <table class="table table-striped">
                <tr>
                    <th data-i18n="widget.module"></th>
                    <td>
                      <?php echo $row->module; ?>
                      <?php if($row->active): ?>
                        <span class="label label-success pull-right" data-i18n="active"></span>
                      <?php else: ?>
                        <span class="label label-danger pull-right" data-i18n="inactive"></span>
                      <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <th data-i18n="widget.file_name"></th>
                    <td><?php echo $row->name; ?>.php</td>
                </tr>
                <tr>
                    <th data-i18n="widget.path"></th>
                    <td><?php echo $row->widget_file; ?></td>
                </tr>
            </table>
        </div> <!-- /col-md-3 -->
	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>
<?php $this->view('partials/foot'); ?>
