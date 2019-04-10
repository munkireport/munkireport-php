<?php $this->view('partials/head');
$moduleManager = getMrModuleObj();
$widget_List = $moduleManager->getWidgets();
$widget_count = count($widget_List)?>

<script>
  var loadAllModuleLocales = True
</script>
<div class="container">
	<div class="row">
        <h3 class="col-lg-12" style="margin-top: 0px;margin-bottom: 0px;"><span  data-i18n="widget.gallery"></span> <span id="total-count" class='label label-primary'><?php echo $widget_count; ?></span></h3>
    </div>

    <?php foreach($widget_List AS $row):?>

	<div class="row">
        <div class="col-lg-12" id="<?php echo substr($row->name, 0, -7); ?>_gallery">
            <h2>&nbsp;<i class="fa <?php echo $row->icon; ?>"></i>&nbsp;<?php echo substr($row->name, 0, -7); ?></h2>

            <?php $this->view($row->name, $row->vars, $row->path); ?>

            <div class="col-md-4">
                <table class="table table-striped" width="440" style="margin-bottom: 0px;">
                    <tr>
                        <th data-i18n="enabled"></th>
                        <td style="padding-top: 5px;"><?php echo $row->enabled; ?></td>
                    </tr>
                    <tr>
                        <th data-i18n="widget.file_name"></th>
                        <td><?php echo $row->name; ?>.php</td>
                    </tr>
                    <tr>
                        <th data-i18n="widget.path"></th>
                        <td><?php echo $row->widget_file; ?></td>
                    </tr>
                    <tr>
                        <th data-i18n="widget.module"></th>
                        <td><?php echo $row->module; ?></td>
                    </tr>
                    <tr>
                        <th data-i18n="widget.module_active"></th>
                        <td style="padding-top: 5px;"><?php echo $row->active; ?></td>
                    </tr>
                </table>
            </div> <!-- /col-md-3 -->
        </div> <!-- /col-md-6 -->
	</div> <!-- /row -->

	<?php endforeach?>

</div>	<!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>
<?php $this->view('partials/foot'); ?>
