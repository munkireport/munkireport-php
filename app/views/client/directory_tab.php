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
					<td><?=$directoryservice->directory_service_comments ? $directoryservice->directory_service_comments : '' ?></td>
				</tr>
				<tr>
					<td>Active Directory Forest</td>
					<td><?=$directoryservice->adforest ? $directoryservice->adforest : '' ?></td>
				</tr>
				<tr>
					<td>Active Directory Domain</td>
					<td><?=$directoryservice->addomain ? $directoryservice->addomain : '' ?></td>
				</tr>
				<tr>
					<td>Computer Account</td>
					<td><?=$directoryservice->computeraccount ? $directoryservice->computeraccount : '' ?></td>
				</tr>
				<td><h4>Advanced Options - User Experience</h4></td><td></td>
				<tr>
					<td>Create mobile account at login</td>
					<td><?=($directoryservice->createmobileaccount == 1) ? 'Enabled' : 'Disabled' ?></td>
				</tr>
				<tr>
					<td>Require confirmation</td>
					<td><?=($directoryservice->requireconfirmation == 1) ? 'Enabled' : 'Disabled' ?></td>
				</tr>
				<tr>
					<td>Force home to startup disk</td>
					<td><?=($directoryservice->forcehomeinstartup == 1) ? 'Enabled' : 'Disabled' ?></td>
				</tr>
				<tr>
					<td>Mount home as sharepoint</td>
					<td><?=($directoryservice->mounthomeassharepoint == 1) ? 'Enabled' : 'Disabled' ?></td>
				</tr>
				<tr>
					<td>Use Windows UNC path for home</td>
					<td><?=($directoryservice->usewindowsuncpathforhome == 1) ? 'Enabled' : 'Disabled' ?></td>
				</tr>
				<tr>
					<td>Network protocol to be used</td>
					<td><?=$directoryservice->networkprotocoltobeused ? $directoryservice->networkprotocoltobeused : '' ?></td>
				</tr>
				<tr>
					<td>Default user Shell</td>
					<td><?=$directoryservice->defaultusershell ? $directoryservice->defaultusershell : '' ?></td>
				</tr>
				<td><h4>Advanced Options - Mappings</h4></td><td></td>
				<tr>
					<td>Mapping UID to attribute</td>
					<td><?=$directoryservice->mappinguidtoattribute ? $directoryservice->mappinguidtoattribute : '' ?></td>
				</tr>
				<tr>
					<td>Mapping user GID to attribute</td>
					<td><?=$directoryservice->mappingusergidtoattribute ? $directoryservice->mappingusergidtoattribute : '' ?></td>
				</tr>
				<tr>
					<td>Mapping group GID to attribute</td>
					<td><?=$directoryservice->mappinggroupgidtoattr ? $directoryservice->mappinggroupgidtoattr : '' ?></td>
				</tr>
				<tr>
					<td>Generate Kerberos authority</td>
					<td><?=($directoryservice->generatekerberosauth == 1) ? 'Enabled' : 'Disabled' ?></td>
				</tr>
				<td><h4>Advanced Options - Administrative</h4></td><td></td>
				<tr>
					<td>Preferred Domain controller</td>
					<td><?=$directoryservice->preferreddomaincontroller ? $directoryservice->preferreddomaincontroller : '' ?></td>
				</tr>
				<tr>
					<td>Allowed admin groups</td>
					<td><?=$directoryservice->allowedadmingroups ? $directoryservice->allowedadmingroups : '' ?></td>
				</tr>
				<tr>
					<td>Authentication from any domain</td>
					<td><?=($directoryservice->authenticationfromanydomain == 1) ? 'Enabled' : 'Disabled' ?></td>
				</tr>
				<tr>
					<td>Packet signing</td>
					<td><?=$directoryservice->packetsigning ? $directoryservice->packetsigning : '' ?></td>
				</tr>
				<tr>
					<td>Packet encryption</td>
					<td><?=$directoryservice->packetencryption ? $directoryservice->packetencryption : '' ?></td>
				</tr>
				<tr>
					<td>Password change interval</td>
					<td><?=$directoryservice->passwordchangeinterval ? $directoryservice->passwordchangeinterval : '' ?></td>
				</tr>
				<tr>
					<td>Restrict Dynamic DNS updates</td>
					<td><?=$directoryservice->restrictdynamicdnsupdates ? $directoryservice->restrictdynamicdnsupdates : '' ?></td>
				</tr>
				<tr>
					<td>Namespace mode</td>
					<td><?=$directoryservice->namespacemode ? $directoryservice->namespacemode : '' ?></td>
				</tr>
			</tbody>

		</table>

<script type="text/javascript" charset="utf-8">
	// Set directory count in tab header
    $(document).on('appReady', function(e, lang) {
        $('#directory-cnt').html($('.nw-dsenabled').length)
    } );
</script>
