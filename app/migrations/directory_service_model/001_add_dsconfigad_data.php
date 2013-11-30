<?php

class Migration_add_dsconfigad_data extends Model 
{
	
	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		switch ($this->get_driver())
		{
			case 'sqlite':

				try 
				{  
		  			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					// Wrap in transaction
					$dbh->beginTransaction();

					// Create new columns
					$sql = "ALTER TABLE directoryservice
							ADD adforest VARCHAR(255),
							ADD addomain VARCHAR(255),
							ADD computeraccount VARCHAR(255),
							ADD createmobileaccount BOOL,
							ADD requireconfirmation BOOL,
							ADD forcehomeinstartup BOOL,
							ADD mounthomeassharepoint BOOL,
							ADD usewindowsuncpathforhome BOOL,
							ADD networkprotocoltobeused VARCHAR(255),
							ADD defaultusershell VARCHAR(255),
							ADD mappinguidtoattribute VARCHAR(255),
							ADD mappingusergidtoattribute VARCHAR(255),
							ADD mappinggroupgidtoattr VARCHAR(255),
							ADD generatekerberosauth BOOL,
							ADD preferreddomaincontroller VARCHAR(255),
							ADD allowedadmingroups VARCHAR(255),
							ADD authenticationfromanydomain BOOL,
							ADD packetsigning VARCHAR(255),
							ADD packetencryption VARCHAR(255),
							ADD passwordchangeinterval VARCHAR(255),
							ADD restrictdynamicdnsupdates VARCHAR(255),
							ADD namespacemode VARCHAR(255)";
					$dbh->exec($sql);

					$dbh->commit();
				}
				catch (Exception $e) 
				{
					$dbh->rollBack();
					$this->errors .= "Failed: " . $e->getMessage();
					return FALSE;
				}
				break;

			case 'mysql':

				// Create new columns
				$sql = "ALTER TABLE directoryservice
						ADD adforest VARCHAR(255),
						ADD addomain VARCHAR(255),
						ADD computeraccount VARCHAR(255),
						ADD createmobileaccount BOOL,
						ADD requireconfirmation BOOL,
						ADD forcehomeinstartup BOOL,
						ADD mounthomeassharepoint BOOL,
						ADD usewindowsuncpathforhome BOOL,
						ADD networkprotocoltobeused VARCHAR(255),
						ADD defaultusershell VARCHAR(255),
						ADD mappinguidtoattribute VARCHAR(255),
						ADD mappingusergidtoattribute VARCHAR(255),
						ADD mappinggroupgidtoattr VARCHAR(255),
						ADD generatekerberosauth BOOL,
						ADD preferreddomaincontroller VARCHAR(255),
						ADD allowedadmingroups VARCHAR(255),
						ADD authenticationfromanydomain BOOL,
						ADD packetsigning VARCHAR(255),
						ADD packetencryption VARCHAR(255),
						ADD passwordchangeinterval VARCHAR(255),
						ADD restrictdynamicdnsupdates VARCHAR(255),
						ADD namespacemode VARCHAR(255)";
				$dbh->exec($sql);

				break;

			default:

				# code...
				break;
		}
	}// End function up()

	public function down()
	{
		// Get database handle
		$dbh = $this->getdbh();

		switch ($this->get_driver())
		{
			case 'sqlite':
				try 
				{  
					$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					// Wrap in transaction
					$dbh->beginTransaction();

					// Create new columns
					$sql = "ALTER TABLE directoryservice
							ADD adforest VARCHAR(255),
							ADD addomain VARCHAR(255),
							ADD computeraccount VARCHAR(255),
							ADD createmobileaccount BOOL,
							ADD requireconfirmation BOOL,
							ADD forcehomeinstartup BOOL,
							ADD mounthomeassharepoint BOOL,
							ADD usewindowsuncpathforhome BOOL,
							ADD networkprotocoltobeused VARCHAR(255),
							ADD defaultusershell VARCHAR(255),
							ADD mappinguidtoattribute VARCHAR(255),
							ADD mappingusergidtoattribute VARCHAR(255),
							ADD mappinggroupgidtoattr VARCHAR(255),
							ADD generatekerberosauth BOOL,
							ADD preferreddomaincontroller VARCHAR(255),
							ADD allowedadmingroups VARCHAR(255),
							ADD authenticationfromanydomain BOOL,
							ADD packetsigning VARCHAR(255),
							ADD packetencryption VARCHAR(255),
							ADD passwordchangeinterval VARCHAR(255),
							ADD restrictdynamicdnsupdates VARCHAR(255),
							ADD namespacemode VARCHAR(255)";
					$this->query($sql);

					$dbh->commit();
				}
				catch (Exception $e) 
				{
					$dbh->rollBack();
					$this->errors .= "Failed: " . $e->getMessage();
					return FALSE;
				}
				break;

			case 'mysql':
				
				// Create new columns
				$sql = "ALTER TABLE directoryservice
						ADD adforest VARCHAR(255),
						ADD addomain VARCHAR(255),
						ADD computeraccount VARCHAR(255),
						ADD createmobileaccount BOOL,
						ADD requireconfirmation BOOL,
						ADD forcehomeinstartup BOOL,
						ADD mounthomeassharepoint BOOL,
						ADD usewindowsuncpathforhome BOOL,
						ADD networkprotocoltobeused VARCHAR(255),
						ADD defaultusershell VARCHAR(255),
						ADD mappinguidtoattribute VARCHAR(255),
						ADD mappingusergidtoattribute VARCHAR(255),
						ADD mappinggroupgidtoattr VARCHAR(255),
						ADD generatekerberosauth BOOL,
						ADD preferreddomaincontroller VARCHAR(255),
						ADD allowedadmingroups VARCHAR(255),
						ADD authenticationfromanydomain BOOL,
						ADD packetsigning VARCHAR(255),
						ADD packetencryption VARCHAR(255),
						ADD passwordchangeinterval VARCHAR(255),
						ADD restrictdynamicdnsupdates VARCHAR(255),
						ADD namespacemode VARCHAR(255)";
				$dbh->query($sql);

				break;

			default:
				# code...
				break;
		}
	}
}
