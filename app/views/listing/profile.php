<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
  new Profile_model;
  new Machine_model;
?>

<div class="container">

  <div class="row">

	<div class="col-lg-12">

	<h3>Profile report <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-colname='machine#computer_name' class="col-md-4">Profile Name</th>
			<th data-colname='profile#payload_name' class="col-md-8">Payloads</th>
			<!--<th data-colname='profile#profile_UUID'>UUID</th>
			<th data-colname='profile#profile_name'>Profile Name</th>
			<th data-colname='profile#payload_name'>Payload Name</th>
			<th data-colname='profile#payload_display'>Display Name</th>
			<th data-colname='profile#payload_data'>Data</th>
			<th data-colname='profile#profile_removal_allowed'>Removable?</th>
			<th data-sort="desc" data-colname='profile#timestamp'>Detected</th>-->
		  </tr>
		</thead>

		<tbody>
	<?php $profile_item_obj = new Profile_model();
	$items = $profile_item_obj->select('profile_name, (select COUNT(DISTINCT serial_number) from profile p2 where profile.profile_name = p2.profile_name) AS num_profiles, payload_name, serial_number, payload_data', '1 GROUP BY profile_name, payload_name, serial_number');

	$profile = array();
	$profilecount = array();
	$payloaddata = array();
	foreach($items as $item)
	{
		$name = $item['profile_name'];
		$version = $item['payload_name'];
		$serialnumber = $item['serial_number'];
		$profiles = $item['num_profiles'];
		$profile[$name][$version] = $profiles;
		$payloaddata[$name][$version] = $profile_item_obj->json_to_html($item['payload_data']);
		$profilecount[$name] = $profiles;
	}
	?>
	
	<?php foreach($profile as $name => $value): ?>
	<?php $name_url=url('module/profile/items/'. rawurlencode($name)); ?>
	
	<tr>
	  <td>
		<a href='<?php echo $name_url; ?>'><?php echo $name; ?></a>
		<span class='badge badge-info pull-right'><?php echo $profilecount[$name]; ?></span>
	  </td>
	  <td>
		<?php foreach($value as $version => $count): ?>
		<?php $vers_url=$name_url . '/' . rawurlencode($version); ?>
		<a href='<?php echo $vers_url; ?>' data-html="true" data-content='<?php echo $payloaddata[$name][$version];?>' class='popovers' data-trigger='hover'><?php echo $version; ?>
		</a><br />
		<?php endforeach; ?>
	  </td>
	</tr>
		<?php endforeach; ?>
	</tbody>

	  </table>

	</div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>  <!-- /container -->

<script type="text/javascript" charset="utf-8">

	$(document).on('appReady', function(e, lang) {
		$('.table').dataTable({
			serverSide: false,
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
			sorting: [[0,'asc']],
			drawCallback: function( oSettings ) {
				$('#total-count').html(oSettings.fnRecordsTotal());
			}
		});
		$(".popovers").popover({ trigger: "manual" , html: true, animation:false})
			.on("mouseenter", function () {
				var _this = this;
			$(".popovers").popover("hide");
				$(this).popover("show");
			$(".popover").on("mouseleave", function () {
			   $(_this).popover('hide');
			});
		}).on("mouseleave", function () {
			var _this = this;
			setTimeout(function () {
				if (!$(".popover:hover").length) {
					$(_this).popover("hide");
				}
			 }, 300);
		});
	});
	
</script>

<?php $this->view('partials/foot'); ?>
