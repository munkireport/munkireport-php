// This widget will pull and count the versions of Safari installed on machines.
// To Customize, copy and paste the code below replacing instances of "Safari"
// with the app you wish to show in a widget.  You can find the formatting of
// the app name from MunkiReport -> Listings -> Inventory

<?
$machine = new Inventory_model();
$sql = "SELECT machine.computer_name, machine.serial_number,inventoryitem.version, COUNT (inventoryitem.version) as count
FROM machine
INNER JOIN inventoryitem
ON machine.serial_number=inventoryitem.serial
WHERE inventoryitem.name = 'Safari'
GROUP by inventoryitem.version
ORDER BY inventoryitem.version DESC";
?>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-compass"></i> <span data-i18n="Safari">Safari</span></h3>
        </div>
        <div class="list-group scroll-box">
            <?foreach($machine->query($sql) as $obj):?>
            <a href="<?=url('module/inventory/items/Safari/'.$obj->version)?>" class="list-group-item">
                <?=$obj->version?>
                <span class="badge pull-right"><?=$obj->count?></span>
            </a>
            <?endforeach?>
        </div>
    </div><!-- /panel -->
</div><!-- /col -->
