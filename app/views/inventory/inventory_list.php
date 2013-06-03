<?$this->view('partials/head')?>

<div class="container">

    <div class="row">

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.inventory').dataTable({
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "bStateSave": true,
            "aaSorting": [[4,'desc']]
        });
    } );
</script>

<?
$hash = new Hash();
$order = " ORDER BY timestamp DESC";
?>

<h1>Inventory Clients (<?=$hash->count('InventoryItem')?>)</h1>

<table class="table table-striped inventory">
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

<?foreach($hash->retrieve_many('name =? '.$order, 'InventoryItem') as $inventory):?>
  <?
	$machine = new Machine($inventory->serial);
	$reportdata = new Reportdata($inventory->serial);
?>
  <tr>
    <td>
        <a href="<?=url("inventory/detail/$inventory->serial")?>"><?=$machine->computer_name?></a>
    </td>
    <td><?=$reportdata->console_user?></td>
    <td><?=$reportdata->remote_ip?></td>
    <td><?=$machine->os_version?></td>
    <td>
        <?=date('Y-M-d H:i:s', $inventory->timestamp)?>
    </td>
  </tr>
<?endforeach?>
</tbody>
</table>

</div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>