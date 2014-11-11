<?php
	$display = new Displays_info_model();
	$sql = "SELECT *
					FROM displays
					WHERE serial_number = '$serial_number'
					ORDER BY type";
?>

	<h2>Displays</h2>
		<?foreach($display->query($sql) as $obj):?>
			<span class="label label-success nw-displayscount"></span>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>
							<?php
								switch ($obj->vendor) {
									case "610":
										echo "Apple";
										break;
									case "10ac":
										echo "Dell";
										break;
									case "5c23":
										echo "Wacom";
										break;
									case "4d10":
										echo "Sharp";
										break;
									case "1e6d":
										echo "LG";
										break;
									case "38a3":
										echo "NEC";
										break;
									case "4c49":
										echo "SMART Technologies";
										break;
									case "9d1":
										echo "BenQ";
										break;
									case "4dd9":
										echo "Sony";
										break;
									case "472":
										echo "Acer";
										break;
									case "22f0":
										echo "HP";
										break;
									case "34ac":
										echo "Mitsubishi";
										break;
									case "5a63":
										echo "ViewSonic";
										break;
									case "4c2d":
										echo "Samsung";
										break;
									case "593a":
										echo "Vizio";
										break;
									case "d82":
										echo "CompuLab";
										break;
								}
							?>
							<?=$obj->model?> (<?=($obj->type == 0 ? 'Built-in' : 'External') ?>)
						</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Serial</td>
						<td><?=$obj->display_serial?></td>
					</tr>
					<tr>
						<td>Manufacture date</td>
						<td><?=$obj->manufactured?></td>
					</tr>
					<tr>
						<td>Native resolution</td>
						<td><?=$obj->native?></td>
					</tr>
					<tr>
						<td>Was connected</td>
						<td><time title="<?=strftime('%c',$obj->timestamp)?>" datetime="<?=date('c',$obj->timestamp)?>"></time></td>
					</tr>
				</tbody>
			</table>
		<?endforeach?>

<script type="text/javascript" charset="utf-8">
	// Set displays count in tab header
	$(document).ready(function() {
		$('#displays-cnt').html($('.nw-displayscount').length)
	} );
</script>
