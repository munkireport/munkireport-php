<?php $widget_counter = 0 ?>
<div class="row" style="margin-top: 20px">
    <?php foreach($widget_list as $widget_id => $data):?>

    <?php if($widget_counter++ % 3 === 0): ?>

</div><!-- /row -->
<div class="row">

    <?php endif ?>
        <!-- <?php echo $widget_id ?> -->
        <?php $this->view($data['view'], isset($data['view_vars'])?$data['view_vars']:array(), isset($data['view_path'])?$data['view_path']:conf('view_path'));?>
    
    <?php endforeach ?>
</div><!-- /row -->
