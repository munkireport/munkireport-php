<table class="inventory table table-striped">
	<thead>
		<tr>
          <th>Name</th>
          <th>Version</th>
          <th>BundleID</th>
          <th>Path</th>
		</tr>
	</thead>
	<tbody>
	<?$inventoryitemobj = new Inventoryitem()?>
	<?foreach($inventoryitemobj->retrieve_many(
		'serial=?', array($serial_number)) as $item):?>
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
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.inventory').dataTable({
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "bStateSave": true,
            "aaSorting": [[1,'asc']]
        });
    } );
</script>