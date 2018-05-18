DetectX Module
==============

Module pulls data from DetectX results json. It does not **run** DetectX, admins are responsible for creating their own launch daemon that runs DetectX and provides the results data to:
```
/usr/local/munki/preflight.d/cache/detectx.json
```

Example Launch Daemon
``` xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>Label</key>
	<string>DetectX All User Run</string>
	<key>ProgramArguments</key>
	<array>
		<string>/Applications/DetectX Swift.app/Contents/MacOS/DetectX Swift</string>
		<string>search</string>
		<string>-aj</string>
		<string>/usr/local/munki/preflight.d/cache/detectx.json</string>
	</array>
	<key>RunAtLoad</key>
	<true/>
</dict>
</plist>
```
