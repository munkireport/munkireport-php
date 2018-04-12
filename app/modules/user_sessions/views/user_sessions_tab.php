<p>
<table class="user_sessions table table-striped table-bordered">
	<thead>
		<tr>
            <th data-i18n="event"></th>
            <th data-i18n="username"></th>
            <th data-i18n="user_sessions.uid"></th>
            <th data-i18n="user_sessions.ipaddress"></th>
            <th data-i18n="user_sessions.time"></th>
		</tr>
	</thead>
	<tbody>
<?php $user_sessionsitemobj = new user_sessions_model(); ?>
      <?php foreach($user_sessionsitemobj->retrieve_records($serial_number) as $item): ?>
        <tr>
          <td><?php echo str_replace(array('sshlogin','login','logout','shutdown','reboot'), array('SSH Login','Login','Logout','Shutdown','Reboot'), $item->event); ?></td>
          <td><?php echo $item->user; ?></td>
          <td><?php echo $item->uid; ?></td>
          <td><?php echo $item->remote_ssh; ?></td>
          <td><?php echo date("Y-m-d H:i:s", $item->time); ?></td>
        </tr>
  <?php endforeach; ?>	</tbody>
</table>

<script>
  $(document).on('appReady', function(e, lang) {

        // Initialize datatables
            $('.user_sessions').dataTable({
                "bServerSide": false,
                "aaSorting": [[4,'asc']]
            });
  });
</script>
