<?
$appsToCheck = conf('apps_to_track');
$appsToChecksql = array();
$appsIndex = count($appsToCheck);
$counter=0;
$group_filter = get_machine_group_filter('AND', 'm');

foreach ($appsToCheck as $string) {
    $appsToChecksql[] = "SELECT i.version, COUNT(i.version) as count
    FROM inventoryitem i
    LEFT JOIN machine m ON (m.serial_number = i.serial)
    WHERE i.name = '$string'
    $group_filter
    GROUP BY i.version
    ORDER BY count DESC";
    }
?>

<? while ($appsIndex != $counter ) {
  $machine = new Inventory_model();
?>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading" data-container="body" title="Known versions of <?echo $appsToCheck[$counter];?>">
            <h3 class="panel-title"><i class="fa fa-tachometer"></i> <span data-i18n="<?echo $appsToCheck[$counter];?>"><?echo $appsToCheck[$counter];?></span></h3>
        </div>
        <div class="list-group scroll-box">
            <?foreach($machine->query($appsToChecksql[$counter]) as $obj):?>
            <a href="<?=url('module/inventory/items/'.$appsToCheck[$counter].'/'.$obj->version)?>" class="list-group-item">
                <?=$obj->version?>
                <span class="badge pull-right"><?=$obj->count?></span>
            </a>
            <?endforeach?>
        </div>
    </div><!-- /panel -->
</div><!-- /col -->
<? $counter++; } ?>
