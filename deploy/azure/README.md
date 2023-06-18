# MunkiReport PHP on Azure #

The following deployment models are given as examples, they are not guaranteed to be up-to-date with the cloud tooling:

- [MunkiReport PHP on Azure App Service (Web App for Containers)](./appsvc/README.md)
- TODO: [MunkiReport PHP on Azure Container Instances](./container-instance/README.md)
- TODO: [MunkiReport PHP on Azure Container Apps](./container-apps/README.md)

*NOTE*: AKS Deployment is not in scope here and will be categorised as vendor-independent Kubernetes instructions when/if
    they are written.

## Laravel Support for Azure Services ##

Some functions of Laravel may use Azure Services directly if you want to externalise some functions for reliability
or integration reasons:

- [Laravel Queues](https://laravel.com/docs/10.x/queues) may use [Azure Cache for Redis](https://azure.microsoft.com/en-us/products/cache/) (more expensive), or
  you can use Azure Storage Queues with [squigg/azure-queue-laravel](https://packagist.org/packages/squigg/azure-queue-laravel) (less expensive).

