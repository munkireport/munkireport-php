<?$warranty = new Warranty($serial)?>
<?if($warranty->id):?>

<table class="client_info">
	<thead>
		<tr>
			<th>Description</th>
			<th>Coverage end date</th>
			<th>Coverage description</th>
			<th>Next check</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=$warranty->PROD_DESCR?></td>
			<td><?=$warranty->COV_END_DATE?></td>
			<td><?=$warranty->HW_COVERAGE_DESC?></td>
			<td><?=date('Y-m-d G:i:s',$warranty->nextcheck)?></td>
		</tr>
	</tbody>
</table>
<?else:?>
<p><i>No Warranty data</i></p>
<?endif?>
