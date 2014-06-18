<?php //Initialize models needed for the table
$ard_obj = new Ard_model($serial_number);
?>

	<h2>Apple Remote Desktop</h2>

		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Text 1</td>
					<td><?=$ard_obj->Text1?></td>
				</tr>
				<tr>
					<td>Text 2</td>
					<td><?=$ard_obj->Text2?></td>
				</tr>
					<tr>
					<td>Text 3</td>
					<td><?=$ard_obj->Text3?></td>
				</tr>
					<tr>
					<td>Text 4</td>
					<td><?=$ard_obj->Text4?></td>
				</tr>
			</tbody>
		</table>