<?php $hist_obj = new Installhistory_model();

$installHistory = $hist_obj->itemsBySerialNumber($serial_number); ?>
<?php if(isset($installHistory) && count($installHistory) > 1): ?>

<table class="install-history-<?php echo $apple; ?> table table-striped">
	<thead>
		<tr>
			<th data-i18n="name">Name</th>
			<th data-i18n="version">Version</th>
			<th data-i18n="client.install_date">Install Date</th>
			<th data-i18n="client.process_name">Process Name</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($installHistory as $item): ?>
	<?php if($apple == (strpos($item->packageIdentifiers,'com.apple.') === 0)): ?>
		<tr>
			<td><?php echo $item->displayName; ?></td>
			<td><?php echo $item->displayVersion; ?></td>
			<td data-order="<?php echo $item->date; ?>"><time title="<?php echo strftime('%c',$item->date); ?>" datetime="<?php echo date('c',$item->date); ?>"></time></td>
			<td><?php echo $item->processName; ?></td>
		</tr>
	<?php endif; ?>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p><i data-i18n="client.no_install_history">No install history</i></p>
<?php endif ?>

<script>
  $(document).on('appReady', function(e, lang) {
  		//Prevent hash search
  		search = '';

        // Initialize datatables
        $('.install-history-<?php echo $apple; ?>').dataTable(
		{
			"aaSorting": [[2,'asc']],
			"fnDrawCallback": function( oSettings ){
				// Update tab counter
				$('#history-cnt-<?php echo $apple; ?>').html(oSettings.fnRecordsTotal());
			},
			"fnCreatedRow": function( nRow, aData, iDataIndex ){
				// Add full time tooltip
				$('time', nRow).tooltip();
			}
        });
  });
</script>
