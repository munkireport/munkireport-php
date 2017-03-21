<p>
<table class="profile table table-striped table-bordered">
	<thead>
		<tr>
      <th data-i18n="profile.profilename"></th>
      <th data-i18n="profile.payload"></th>
		</tr>
	</thead>
	<tbody>
	<?php $profile_item_obj = new Profile_model();
	$items = $profile_item_obj->select('profile_name, payload_name, serial_number, GROUP_CONCAT(payload_data) as payload_data', 'serial_number=? GROUP BY profile_name, payload_name, serial_number',array($serial_number));

	$payloaddata = array();
	$profile = array();
	foreach($items as $item)
	{
		$name = $item['profile_name'];
		$version = $item['payload_name'];
		$serialnumber = $item['serial_number'];
		$profile[$name][$version] = $profile;
		$payloaddata[$name][$version] = $profile_item_obj->json_to_html($item['payload_data']);
	}
	?>

    <?php foreach($profile as $name => $value): ?>
    <?php $name_url=url('module/profile/items/'. rawurlencode($name)); ?>

    <tr>
      <td>
        <a href='<?php echo $name_url; ?>'><?php echo $name; ?></a>
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

<script>
  $(document).on('appReady', function(e, lang) {

        // Initialize datatables
            $('.profile').dataTable({
                "bServerSide": false,
                "aaSorting": [[1,'asc']],
                "fnDrawCallback": function( oSettings ) {
                $('#profile-cnt').html(oSettings.fnRecordsTotal());
              }
            });
	    	//$(".popovers").popover({ trigger: "hover" });
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
