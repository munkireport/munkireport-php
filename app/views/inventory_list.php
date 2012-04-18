<?$this->view('partials/head')?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.clientlist').dataTable({
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "sPaginationType": "full_numbers",
            "bStateSave": true,
            "aaSorting": [[4,'desc']]
        });
    } );
</script>

<h1>Inventory Clients (<?=count($all_machines)?>)</h1>

<table class="clientlist">
<thead>
  <tr>
    <th>Hostname    </th>
    <th>Console User</th>
    <th>IP          </th>
    <th>OS          </th>
    <th>Last Inventory</th>
  </tr>
</thead>
<tbody>
<?foreach($all_machines as $machine):?>
  <?$url = url("inventory/detail/" . $machine->serial)?>
  <tr>
    <td>
        <a href="<?=$url?>"><?=$machine->name?></a>
    </td>
    <td><?=$machine->console_user?></td>
    <td><?=$machine->remote_ip?></td>
    <td><?='?'?></td>
    <td>
        <?=date('Y-M-d H:i:s', $machine->last_inventory_update)?>
    </td>
  </tr>
<?endforeach?>
</tbody>
</table>

<?$this->view('partials/foot')?>
