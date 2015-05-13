<?php 
$machine = new Machine_model(); new Munkireport_model;
$filter = get_machine_group_filter('AND');
$sql = "SELECT computer_name, pendinginstalls, machine.serial_number
    FROM machine
    LEFT JOIN munkireport USING(serial_number)
    WHERE pendinginstalls > 0
    $filter
    ORDER BY pendinginstalls DESC";
?>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-moon"></i> Clients with pending installs</h3>
        </div>
        <div class="list-group scroll-box">
            <?php foreach($machine->query($sql) as $obj): ?>
            <a href="<?php echo url('clients/detail/'.$obj->serial_number); ?>" class="list-group-item">
                <?php echo $obj->computer_name; ?>
                <span class="badge pull-right"><?php echo $obj->pendinginstalls; ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div><!-- /panel -->
</div><!-- /col -->