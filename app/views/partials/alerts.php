<?php foreach($GLOBALS['alerts'] AS $type => $list):?>
  <?php foreach ($list AS $msg):?>
  <p class="text-<?php echo $type; ?>">
    <?php echo $msg; ?>
  </p>
  <?php endforeach; ?>
<?php endforeach; ?>
