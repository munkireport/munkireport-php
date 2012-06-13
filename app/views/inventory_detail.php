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

<h1><?=$client->name?></h1>

<table class="twocol">
  <tbody>
    <td>
      <h2>Machine info</h2>

      <table class="client_info">
        <tbody>
        <tr>
          <th>Hostname:</th>
          <td><?=$machine_info->hostname?></td>
        </tr>
          <tr>
            <th>Username:</th>
            <td><?=$client->console_user?></td>
          </tr>
          <tr>
            <th>Last inventory date:</th>
            <td><?=date(
                'Y-M-d H:i:s', $machine_info->last_inventory_date)?></td>
          </tr>
          <tr>
            <th>Remote IP:</th>
            <td><?=$client->remote_ip?></td>
          </tr>
        </tbody>
      </table>
    </td>
    <td>
      <h2>&nbsp;</h2>
      <table class="client_info">
          <tbody>
            <tr>
              <th>Model:</th>
              <td><?=$machine_info->machine_model?> <?=$machine_info->cpu_type?> <?=$machine_info->current_processor_speed?></td>
            </tr>
            <tr>
              <th>Memory:</th>
              <td><?=$machine_info->physical_memory?></td>
            </tr>
            <tr>
              <th>Serial:</th>
              <td><?=$client->serial?></td>
            </tr>
              <tr>
                <th>OS:</th>
                <td><?=$machine_info->os_vers.' ('.$machine_info->arch.')'?></td>
              </tr>
            <tr>
              <th>Free Disk Space:</th>
              <td><?=humanreadablesize(
                  $machine_info->available_disk_space * 1024)?></td>
            </tr>
          </tbody>
        </table>
    </td>
  </tbody>
</table>

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