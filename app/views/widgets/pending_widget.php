<? 
$machine = new Machine_model(); new Munkireport;
$sql = "SELECT computer_name, pendinginstalls, machine.serial_number
    FROM machine
    LEFT JOIN munkireport USING(serial_number)
    WHERE pendinginstalls > 0 
    ORDER BY pendinginstalls DESC";
?>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon-moon"></i> Clients with pending installs</h3>
        </div>
        <div class="list-group scroll-box">
            <?foreach($machine->query($sql) as $obj):?>
            <a href="<?=url('clients/detail/'.$obj->serial_number)?>" class="list-group-item">
                <?=$obj->computer_name?>
                <span class="badge pull-right"><?=$obj->pendinginstalls?></span>
            </a>
            <?endforeach?>
        </div>
    </div><!-- /panel -->
</div><!-- /col -->