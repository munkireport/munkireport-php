<p>
<table class="applications table table-striped table-bordered">
	<thead>
		<tr>
          <th data-i18n="name"></th>
          <th data-i18n="version"></th>
          <th data-i18n="applications.signed_by"></th>
          <th data-i18n="applications.obtained_from"></th>
          <th data-i18n="applications.last_modified"></th>
          <th data-i18n="applications.has64bit"></th>
          <th data-i18n="path"></th>
          <th data-i18n="info"></th>
		</tr>
	</thead>
	<tbody>
	<?php $applicationsitemobj = new Applications_model(); ?>
	<?php foreach($applicationsitemobj->retrieveMany('serial_number=?', array($serial_number)) as $item): ?>
	<?php $name_url=url('show/listing/applications/applications#'. rawurlencode($item->name)); ?>
        <tr>
          <td><a href='<?php echo $name_url; ?>'><?php echo $item->name; ?></a></td>
          <td><?php echo $item->version; ?></td>
          <td><?php echo $item->signed_by; ?></td>
          <td><?php echo str_replace(array('unknown','mac_app_store','apple','identified_developer'), array('Unknown','Mac App Store','Apple','Identified Developer'), $item->obtained_from); ?></td>
          <td><?php echo date("Y-m-d H:i:s", $item->last_modified); ?></td>
          <td><?php echo str_replace(array('1','0'), array('Yes','No'), $item->has64bit); ?></td>
          <td><?php echo $item->path; ?></td>
          <td><?php echo $item->info; ?></td>
        </tr>
	<?php endforeach; ?>

	</tbody>
</table>

<script>
  $(document).on('appReady', function(e, lang) {

        // Initialize datatables
            $('.applications').dataTable({
                "bServerSide": false,
                "aaSorting": [[0,'asc']],
                "fnDrawCallback": function( oSettings ) {
                $('#applications-cnt').html(oSettings.fnRecordsTotal());
              }
            });
  });
</script>
