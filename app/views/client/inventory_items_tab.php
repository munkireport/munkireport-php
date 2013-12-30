<p>
<table class="inventory table table-striped table-bordered">
	<thead>
		<tr>
          <th><?=lang('name')?></th>
          <th><?=lang('version')?></th>
          <th><?=lang('bundle_id')?></th>
          <th><?=lang('path')?></th>
		</tr>
	</thead>
	<tbody>
	<?$inventoryitemobj = new Inventory_model()?>
	<?foreach($inventoryitemobj->retrieve_many(
		'serial=?', array($serial_number)) as $item):?>
		      <?php $name_url=url('module/inventory/items/'. rawurlencode($item->name)); ?>
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
            "aaSorting": [[1,'asc']],
            "fnDrawCallback": function( oSettings ) {
            $('#inventory-cnt').html(oSettings.fnRecordsTotal());
          }
        });
    } );
</script>