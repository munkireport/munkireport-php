Disk report module
==============

Provides information on all mounted HFS volumes by running 
`diskutil list -plist` and `diskutil info -plist deviceID`

The table provides the following information per client:

* TotalSize (int) Size in Bytes
* FreeSpace (int) Size in Bytes
* Percentage (int) percentage 0-100
* SMARTStatus (string) Verified or ?
* SolidState (int) Yes = 1, No = 0

* TotalSize (int) Size in Bytes
* FreeSpace (int) Size in Bytes
* Percentage (int) percentage 0-100
* SMARTStatus (string) Verified, Unsupported or Failing
* VolumeType (string) SSD, HDD or RAID
* BusProtocol (string) PCI, SAS, SATA, USB
* Internal (bool)
* MountPoint (string)
* VolumeName (string)
* CoreStorageEncrypted (bool)


Remarks
---

* Sizes are rounded to .5 GB steps to prevent uploading the diskreport every time the size changes with a minimal amount.
