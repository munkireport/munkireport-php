# MunkiReport PHP on Azure App Service #

This template provides an Azure App Service deployment of MunkiReport-PHP on a least-cost design philosophy.
Not much in Azure is truly free, so please consider the potential cost before deployment.

## Prerequisites ##

- Azure CLI. [You can find a Microsoft guide to install Azure CLI here](https://learn.microsoft.com/en-us/cli/azure/install-azure-cli-macos).
- Azure CLI Bicep Plugin (Automatically installed the first time you use it).

## Deploy ##

Create a resource group for the deployment. The deployment will assume that the resource group region selected is the
one you want to place resources into.

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

## Parameters ##

