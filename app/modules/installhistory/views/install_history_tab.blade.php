<?php $hist_obj = new Installhistory_model();

$installHistory = $hist_obj->itemsBySerialNumber($serial_number); ?>
<?php if(isset($installHistory) && count($installHistory) > 1): ?>

<?php if($apple):?>
<h2 data-i18n="installhistory.installed_apple_software"></h2>
<?php else:?>
<h2 data-i18n="installhistory.installed_third_party_software"></h2>
<?php endif?>

<table class="install-history-{{ $apple }} table table-striped">
	<thead>
		<tr>
			<th data-i18n="name"></th>
			<th data-i18n="version"></th>
			<th data-i18n="installhistory.install_date"></th>
			<th data-i18n="installhistory.process_name"></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($installHistory as $item): ?>
	<?php if($apple == (strpos($item->packageIdentifiers,'com.apple.') === 0)): ?>
		<tr>
			<td>{{ $item->displayName }}</td>
			<td>{{ $item->displayVersion }}</td>
			<td data-order="{{ $item->date }}"><time title="{{ strftime('%c',$item->date) }}" datetime="{{ date('c',$item->date) }}"></time></td>
			<td>{{ $item->processName }}</td>
		</tr>
	<?php endif; ?>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p><i data-i18n="installhistory.no_install_history"></i></p>
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
