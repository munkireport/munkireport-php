# MunkiReport PHP on Azure App Service #

This template deploys MunkiReport PHP as an Azure App
Service [Web App for Containers](https://azure.microsoft.com/en-us/products/app-service/containers?activetab=pivot:deploytab).

Be aware, Azure App Service can cost significant amounts depending on the selection of SKU and features.

This template is developed with the lowest cost defaults, but this may still represent a cost of approx 50USD/month.

## Prerequisites ##

- An Azure Subscription
- Azure
  CLI. [You can find a Microsoft guide to install Azure CLI here](https://learn.microsoft.com/en-us/cli/azure/install-azure-cli-macos).
- Azure CLI Bicep Plugin (Automatically installed the first time you use it).

## Parameters ##

Module parameters are as follows:

| Parameter             | Type   | Default                                   | Description                                                                                                        |
|-----------------------|--------|-------------------------------------------|--------------------------------------------------------------------------------------------------------------------|
| webAppName            | string | MunkiReportPHP                            | The prefix given to all resources associated with the web app.                                                     |
| sku                   | string | B1                                        | The App Service plan Sku. Default is basic                                                                         |
| useScmDeployment      | bool   | false                                     | Use SCM/Git deployment. Defaults to false because the container is more reproduceable than the Azure Build service |
| containerImage        | string | `ghcr.io/munkireport/munkireport-php:wip` | Defaults to the WIP branch container (bleeding edge)                                                               |
| firewallIpCidr        | string | `Any`                                     | Allow connections to the Azure App from this IP CIDR only                                                          |
| database              | string | `none` (Uses SQLite or BYO)               | Provision a database as part of this deployment. Available: `none`, `mysql`                                        |
| databaseLogin         | string | `munkireport`                             | The database username                                                                                              |
| databaseLoginPassword | string | (Randomly Generated)                      | The database password                                                                                              |

## Deploy ##

### Using the Azure CLI ###

Create a resource group for the deployment. The deployment will assume that the resource group region selected is the
one you want to place resources into.

```shell
az group create --location australiaeast --resource-group MunkiReportPHPRG
```

Tweak the parameters as required.

Have a look at [parameters.example.json](./parameters.example.json) as an example.

Deploy the bicep template

```shell 
az deployment group create \
  --name MunkiReportPHP \
  --resource-group MunkiReportPHPRG \
  --template-file main.bicep \
  --parameters @parameters.json
```

## Remove ##

You can simply delete the resource group associated with the application.

## Generate Kudu Script for Azure App Service Build ##

Generally you will never need to do this unless you are developing on the **wip** branch and you need to deploy
straight from git.

The App Build Service can break unexpectedly because it's not a tested/supported use-case in MunkiReport PHP.

```shell
kuduscript --php -o deploy/azure/appsvc -y
Generating deployment script for PHP Web Site
Generated deployment script files
```



