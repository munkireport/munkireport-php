Disk report module
==============

Provides information on the System disk trhough running `diskutil info -plist /`

The table provides the following information per client:

* TotalSize (int) Size in Bytes
* FreeSpace (int) Size in Bytes
* Percentage (int) percentage 0-100
* SMARTStatus (string) Verified or ?
* SolidState (int) Yes = 1, No = 0

Remarks
---

* Sizes are truncated to .5 GB steps to prevent uploading the diskreport every time the size changes with a minimal amount.
