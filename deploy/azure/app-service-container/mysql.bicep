@description('Location for all resources.')
param location string = resourceGroup().location

@description('Server Name for Azure database for MySQL')
param serverName string

@description('Database administrator login name')
@minLength(1)
param administratorLogin string = 'munkireport'

@description('Database administrator password')
@minLength(8)
@secure()
param administratorLoginPassword string

@description('Azure database for MySQL sku name ')
param skuName string = 'Standard_B1s'

@description('Azure database for MySQL pricing tier')
@allowed([
  'GeneralPurpose'
  'MemoryOptimized'
  'Burstable'
])
param SkuTier string = 'Burstable'

resource mysqlDbIdentity 'Microsoft.ManagedIdentity/userAssignedIdentities@2018-11-30' = {
  name: serverName
  location: location
}


resource mysqlDbServer 'Microsoft.DBforMySQL/flexibleServers@2021-05-01' = {
  name: serverName
  location: location
  sku: {
    name: skuName
    tier: SkuTier
  }
  identity: {
    type: 'UserAssigned'
    userAssignedIdentities: {
      '${mysqlDbIdentity.id}': {}
    }
  }
  properties: {
    administratorLogin: administratorLogin
    administratorLoginPassword: administratorLoginPassword
    createMode: 'Default'
    highAvailability: {
      mode: 'Disabled'
    }
    version: '5.7'
  }
}

resource munkireportDb 'Microsoft.DBforMySQL/flexibleServers/databases@2021-12-01-preview' = {
  name: 'munkireport'
  parent: mysqlDbServer
  properties: {
    charset: 'utf8'
    collation: 'utf8_general_ci'
  }
}

resource munkireportDbAllowAzure 'Microsoft.DBforMySQL/flexibleServers/firewallRules@2021-12-01-preview' = {
  name: 'AllowAllAzureServicesAndResourcesWithinAzureIps_bicep'
  parent: mysqlDbServer
  properties: {
    startIpAddress: '0.0.0.0'
    endIpAddress: '0.0.0.0'
  }
}

output hostname string = mysqlDbServer.properties.fullyQualifiedDomainName

