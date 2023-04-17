# MunkiReport PHP on Azure App Service #

## Prerequisites ##

- Az CLI with bicep installed
- The container must trust the MySQL Flexible Server CA via setting CONNECTION_SSL_CA

## Deploy ##

Create a resource group for the deployment in the region where you want to deploy eg.

```shell
az group create --location australiaeast --resource-group MunkiReportPHPRG
```

Tweak the parameters as required.

Deploy the bicep template (using the Az CLI in this example)

```shell 

az deployment group create \
  --name MunkiReportPHP \
  --resource-group MunkiReportPHPRG \
  --template-file main.bicep \
  --parameters @parameters.json
```


## Remove ##

```shell 

az deployment group delete \
  --name MunkiReportPHP \
  --resource-group MunkiReportPHPRG
```

## Generate Kudu Script for Azure App Service Build ##

```shell

kuduscript --php -o deploy/azure/appsvc -y
Generating deployment script for PHP Web Site
Generated deployment script files
```
