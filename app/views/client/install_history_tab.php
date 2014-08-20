<?$hist_obj = new Installhistory_model();

$installHistory = $hist_obj->itemsBySerialNumber($serial_number)?>
<?if(isset($installHistory) && count($installHistory) > 1):?>

<table class="install-history-<?=$apple?> table table-striped">
	<thead>
		<tr>
			<th data-i18n="name">Name</th>
			<th data-i18n="version">Version</th>
			<th data-i18n="client.install_date">Install Date</th>
			<th data-i18n="client.process_name">Process Name</th>
		</tr>
	</thead>
	<tbody>
	<?foreach($installHistory as $item):?>
	<?if($apple == (strpos($item->packageIdentifiers,'com.apple.') === 0)):?>
		<tr>
			<td><?=$item->displayName?></td>
			<td><?=$item->displayVersion?></td>
			<td><time title="<?=strftime('%c',$item->date)?>" datetime="<?=date('c',$item->date)?>"></time></td>
			<td><?=$item->processName?></td>
		</tr>
	<?endif?>
	<?endforeach?>
	</tbody>
</table>
<?else:?>
<p><i data-i18n="client.no_install_history">No install history</i></p>
<?endif?>

<script>
  $(document).on('appReady', function(e, lang) {
  		//Prevent hash search
  		search = '';

        // Initialize datatables
            $('.install-history-<?=$apple?>').dataTable({
                "aaSorting": [[2,'asc']],
                "fnDrawCallback": function( oSettings ) {
                $('#history-cnt-<?=$apple?>').html(oSettings.fnRecordsTotal());
              }
            });
  });
</script>
