@description('Base name of the resource such as web app name and app service plan ')
@minLength(2)
param webAppName string = 'MunkiReportPHP'

@description('The SKU of App Service Plan ')
param sku string = 'B1'

@description('The container image to run')
param containerImage string = 'ghcr.io/munkireport/munkireport-php:wip'

@description('Location for all resources.')
param location string = resourceGroup().location

@description('Allow connections to the app from this CIDR by default')
param firewallIpCidr string = 'Any'

@description('Database instance to provision')
@allowed([
  'mysql' // MySQL Flexible Server
  'none'
])
param database string = 'none'

@description('Database administrator login name')
@minLength(1)
param databaseLogin string = 'munkireport'

@description('Database administrator password')
@minLength(8)
@secure()
param databaseLoginPassword string = newGuid()

//var linuxFxVersion = 'DOCKER|${containerImage}'
var linuxFxVersion = 'PHP|8.1'

var appServiceName = '${webAppName}-webapp-${uniqueString(resourceGroup().id)}'
var appServicePlanName = '${webAppName}-asp-${uniqueString(resourceGroup().id)}'
var storageAccountName = toLower('${substring(webAppName, 0, 10)}${uniqueString(resourceGroup().id)}')
var databaseName = toLower('${webAppName}-database-${uniqueString(resourceGroup().id)}')

resource dataStorage 'Microsoft.Storage/storageAccounts@2022-09-01' = {
  name: storageAccountName
  location: location
  sku: {
    name: 'Standard_LRS'
  }
  kind: 'StorageV2'
  properties: {
    allowSharedKeyAccess: false
  }
}

resource dataStorageFiles 'Microsoft.Storage/storageAccounts/fileServices@2022-09-01' = {
  name: 'default'
  parent: dataStorage
}

resource dataShare 'Microsoft.Storage/storageAccounts/fileServices/shares@2022-09-01' = {
  name: 'data'
  parent: dataStorageFiles
  properties: {
    enabledProtocols: 'SMB'
  }
}


module mysql 'mysql.bicep' = if (database == 'mysql') {
  name: '${webAppName}-mysql'
  params: {
    location: location
    serverName: databaseName
    administratorLogin: databaseLogin
    administratorLoginPassword: databaseLoginPassword
  }
}

resource appServicePlan 'Microsoft.Web/serverfarms@2022-03-01' = {
  name: appServicePlanName
  location: location
  sku: {
    name: sku
  }
  kind: 'linux'
  properties: {
    reserved: true
  }
}

resource appService 'Microsoft.Web/sites@2022-03-01' = {
  name: appServiceName
  location: location
  kind: 'app'
  properties: {
    serverFarmId: appServicePlan.id
    siteConfig: {
      // appCommandLine does not seem to be run if you use a custom container image
      // appCommandLine: 'mkdir -p /home/db && touch /home/db/db.sqlite'
      appSettings: [
//        {
//          name: 'DB_CONNECTION'
//          value: 'mysql'
//        }
//        {
//          name: 'DB_HOST'
//          value: mysql.outputs.hostname
//        }
//        {
//          name: 'DB_DATABASE'
//          value: 'munkireport'
//        }
//        {
//          name: 'DB_USERNAME'
//          value: databaseLogin
//        }
//        {
//          name: 'DB_PASSWORD'
//          value: databaseLoginPassword
//        }
//        {
//          name: 'APP_DEBUG'
//          value: 'false'
//        }
        {
          // TODO: Kudu/App Service provides an environment variable (WEBSITE_HOSTNAME) with the current site URL which should be determined automatically
          name: 'APP_URL'
          value: 'https://${appServiceName}.azurewebsites.net'
        }
        {
          // Only files written to /home will be persisted across App Service restarts by default
          // See: https://learn.microsoft.com/en-us/azure/app-service/configure-custom-container?tabs=debian&pivots=container-linux#use-persistent-shared-storage
          name: 'STORAGE_PATH'
          value: '/home/storage'
        }
        {
          // Kudu (App Service) will pick up this app setting and map the ports accordingly for incoming requests
          name: 'WEBSITES_PORT'
          value: '8080'
        }
        {
          // If using MySQL Flexible Server for Azure, you need to trust the CA which issues the connection certificate
          name: 'CONNECTION_SSL_CA'
          value: '/data/certs/DigiCertGlobalRootCA.crt.pem'
        }
        // If you want to do local git deployment from a custom branch, use this app setting
        {
          name: 'DEPLOYMENT_BRANCH'
          value: 'wip'
        }
      ]
      //azureStorageAccounts: {
      //  data: {
      //    type: 'AzureFiles'
      //    accountName: dataStorage.name
      //    shareName: dataShare.name
      //    mountPath: '/data'
      //  }
      //}
      http20Enabled: true
      scmType: 'LocalGit'
      linuxFxVersion: linuxFxVersion
      ftpsState: 'Disabled'
      ipSecurityRestrictions: [
        {
          ipAddress: firewallIpCidr
          action: 'Allow'
          tag: 'Default'
          priority: 100
          name: 'MyClientIP'
          description: 'Allow only the creators IP Address by default'
        }
        {
          ipAddress: 'Any'
          action: 'Deny'
          priority: 2147483647
          name: 'Deny all'
          description: 'Deny all access'
        }
      ]
    }
    httpsOnly: true
  }
  identity: {
    type: 'SystemAssigned'
  }
}



output url string = 'https://${appService.properties.defaultHostName}/'
