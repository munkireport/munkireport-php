<?php
$printer = new Printer_model($serial_number);
// $printer = new printer_model();
$sql = "SELECT *
					FROM printer
					WHERE serial_number = '$serial_number'
					ORDER BY name";
?>

	<h2>Installed Printers</h2>
		<?php foreach($printer->query($sql) as $obj): ?>
		<?php echo $obj->name; ?> 
			<span class="label label-success nw-printercount"></span>
			<table class="table table-striped">
				<tbody>
					<tr>
						<td>Name</td>
						<td><?php echo $obj->name?></td>
					</tr>
					<tr> 
						<td>PPD</td>
						<td><?php echo $obj->ppd?></td>
					</tr>
					<tr>
						<td>Driver Verison</td>
						<td><?php echo $obj->driver_version?></td>
					</tr>
					<tr>
						<td>URL</td>
						<td><?php echo $obj->url?></td>
					</tr>
					<tr>
						<td>Default Set</td>
						<td><?php echo $obj->default_set?></td>
					</tr>
					<tr>
						<td>Printer Status</td>
						<td><?php echo $obj->printer_status?></td>
					</tr>
					<tr>
						<td>Printer Sharing</td>
						<td><?php echo $obj->printer_sharing?></td>
					</tr>
				</tbody>
			</table>
			<?php endforeach; ?>

<script type="text/javascript" charset="utf-8">
	// Set printer count in tab header
	$(document).ready(function() {
		$('#printer-cnt').html($('.nw-printercount').length)
	} );
</script>
