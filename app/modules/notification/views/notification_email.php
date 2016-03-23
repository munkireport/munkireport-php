<p>The following events were recorded:</p>
    
<?php foreach($events as $event):?>
<h2><?php echo $event->computer_name . ' - ' . $event->type; ?></h2>
<p><?php echo date('Y-m-d H:i:s', $event->timestamp);?></p>
<p>    
    <?php echo $event->data; ?><br>
    <?php echo $event->msg; ?><br>
    <?php echo $event->serial_number; ?><br>
    <a href="<?php printf("%s%s/clients/detail/%s", conf('webhost'), conf('subdirectory'), $event->serial_number); ?>"">
        Visit machine in Munkireport
    </a>
    
</p>

<hr>

<?php endforeach?>