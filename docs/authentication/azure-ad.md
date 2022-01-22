# Authentication with Azure AD #

This guide describes setting up MunkiReport-PHP with Azure AD for single sign-on.

## Step 1 - App Registration ##

Prerequisites:

- Must be logged in to the Azure AD portal.
- Must have access to see and create "App Registrations".

1. From the Azure AD Portal, navigate to the **App Registrations** section.
2. Click on **New registration** to register a new Application.
3. Enter the following details:

    - **name:** MunkiReport-PHP
    - **supported account types:** Accounts in this organizational directory only.
    - **redirect application type:** Choose "Web".
    - **redirect uri:** (The URL to your MunkiReport installation, with the path `/oauth2/redirect/azure` appended), eg.
        `https://munkireport.local/oauth2/redirect/azure` if you access munkireport at `https://munkireport.local`.
   
    _You should now be redirected to your newly registered "MunkiReport-PHP" application, if not: you can access the registration
    detail at any time by visiting the **App Registrations** page, and clicking on the "MunkiReport-PHP" registration._

4. In the registration overview you will need to record a couple of details which will become MunkiReport configuration:
   - **Application (client) ID**: This value will be added to `AZURE_CLIENT_ID`.
   - **Directory (tenant) ID**: This value will be added to `AZURE_TENANT_ID`.

5. Create a new secret for the application to use. From the app registration overview, navigate to **Certificates & secrets**.
6. Choose the **Client secrets** tab.
7. Click the button **New client secret**.
8. Describe the secret, it's not important for this application to provide a detailed description.
9. Select an expiry length.
10. Click **Add**, you will be shown the secret value **ONLY ONCE**, so make a copy of it now.

## Step 2 - MunkiReport PHP Configuration ##

Prerequisites:

- MunkiReport PHP should already be running and configured with an SSL certificate. Azure AD will complain if you are
  trying to perform a secure auth code login over a connection that could be captured.

1. Now you will need the **Application (client) ID** and **Directory (tenant) ID** from item 4 in the app registration step,
   and the **Client secret** from item 10.
2. Depending on how you configure MunkiReport (`.env` file, environment variables in the web server configuration, or docker `-e` variables),
   you will need to provide the following (the angle brackets are not literally required):

```
    AZURE_CLIENT_ID=<Application (client) ID>
    AZURE_TENANT_ID=<Directory (tenant) ID>
    AZURE_REDIRECT_URI=/oauth2/callback/azure
    AZURE_CLIENT_SECRET=<Client Secret>
```

## Step 3 - Test ##

You should now see an extra sign-in button on the MunkiReport-PHP login page which takes you to Azure AD to authenticate.

The first time you sign-in, you may see a consent screen, asking you to consent that MunkiReport-PHP can read your information.

This is normal and necessary for MunkiReport-PHP to authenticate and fill user details as part of the sign-in process. You can
provide global admin consent to prevent other users from seeing this screen. Global Admin consent is outside of the scope of this
guide.

## In depth ##

### Token Claims ###

By default, the Microsoft Azure AD Socialite Provider only processes the token claims for `id`, `email`, and `name`.

It will not retrieve any other claims listed in the token by default.

Support for Azure AD App Roles and/or AAD Group ID membership may be added in a future version.
