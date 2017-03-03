<?php $hist_obj = new Installhistory_model();

$installHistory = $hist_obj->itemsBySerialNumber($serial_number); ?>
<?php if(isset($installHistory) && count($installHistory) > 1): ?>

<?php if($apple):?>
<h2 data-i18n="client.installed_apple_software"></h2>
<?php else:?>
<h2 data-i18n="client.installed_third_party_software"></h2>
<?php endif?>

<table class="install-history-<?php echo $apple; ?> table table-striped">
	<thead>
		<tr>
			<th data-i18n="name"></th>
			<th data-i18n="version"></th>
			<th data-i18n="client.install_date"></th>
			<th data-i18n="client.process_name"></th>
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
<p><i data-i18n="client.no_install_history"></i></p>
<?php endif ?>

<script>
  $(document).on('appReady', function(e, lang) {
  		//Prevent hash search
  		search = '';

        // Initialize datatables
        $('.install-history-<?php echo $apple; ?>').dataTable(
		{
			"order": [[2, 'desc']],
			"fnDrawCallback": function( oSettings ){
				// Update tab counter
				$('#history-cnt-<?php echo $apple; ?>').html(oSettings.fnRecordsTotal());
			},
			"fnCreatedRow": function( nRow, aData, iDataIndex ){
				// Format time and add full time tooltip
				var time = $('time', nRow);
				time
					.text(moment(time.attr('datetime')).fromNow())
					.tooltip();
			}
        });
  });
</script>
