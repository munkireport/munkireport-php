<?php
	$directoryservice = new Directory_service_model($serial_number)
?>

	<h2>Directory Service
		<?php
			if (strcasecmp($directoryservice->which_directory_service,'LDAPv3') == 0) {
				echo '<span class="label label-success nw-dsenabled"> LDAPv3</span>';
			}
			if (strcasecmp($directoryservice->which_directory_service,'Active Directory') == 0)  {
				echo '<span class="label label-success nw-dsenabled"> Active Directory</span>';
			}
		?>
	</h2>

		<table class="table table-striped">
			<thead>
				<tr>
					<th>Active Directory Option</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Active Directory Comments</td>
					<td><?php echo $directoryservice->directory_service_comments ? $directoryservice->directory_service_comments : ''; ?></td>
				</tr>
				<tr>
					<td>Active Directory Forest</td>
					<td><?php echo $directoryservice->adforest ? $directoryservice->adforest : ''; ?></td>
				</tr>
				<tr>
					<td>Active Directory Domain</td>
					<td><?php echo $directoryservice->addomain ? $directoryservice->addomain : ''; ?></td>
				</tr>
				<tr>
					<td>Computer Account</td>
					<td><?php echo $directoryservice->computeraccount ? $directoryservice->computeraccount : ''; ?></td>
				</tr>
				<td><h4>Advanced Options - User Experience</h4></td><td></td>
				<tr>
					<td>Create mobile account at login</td>
					<td><?php echo ($directoryservice->createmobileaccount == 1) ? 'Enabled' : 'Disabled'; ?></td>
				</tr>
				<tr>
					<td>Require confirmation</td>
					<td><?php echo ($directoryservice->requireconfirmation == 1) ? 'Enabled' : 'Disabled'; ?></td>
				</tr>
				<tr>
					<td>Force home to startup disk</td>
					<td><?php echo ($directoryservice->forcehomeinstartup == 1) ? 'Enabled' : 'Disabled'; ?></td>
				</tr>
				<tr>
					<td>Mount home as sharepoint</td>
					<td><?php echo ($directoryservice->mounthomeassharepoint == 1) ? 'Enabled' : 'Disabled'; ?></td>
				</tr>
				<tr>
					<td>Use Windows UNC path for home</td>
					<td><?php echo ($directoryservice->usewindowsuncpathforhome == 1) ? 'Enabled' : 'Disabled'; ?></td>
				</tr>
				<tr>
					<td>Network protocol to be used</td>
					<td><?php echo $directoryservice->networkprotocoltobeused ? $directoryservice->networkprotocoltobeused : ''; ?></td>
				</tr>
				<tr>
					<td>Default user Shell</td>
					<td><?php echo $directoryservice->defaultusershell ? $directoryservice->defaultusershell : ''; ?></td>
				</tr>
				<td><h4>Advanced Options - Mappings</h4></td><td></td>
				<tr>
					<td>Mapping UID to attribute</td>
					<td><?php echo $directoryservice->mappinguidtoattribute ? $directoryservice->mappinguidtoattribute : ''; ?></td>
				</tr>
				<tr>
					<td>Mapping user GID to attribute</td>
					<td><?php echo $directoryservice->mappingusergidtoattribute ? $directoryservice->mappingusergidtoattribute : ''; ?></td>
				</tr>
				<tr>
					<td>Mapping group GID to attribute</td>
					<td><?php echo $directoryservice->mappinggroupgidtoattr ? $directoryservice->mappinggroupgidtoattr : ''; ?></td>
				</tr>
				<tr>
					<td>Generate Kerberos authority</td>
					<td><?php echo ($directoryservice->generatekerberosauth == 1) ? 'Enabled' : 'Disabled'; ?></td>
				</tr>
				<td><h4>Advanced Options - Administrative</h4></td><td></td>
				<tr>
					<td>Preferred Domain controller</td>
					<td><?php echo $directoryservice->preferreddomaincontroller ? $directoryservice->preferreddomaincontroller : ''; ?></td>
				</tr>
				<tr>
					<td>Allowed admin groups</td>
					<td><?php echo $directoryservice->allowedadmingroups ? $directoryservice->allowedadmingroups : ''; ?></td>
				</tr>
				<tr>
					<td>Authentication from any domain</td>
					<td><?php echo ($directoryservice->authenticationfromanydomain == 1) ? 'Enabled' : 'Disabled'; ?></td>
				</tr>
				<tr>
					<td>Packet signing</td>
					<td><?php echo $directoryservice->packetsigning ? $directoryservice->packetsigning : ''; ?></td>
				</tr>
				<tr>
					<td>Packet encryption</td>
					<td><?php echo $directoryservice->packetencryption ? $directoryservice->packetencryption : ''; ?></td>
				</tr>
				<tr>
					<td>Password change interval</td>
					<td><?php echo $directoryservice->passwordchangeinterval ? $directoryservice->passwordchangeinterval : ''; ?></td>
				</tr>
				<tr>
					<td>Restrict Dynamic DNS updates</td>
					<td><?php echo $directoryservice->restrictdynamicdnsupdates ? $directoryservice->restrictdynamicdnsupdates : ''; ?></td>
				</tr>
				<tr>
					<td>Namespace mode</td>
					<td><?php echo $directoryservice->namespacemode ? $directoryservice->namespacemode : ''; ?></td>
				</tr>
			</tbody>

		</table>

<script type="text/javascript" charset="utf-8">
	// Set directory count in tab header
    $(document).on('appReady', function(e, lang) {
        $('#directory-cnt').html($('.nw-dsenabled').length)
    } );
</script>
