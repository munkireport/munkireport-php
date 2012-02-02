#!/bin/bash

fname="MunkiScripts"
version="$2"
BASEURL="$1"
CATALOG="testing"
EXTENSION=".plist"

if [[ ( -z "$version" ) || ( -z "$BASEURL" ) ]]; then
    echo "Usage: $0 baseurl version"
    exit 1
fi

dmg_fname="$fname-$version.dmg"

tmpdir=`mktemp -d -t munkiscripts`
tmproot="$tmpdir/munkiscripts"
mkdir "$tmproot"

printf "\nCopying files\n"
for i in preflight postflight report_broken_client; do
	sed "s#REPLACE_BASEURL#${BASEURL}#" $i > "${tmproot}/${i}";
done

printf "\nCreating image\n"
hdiutil create -srcfolder "$tmproot" -ov "$dmg_fname"

md5_preflight=`md5 ${tmproot}/preflight | awk '{print $NF}'`
md5_postflight=`md5 ${tmproot}/postflight | awk '{print $NF}'`
md5_report_broken_client=`md5 ${tmproot}/report_broken_client | awk '{print $NF}'`

printf "\nCleaning up\n"
rm -rf "$tmpdir"

printf "\nGenerating munki info file\n"
sha256_dmg=`shasum -a 256 "$dmg_fname" | awk '{print $1}'`

cat > "${fname-}-${version}${EXTENSION}" <<EOF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>autoremove</key>
	<false/>
	<key>catalogs</key>
	<array>
		<string>${CATALOG}</string>
	</array>
	<key>forced_install</key>
	<true/>
	<key>unattended_install</key>
	<true/>
	<key>installer_item_hash</key>
	<string>${sha256_dmg}</string>
	<key>installer_item_location</key>
	<string>$dmg_fname</string>
	<key>installer_item_size</key>
	<integer>15</integer>
	<key>installer_type</key>
	<string>copy_from_dmg</string>
	<key>installs</key>
	<array>
		<dict>
			<key>md5checksum</key>
			<string>${md5_preflight}</string>
			<key>path</key>
			<string>/usr/local/munki/preflight</string>
			<key>type</key>
			<string>file</string>
		</dict>
		<dict>
			<key>md5checksum</key>
			<string>${md5_postflight}</string>
			<key>path</key>
			<string>/usr/local/munki/postflight</string>
			<key>type</key>
			<string>file</string>
		</dict>
		<dict>
			<key>md5checksum</key>
			<string>${md5_report_broken_client}</string>
			<key>path</key>
			<string>/usr/local/munki/report_broken_client</string>
			<key>type</key>
			<string>file</string>
		</dict>
	</array>
	<key>items_to_copy</key>
	<array>
		<dict>
			<key>destination_path</key>
			<string>/usr/local/munki</string>
			<key>group</key>
			<string>wheel</string>
			<key>mode</key>
			<string>0755</string>
			<key>source_item</key>
			<string>preflight</string>
			<key>user</key>
			<string>root</string>
		</dict>
		<dict>
			<key>destination_path</key>
			<string>/usr/local/munki</string>
			<key>group</key>
			<string>wheel</string>
			<key>mode</key>
			<string>0755</string>
			<key>source_item</key>
			<string>postflight</string>
			<key>user</key>
			<string>root</string>
		</dict>
		<dict>
			<key>destination_path</key>
			<string>/usr/local/munki</string>
			<key>group</key>
			<string>wheel</string>
			<key>mode</key>
			<string>0755</string>
			<key>source_item</key>
			<string>report_broken_client</string>
			<key>user</key>
			<string>root</string>
		</dict>
	</array>
	<key>minimum_os_version</key>
	<string>10.4.0</string>
	<key>name</key>
	<string>MunkiScripts</string>
	<key>uninstall_method</key>
	<string>remove_copied_items</string>
	<key>uninstallable</key>
	<true/>
	<key>version</key>
	<string>${version}</string>
</dict>
</plist>
EOF
