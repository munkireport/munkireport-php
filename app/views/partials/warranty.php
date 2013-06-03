<?$warranty = new Warranty($serial)?>
<?if($warranty->id):?>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Status</th>
			<th>Coverage end date</th>
			<th>Next check</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=$warranty->status?></td>
			<td><?=$warranty->end_date?></td>
			<td><?=date('Y-m-d G:i:s',$warranty->nextcheck)?></td>
		</tr>
	</tbody>
</table>
<?else:?>
<p><i>No Warranty data</i></p>
<?endif?>
