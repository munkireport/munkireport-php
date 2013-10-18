<?php 
	header("Content-Type: application/xml");
	$version = "1.0.0";

	// We're echoing the header so the '<?' doesn't turn on the PHP interpreter
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
  <key>autoremove</key>
  <false/>
  <key>catalogs</key>
  <array>
    <string>production</string>
  </array>
  <key>name</key>
  <string>munkireport</string>
  <key>display_name</key>
  <string>Munkireport Install and config</string>
  <key>installer_type</key>
  <string>nopkg</string>
  <key>installs</key>
  <array>
    <dict>
      <key>path</key>
      <string>/usr/local/munki/munkireport-<?php echo $version; ?></string>
      <key>type</key>
      <string>file</string>
    </dict>
  </array>
  <key>preinstall_script</key>
  <string>#!/bin/bash -c
"`curl <?php echo
  (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
	  . $_SERVER['HTTP_HOST']
		. conf('subdirectory'); ?>index.php?/install`"</string>
  <key>minimum_os_version</key>
  <string>10.4.0</string>
  <key>uninstallable</key>
  <true/>
  <key>uninstall_method</key>
  <string>uninstall_script</string>
  <key>uninstall_script</key>
  <string>#!/bin/sh
rm -rf /usr/local/munki/preflight \
  /usr/local/munki/preflight.d \
  /usr/local/munki/postflight \
  /usr/local/munki/report_broken_client \
  /usr/local/munkilib/reportcommon.py \
  /usr/local/munki/munkireport-* \
  /Library/Preferences/MunkiReport.plist
exit 0</string>
  <key>version</key>
  <string><?php echo $version; ?></string>
</dict>
</plist>
