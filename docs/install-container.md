# Container Based Installations (docker/compose/ECS/k8s etc)

## Environment Variables

Anything that is valid in the **.env** file, is valid as a container environment variable.

## Volume Mounts

The following paths may use persistent volume mounts if you need to keep this data between container restarts:

| Path              | Description                                                                                                                        |
|:------------------|:-----------------------------------------------------------------------------------------------------------------------------------|
| storage/logs      | Error, Debug and Deprecation Logs, see also [The Storage Directory](https://laravel.com/docs/10.x/structure#the-storage-directory) |
| storage/framework | Framework Cache (Not necessary to keep)                                                                                            |
| storage/app       | User Upload (Reserved for future use)                                                                                              |
| app/db            | SQLite database (if not using MySQL/MariaDB)                                                                                       |
| local             | Backwards compatible local certs/dashboards/modules (Users not supported for the moment)                                           |
