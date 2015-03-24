<p>
<table class="profile table table-striped table-bordered">
	<thead>
		<tr>
      <th data-i18n="name">Name</th>
      <th data-i18n="payload">Payload</th>
		</tr>
	</thead>
	<tbody>
	<?php $profile_item_obj = new Profile_model();
	$items = $profile_item_obj->select('profile_name, payload_name, serial_number', 'serial_number=? GROUP BY profile_name, payload_name, serial_number',array($serial_number));

	$profile = array();
	foreach($items as $item)
	{
		$name = $item['profile_name'];
		$version = $item['payload_name'];
		$serialnumber = $item['serial_number'];
		$profile[$name][$version] = $profiles;
		$profile[$name][$name] = $profiles;
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
        <a href='<?php echo $vers_url; ?>'><?php echo $version; ?>
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
  });
</script>