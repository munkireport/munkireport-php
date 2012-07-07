<?$this->view('partials/head')?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.clientlist').dataTable({
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "sPaginationType": "full_numbers",
            "bStateSave": true,
            "aaSorting": [[1,'asc']]
        });
    } );
</script>

<?$report_type = (object) array('name'=>'InventoryItems', 'desc' => 'inventory')?>
<?$this->view('partials/machine_info', array('report_type' => $report_type))?>


<? if (count($inventory_items)): ?>
    <h2>Inventory Items (<?=count($inventory_items)?>)</h2>
    <table class='clientlist'>
      <thead>
        <tr>
          <th>Name</th>
          <th>Version</th>
          <th>BundleID</th>
          <th>Path</th>
        </tr>
      </thead>
      <tbody>
      <? foreach($inventory_items as $item): ?>
      <?php $name_url=url('/inventory/items/'. rawurlencode($item->name)); ?>
      <?php $vers_url=$name_url . '/' . rawurlencode($item->version); ?>
        <tr>
          <td><a href='<?=$name_url?>'><?=$item->name?></a></td>
          <td><a href='<?=$vers_url?>'><?=$item->version?></a></td>
          <td><?=$item->bundleid?></td>
          <td><?=$item->path?></td>
        </tr>
      <? endforeach ?>
      </tbody>
    </table>
<? else: ?>
    <h2>Inventory Items</h2>
    <p><i>No inventory items.</i></p>
<? endif ?>

<?$this->view('partials/foot')?>