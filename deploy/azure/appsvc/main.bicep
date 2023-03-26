

@description('Base name of the resource such as web app name and app service plan ')
@minLength(2)
param webAppName string = 'MunkiReportPHP'

@description('The SKU of App Service Plan ')
param sku string = 'B1'

@description('The runtime stack, which should always be PHP')
param linuxFxVersion string = 'php|8.2'

@description('Repository URL for deployment')
param repositoryUrl string = 'https://github.com/mosen/munkireport-php'

@description('Repository branch to deploy')
param branch string = 'wip'

@description('Location for all resources.')
param location string = resourceGroup().location

@description('Database administrator login name')
@minLength(1)
param administratorLogin string

@description('Database administrator password')
@minLength(8)
@secure()
param administratorLoginPassword string

var appServiceName = '${webAppName}-webapp'
var appServicePlanName = '${webAppName}-asp'
var databaseName = '${webAppName}-db'

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
      appSettings: [
        {
          name: 'DB_HOST',
          value: ''
        },
        {
          name: 'DB_DATABASE',
          value: 'munkireport'
        },
        {
          name: 'DB_USERNAME',
          value: 'munkireport'
        },
        {
          name: 'DB_PASSWORD',
          value: ''
        },
        {
          name: 'APP_DEBUG',
          value: 'false'
        }
      ]
      linuxFxVersion: linuxFxVersion
      ftpsState: 'FtpsOnly'
    }
    httpsOnly: true
  }
  identity: {
    type: 'SystemAssigned'
  }
}

resource sourceControls 'Microsoft.Web/sites/sourcecontrols@2021-01-01' = {
  name: '${appService.name}/web'
  properties: {
    repoUrl: repositoryUrl
    branch: branch
    isManualIntegration: true
  }
}

