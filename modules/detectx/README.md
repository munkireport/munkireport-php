DetectX Module
==============

**Admins are responsible for creating their own launch daemon that runs DetectX and provides the results data to:**
```
/usr/local/munki/preflight.d/cache/detectx.json
```

Module pulls data from DetectX Swift (requires Pro or Management License!) results `json`. It does not **run** DetectX!

Differences between Infection and Issue:
- Infections are things that DTXS can’t remove simply by deleting files.
    - So, for example, they may include modifications of essential files or system settings.
    - If you ever get an infection, the best course of action is to contact support@sqwarq.com with details so that you can remediate it.
- Issues are something DTXS can deal with by simple file deletion and restart of the system



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
