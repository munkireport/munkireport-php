# SAML2 Authentication #


## Configuration ##

For each Identity Provider (IdP) you use (and that may just be one), you have to define a short name for the provider.
In this example I'll use the Azure AD SAML via app registration, but it's not important to know how SAML works in Azure
AD to configure this. Wherever you see `azuread` you can replace it with whatever provider you use.

### 1. Enable SAML2 as an authentication type in MunkiReport

You can do this by editing your *.env* file, or by providing environment variables like so:

```dotenv
AUTH_METHODS="SAML,LOCAL"  # Keep LOCAL if you need to have a local administrator when SAML is unavailable.
```

### 1. Define the IdP name in config/saml2_settings.php ###

Pick a short name that makes sense. It has to be part of a URL, so try not to pick anything that would require
special characters, and especially dont pick ampersand (&) or question mark(?) as part of the name.

In my example I am changing from the default:

```php
'idpNames' => ['test'],
```

to

```php
'idpNames' => ['azuread'],
```

This name will be referred to when we set all the detailed settings later on.

### 2. Configure your sp details via the environment/.env ###

Now that I have added the IdP named "azuread", some new variables are available to the environment to specify all
the details. Not all IdP's require all the possible settings to get going, so you will have to read up. For Azure AD
this was enough:
```dotenv

# SAML2 Identity Provider (IdP) Login URL
SAML2_azuread_IDP_SSO_URL="https://login.microsoftonline.com/<uuid>/saml2"

# SAML 2 Identity Provider Certificate
# As a Base64 Encoded DER X.509 Certificate
SAML2_azuread_IDP_x509="MIIC8DC...."

# SAML 2 Identity Provider (IdP) Entity ID, but really usually referred to as "Issuer"
SAML2_azuread_IDP_ENTITYID=""
```

### 3. Tell your provider which URLs and Entity IDs they should expect

This is part of the SAML security model (as well as verifying the signature of the request).
You have to give them your SAML SP Entity ID and Assertion Consumer Service (ACS) URL, thats the URL that they
will return to after logging in which will process the user information.

#### Entity ID ####

If you didn't configure `SAML2_azuread_SP_ENTITYID`, the Entity ID will be generated for you, and is the URL to the
SP metadata, which is something like this:

    https://munkireport/saml2/azuread/metadata

Replace `azuread` for whatever you configured as the IdP short name.

#### Assertion Consumer Service (ACS) URL ####

This URL is also generated based on the short name shown above:

    https://munkireport/saml2/azuread/acs

#### Sign on / Login URL ####

The IdP might also want to know where the redirects are coming from, which will be:

    https://munkireport/saml2/azuread/login

### 4. Additional Claims Mapping ###

Claims are verified information about the user logging in. Every SAML2 IdP might have specific and different names
for the same information, so sometimes you have to rename those to be consistent.

At the moment we give you one optional claim, the user "display name", which will be shown in the user profile menu.
You can map the SAML2 Attribute using the following .env configuration:

```dotenv
SAML2_azuread_CLAIM_DISPLAYNAME="http://schemas.microsoft.com/identity/claims/displayname"
```

## More Information ##

The SAML integration uses [aacotroneo/laravel-saml2](https://github.com/aacotroneo/laravel-saml2) which is really
the [OneLogin PHP SAML toolkit](https://github.com/onelogin/php-saml) under the hood, which is what nearly everybody
uses on PHP.

