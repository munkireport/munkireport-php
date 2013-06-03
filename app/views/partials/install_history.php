<?$history = new InstallHistory()?>
<?if($history->retrieve_one('serial_number=?', $serial)):?>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>Version</th>
			<th>Install Date</th>
			<th>ProcessName</th>
		</tr>
	</thead>
	<tbody>
	<?foreach($history->retrieve_many('serial_number=?', $serial) AS $item):?>
	<?if($apple == (strpos($item->packageIdentifiers[0],'com.apple.') === 0)):?>
		<tr>
			<td><?=$item->displayName?></td>
			<td><?=$item->displayVersion?></td>
			<td><?=date('Y-m-d G:i:s',$item->date)?></td>
			<td><?=$item->processName?></td>
		</tr>
	<?endif?>
	<?endforeach?>
	</tbody>
</table>
<?else:?>
<p><i>No Install History</i></p>
<?endif?>
