<p>
<table class="inventory table table-striped table-bordered">
	<thead>
		<tr>
      <th data-i18n="name">Name</th>
      <th data-i18n="version">Version</th>
      <th data-i18n="bundle_id">BundleID</th>
      <th data-i18n="path">Path</th>
		</tr>
	</thead>
	<tbody>
	<?php $inventoryitemobj = new Inventory_model(); ?>
	<?php foreach($inventoryitemobj->retrieve_many(
		'serial_number=?', array($serial_number)) as $item): ?>
		      <?php $name_url=url('module/inventory/items/'. rawurlencode($item->name)); ?>
      <?php $vers_url=$name_url . '/' . rawurlencode($item->version); ?>
        <tr>
          <td><a href='<?php echo $name_url; ?>'><?php echo $item->name; ?></a></td>
          <td><a href='<?php echo $vers_url; ?>'><?php echo $item->version; ?></a></td>
          <td><?php echo $item->bundleid; ?></td>
          <td><?php echo $item->path; ?></td>
        </tr>
  <?php endforeach; ?>

	</tbody>
</table>

<script>
  $(document).on('appReady', function(e, lang) {

        // Initialize datatables
            $('.inventory').dataTable({
                "bServerSide": false,
                "aaSorting": [[1,'asc']],
                "fnDrawCallback": function( oSettings ) {
                $('#inventory-cnt').html(oSettings.fnRecordsTotal());
              }
            });
  });
</script>