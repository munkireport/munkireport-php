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

## GraphQL ##

Some core models are available for query at the GraphQL endpoint `/graphql` provided by [Lighthouse](https://lighthouse-php.com/).

Additionally, if you installed the dev requirements, there is a playground app automatically installed for you to play
around with GraphQL queries at `/graphql-playground`.


