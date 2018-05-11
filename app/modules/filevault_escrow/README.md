# FileVault 2 Escrow module

Integration with [the Crypt authentication plugin](https://github.com/grahamgilbert/crypt)

The table provides the following information per client:

+ enableddate - The data FileVault was enabled
+ enableduser - Users added to the EFI login (authorized to unlock the drive)
+ lvguuid - (CoreStorage) Logical Volume Group UUID
+ lvuuid - (CoreStorage) Logical Volume UUID
+ pvuuid - (CoreStorage) Physical Volume UUID
+ recoverykey -  The personal recovery key
+ Also added is hddserial - The serial number of the hard drive

# Remarks

The workflow:

1. Create a crypto key by calling `vendor/bin/generate-defuse-key` in the root of the munkireport directory. Add the resulting key to `config.php` as `$conf['encryption_key'] = 'def00000505fe726...34'`;

2. Install and configure Crypt make sure to prevent the removal of the plist:
``` bash
$ sudo defaults write /Library/Preferences/com.grahamgilbert.crypt RemovePlist -bool FALSE
```

The recovery key is encrypted before it enters the database and is decrypted after retrieval. Don't lose the encryption key or your recovery keys are lost forever!


You can specify the ServerURL in the crypt preferences to a special url that will respond in a way that the crypt client stops attempting to Escrow.

```bash
$ sudo defaults write /Library/Preferences/com.grahamgilbert.crypt ServerURL "http://munkireportURL/index.php?/module/filevault_escrow/"
```
The client will then checkin at `munkireporturl/index.php?/module/filevault_escrow/checkin`.

# Dependencies

This module is dependent on the filevault_status model to provide the current status of FileVault and to list the user accounts who are authorized to unlock the drive
