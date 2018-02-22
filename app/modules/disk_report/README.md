Disk report module
==============

Provides information on all mounted HFS volumes by running 
`diskutil list -plist` and `diskutil info -plist deviceID`

The table provides the following information per client:

* totalsize (int) Size in Bytes
* freespace (int) Size in Bytes
* percentage (int) percentage 0-100
* smartstatus (string) Verified, Unsupported or Failing
* volumetype (string) HFS+, APFS, BOOTCAMP
* media_type (string) SSD, FUSION, RAID or HDD
* busprotocol (string) PCI, SAS, SATA, USB
* internal (bool)
* mountpoint (string)
* volumename (string)
* encrypted (bool)


Remarks
---

* Sizes are rounded to .5 GB steps to prevent uploading the diskreport every time the size changes with a minimal amount.
* USB FLASH drives are reported as HDD - this should be fixed
