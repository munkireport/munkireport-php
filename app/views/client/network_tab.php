<?
$nw = new Network_model();?>

<?foreach($nw->retrieve_many(
		'serial_number=? ORDER BY `order`', array($serial_number)) as $item):?>

<h2><?=$item->service?> (<?=$item->order?>)

<?if($item->status):?>
<span class="label label-success nw-enabled"><?=lang('enabled')?></span>
<?else:?>
<span class="label label-danger nw-disabled"><?=lang('disabled')?></span>
<?endif?>

</h2>

<div class="col-lg-6">

	<div class="table-responsive">

		<table class="table table-bordered">
			<caption>IPv4</caption>
			<tr>
				<th><?=lang('ethernet')?></th>
				<th><?=lang('ip_address')?></th>
				<th><?=lang('network_mask')?></th>
				<th><?=lang('router')?></th>
				<th><?=lang('configuration')?></th>
			</tr>
			<tr>
				<td><?=$item->ethernet?></td>
				<td><?=$item->ipv4ip?></td>
				<td><?=$item->ipv4mask?></td>
				<td><?=$item->ipv4router?></td>
				<td><?=$item->ipv4conf?></td>
			</tr>

		</table>

	</div>

</div>

<div class="col-lg-6">

	<div class="table-responsive">

		<table class="table table-bordered">

			<caption>IPv6</caption>
			<tr>
				<th><?=lang('ethernet')?></th>
				<th><?=lang('ip_address')?></th>
				<th><?=lang('prefix_length')?></th>
				<th><?=lang('router')?></th>
				<th><?=lang('configuration')?></th>
			</tr>
			<tr>
				<td><?=$item->ethernet?></td>
				<td><?=$item->ipv6ip?></td>
				<td><?=$item->ipv6prefixlen?></td>
				<td><?=$item->ipv6router?></td>
				<td><?=$item->ipv6conf?></td>
			</tr>

		</table>
	</div>
</div>


<?endforeach?>

<script type="text/javascript" charset="utf-8">
	// Set network interface count in tab header
	// Todo: set disabled count as well
    $(document).ready(function() {
        $('#network-cnt').html($('.nw-enabled').length)
    } );
</script>