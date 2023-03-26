# MunkiReport PHP on Azure App Service #

## Prerequisites ##

- Az CLI with bicep installed

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
