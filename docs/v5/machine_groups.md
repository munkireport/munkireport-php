# About Machine Groups

Machine Groups can be used to group machines together. Machines have to register themselves into the available machine groups. To do this, all machines have to have a key that corresponds to the machinegroup they belong to.

## Create a Machine Group

To create a Machine Group, open the Munkireport webinterface and click on 'Admin->Manage Business Units'. If you don't have Business Units enabled, you'll see a panel with the title 'Unassigned Groups'. Click on the + sign to add a new group. Give the group a name and type a Machine Key or click on 'generate' to generate a random GUID-style key.

## Deploy Group keys

To deploy the group key, you'll have to add it to the machines running munkireport. On the client, the group key is stored in the 'Passphrase' property in the MunkiReport preferences file. To manually set this key, you can type the following command:

```sh
sudo defaults write /Library/Preferences/MunkiReport Passphrase 'FE0E7F5F-5396-CCE5-3821-52055981CC94'
```

For new machines, you could add this to a first-boot script/package. Depending on your setup, you could set this value with munki (anyone can retrieve this value from you munki repo which might not be desirable).
