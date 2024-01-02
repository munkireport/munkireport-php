# List of guides by feature

## Admin Features

* **Authentication**
  * OpenID Connect (OIDC)
    * Azure AD Example
  * SAML
  * Local Authentication
  * LDAP(S) / Active Directory
* **Clients**
  * Python 3 Compatibility

## Developer Features

* **Modules**
  * MunkiReport v5 Backwards Compatibility Layer
  * Laravel/Provider Based Packages (Modules v2)
* **Widgets**
  * Blade Components


## Alpha (Preview) Features

* Global Search (via Laravel Scout)
* Vue Dashboards
* Notifications
* GraphQL API Endpoints


# New Features

## Laravel Framework

The Laravel Framework Replaces all custom MVC style code that was using the old KISSMVC framework.

### Laravel Fortify

Excerpt from [Fortify Documentation](https://laravel.com/docs/10.x/fortify):

    Fortify registers the routes and controllers needed to implement all of Laravel's authentication features, 
    including login, registration, password reset, email verification, and more.

### Laravel Jetstream

Jetstream builds on Fortify, as per the docs:

    provides the implementation for your application's login, 
    registration, email verification, two-factor authentication, session management, 
    API via Laravel Sanctum, and optional team management features.

### Laravel Sanctum

Sanctum provides the token-based REST API functionality

### Laravel Socialite

Socialite provides sign-on via many SSO providers.

### Inertia

Inertia provides a way to write component-based SPA style frontend in a way that resembles a normal Laravel PHP backend
application.

## Lighthouse

Lighthouse provides a GraphQL API for querying information about devices across modules.

## Swagger-PHP

Swagger PHP provides API documentation from PHP DocBlocks


