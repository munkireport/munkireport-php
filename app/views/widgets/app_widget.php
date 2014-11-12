<!--  This widget can be used to specify applications you would like to see in
      MunkiReport-PHP.  The appsToCheck array is pre-popluated with four sample
      applications.  You may remove or add additional applications.  -->

<?
$appsToCheck = array("Safari","Firefox","TextEdit","Notes");
$appsToChecksql = array();
$appsIndex = count($appsToCheck);
$counter=0;


foreach ($appsToCheck as $string) {
    $appsToChecksql[] = "SELECT inventoryitem.version, COUNT(inventoryitem.version) as count
    FROM inventoryitem
    WHERE inventoryitem.name = '$string'
    GROUP BY inventoryitem.version
    ORDER BY count DESC";
    }
?>

<? while ($appsIndex != $counter ) {
  $machine = new Inventory_model();
?>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
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
