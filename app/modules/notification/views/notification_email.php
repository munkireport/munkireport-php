<?php foreach($events as $event):?>
    
<b><?php echo $i18nObj->translate($event->type) . ': ' . $event->computer_name; ?></b><br>
<?php echo $i18nObj->translate($event->msg, $event->data); ?><br>
<br>
<a href="<?php printf("%s%sindex.php?/clients/detail/%s", conf('webhost'), conf('subdirectory'), $event->serial_number); ?>">
    <?php echo $i18nObj->translate('email.show_machine_in_browser'); ?>
</a><br>
<br>
<?php echo date('Y-m-d H:i:s', $event->timestamp);?><br>
<br>
<hr>

<?php endforeach?>