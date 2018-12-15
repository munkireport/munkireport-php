# Dashboard layouts

Put dashboard layout `YAML` files in this directory. Make sure the files end with `.yml`.
To override the default dashboard, create a file called `default.yml`.

Structure each file as follows:

```yaml
display_name: My Awesome Dashboard
hotkey: q
row1:
    client:
    messages:
row2:
    new_clients:
    pending_apple:
    pending_munki:
row3:
    munki:
    disk_report:
    uptime:
```
