SMART Stats module
================


The following information is stored in the smart_stats table:


* disk_number - int - device id (/dev/diskX)
* model_family - varchar(255) - Family model of the disk
* device_model - varchar(255) - Device model of the disk
* serial\_number_hdd - varchar(255) - Disk serial number
* lu\_wwn\_device_id - varchar(255)
* firmware_version - varchar(255) - Firmware version of disk
* user_capacity - varchar(255) - Raw capacity of disk
* sector_size - varchar(255) - Reported sector size of disk
* rotation_rate - varchar(255) - Rotation rate of disk
* device_is - varchar(255) - Status of disk in smartctl's database
* ata\_version_is - varchar(255) - Information about ATA protocol in use
* sata\_version_is - varchar(255) - Information about SATA protocol in use
* form_factor - varchar(255) - Form factor of drive
* smart\_support_is - varchar(255) - SMART support about drive
* smart_is - varchar(255) - Is SMART enabled
* error_count - int - Amount of SMART errors
* error_poh - int - Power on hour count at last SMART error
* timestamp - int - Timestamp of last data pull

All other table columns correspond with SMART attributes.  

## Dependency

smart_stats module required smartctl, a part of smartmontools: [https://www.smartmontools.org/](https://www.smartmontools.org/)

Download the latest version of smartmontools for macOS from the official repository here: [https://sourceforge.net/projects/smartmontools/files/latest/download?source=files](https://sourceforge.net/projects/smartmontools/files/latest/download?source=files)

**Munki:**
The downloaded smartmontools package has a name that conflicts with Munki's versioning. To avoid an error, import the downloaded DMG file directly instead of the PKG it contains.

## Notes

Because smartmontools does not yet fully support NVMe drives on macOS, this module does not support NVMe drives.
