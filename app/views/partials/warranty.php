<?if($warranty['id']):?>

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
			<td>
				<?=$warranty['status']?>
				<?if($warranty['status'] == 'Unregistered serialnumber'):?>
				<a href="https://selfsolve.apple.com/RegisterProduct.do?productRegister=Y&country=USA&id=<?=$machine['serial_number']?>">Register</a>
				<?endif?>
			</td>
			<td><?=$warranty['end_date']?></td>
			<td><?=date('Y-m-d G:i:s', $warranty['nextcheck'])?>
				<a class="btn btn-small" href="<?php echo url("clients/recheck_warranty/"
					. $machine['serial_number']);?>">Check now</a>
			</td>
		</tr>
	</tbody>
</table>
<?else:?>
<p><i>No Warranty data</i></p>
<?endif?>
