<?if(isset($installHistory) && count($installHistory) > 1):?>

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
	<?foreach($installHistory as $item):?>
	<?if($apple == (strpos($item->packageIdentifiers[0],'com.apple.') === 0)):?>
		<tr>
			<td><?=$item->displayName?></td>
			<td><?=$item->displayVersion?></td>
			<td><time datetime="<?=date('Y-m-d G:i:s',$item->date)?>"><?=date('Y-m-d G:i:s',$item->date)?></time></td>
			<td><?=$item->processName?></td>
		</tr>
	<?endif?>
	<?endforeach?>
	</tbody>
</table>
<?else:?>
<p><i>No Install History</i></p>
<?endif?>
