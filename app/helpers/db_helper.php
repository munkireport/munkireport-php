<?php

	function reset_db()
	{
		  $dbh=getdbh();
		  $dbh->exec('DROP TABLE "client"');
		  $dbh->exec('VACUUM');

		$sql = "CREATE TABLE `client` (
		  `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
		  `name` varchar(255)  NOT NULL,
		  `serial` varchar(255)  NULL,
		  `remote_ip` varchar(255)  NULL,
		  `timestamp` varchar(255)  NULL,
		  `runtype` varchar(255)  NULL,
		  `runstate` varchar(255)  NULL,
		  `console_user` varchar(255)  NULL,
		  `errors` integer  NULL,
		  `warnings` integer  NULL,
		  `activity` BLOB,
		  `report_plist` BLOB  
		) ";

			return $dbh->exec($sql);
		
	}
	
	