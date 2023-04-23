# MunkiReport v6 API #

API Documentation is now provided by L5 Swagger/Swagger UI which you may access on your installation at the path `/api/documentation`

To re-generate the OpenAPI/Swagger Documentation, you may run:

```shell 
php please l5-swagger:generate
```




```shell 
$ curl --cookie "munkireport_60_session=<sessiontoken>" \
  -H "Accept: application/json;charset=utf8" \
  https://localhost/module/disk_report/get_stats

```
