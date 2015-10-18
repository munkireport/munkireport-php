<?php header('Content-Type: text/plain;charset=utf-8'); ?>
<key>modules</key>
<array>
<?php foreach($modules as $module):?>
    <string><?php echo $module?></string>
<?php endforeach?>
</array>
