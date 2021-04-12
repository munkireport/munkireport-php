Configuration
-------------

### Clients widget

Shows a graph with the total machines, active machines and inactive machines. The following coniguration variable determines the *inactive status* by the amount of days that a client has not logged in. The default value is 30.

```sh
REPORTDATA_DAYS_INACTIVE=30`
```

### IP Ranges widget

Plot IP ranges by providing an array with labels and
a partial IP address. Specify multiple partials in array
if you want to group them together.
The IP adress part is queried with SQL LIKE

The configuration has to be a YAML file and is loaded from: 

`local/module_configs/ip_ranges.yml`

You can override this file by specifying the following variable:

```sh
REPORTDATA_IP_CONFIG_PATH=/path/to/custom/config.yml
```

Example:

```yaml
MyOrg: 100.99.
AltLocation:
    - 211.88.12.
    - 211.88.13.
Local: 127.0.0.1
```


## Module Migration to Core

- Verify processor
- Register everything from provides.php
- Update config references from config.php to reportdata.var
- Reconcile multiple same modules
