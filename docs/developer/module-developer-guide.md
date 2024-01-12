# Module Development Guide (v6) #

This development guide is intended to show you how to create a module for Version 6 and above.
When appropriate, the upgrade path from v5 will be described, but you don't necessarily need to know how v5 modules
were created.

## Package Structure ##

In both v5 and v6, modules have a specific structure which allows MunkiReport to discover their features.

While v5 modules are compatible with v6, you might choose to create or migrate to the new structure because you need
some new framework features.

*NOTE:* Because we won't go deep into package development, you can refer to [Laravel Package Development](https://laravel.com/docs/10.x/packages) if you
  need low level detail.

If you are creating from scratch, GitHub user "Spatie" has provided a [Laravel Package Skeleton](https://github.com/spatie/package-skeleton-laravel)
that you can use for the directory structure.

### Namespaces and Naming ###

Some earlier versions of v5 modules did not have a namespace for their classes. Model classes like `\Caching_model` were
part of the root namespace and MunkiReport "guessed" the location of the `caching_model.php` when it needed to based on
a filesystem search. 

Due to the fact that Laravel/Composer use PHP autoloading based on namespaces, we need to define a namespace for our package.

The namespace comes from the composer configuration (`composer.json`). Inside you can find a section which hints to the
autoloader what our module namespace will be, for example:

```json 
{
  "autoload": {
    "psr-4": {
      "Munkireport\\Osquery\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Munkireport\\Tests\\Osquery\\": "tests/"
    }
  }
}
```

This means that the autoloader will use the [PHP PSR-4 Standard](https://www.php-fig.org/psr/psr-4/) to guess exactly
what file would be required for a given name.

In this case the class `Munkireport\Osquery\Blah` would be assumed to be loaded from `src/Blah.php`.

It is strongly suggested to use the `Munkireport\` prefix, to distinguish Munkireport modules from foundation or 3rd
party utilities that have no relation to the module system.

### Discovery ###

In v5, there were two ways to discover a MunkiReport Module and its features:

* The very old way, which was to just look for a directory containing a file called `Module_model.php`, and assume the parent directory was a module.
* A developer provided `provides.php` or `provides.yml` file.

While you can still use this method, there are sections of the `composer.json` provided for discovery which mimic the 
way that [Laravel Does Package Discovery](https://laravel.com/docs/10.x/packages#package-discovery)

You will need to use the `composer.json` style if you want to write your own [Laravel Facades](https://laravel.com/docs/10.x/facades)
or hook into system events using a [Laravel Service Provider](https://laravel.com/docs/10.x/providers). Basically any 
functionality that exists in v6 or newer will require this style. v5 modules will not break if they do not have this
configuration.

#### Service Providers ####

To understand what Service Providers add in terms of functionality, lets have a look at what v5 modules could do in
terms of features:

* Provide migrations and models to perform CRUD operations on the database.
* Provide a processor which would process data submitted by client scripts.
* Provide client scripts for installation/uninstallation.
* Provide client-side localization of strings.
* Provide the core views functionality: Dashboards, Reports, Listings, Widgets, Detail Widgets and Admin Pages.

Service Providers hook into the framework at a lower layer. They allow you to extend things that don't fall into the
categories that we define for modules above. Service Providers can register services and event callbacks that change how the
entire system works, rather than adding a page (usually at a predefined URL).

For example: say you want to retrieve some information about a user when they log in and then send some data to a
different API. You can listen for auth events and get all the information associated with a User when this happens.

Another simple example is that you may register URLs at whatever path you want. You do not have to follow the v5 
convention of `/module/controller/action`. If you write an OSQuery module, you can just make the UI available at `/osquery`.

Here's an example of a standard/minimum service provider for a v6 module. I'll annotate all the code, so you know what
is happening:

```php
namespace Munkireport\Osquery\Providers;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /// Here i'm setting up the module base directory which is relative to this ModuleServiceProvider.php
        $packageDir = realpath(__DIR__.'/../../');

        /// The call to `publishes` means that we are providing a template which will be "published" into the
        /// users installation. In this case, we provide a default config for osquery that people may modify.
        $this->publishes([
            $packageDir . '/config/osquery.php' => config_path('osquery.php'),
        ], 'config');
        
        /// This instructs the migration framework to load database migrations from this directory, relative to the
        /// package.
        $this->loadMigrationsFrom($packageDir . '/database/migrations');
        
        /// Routes are new to v6, and define which URLs will call which controller actions, more on routes later.
        $this->loadRoutesFrom($packageDir . '/routes/osquery.php');
        
        /// PHP Based translations are also new to v6, They can be used for server-side translation if required. They
        /// are optional.
        $this->loadTranslationsFrom($packageDir . '/resources/lang', 'osquery');
        
        /// This method instructs Laravel to load views from this path whenever the namespace matches the 2nd argument.
        /// You would refer to some view inside osquery called 'home.php' by using the namespaced view name of 
        /// view('osquery::home')
        $this->loadViewsFrom($packageDir . '/resources/views', 'osquery');

        /// If you need your module to have CLI commands, you can register them here. They might provide the admin with
        /// some quick way of administering the system.
        if ($this->app->runningInConsole()) {
//            $this->commands([
//            ]);
        }

        /// This line is not required in the ModuleServiceProvider but serves as an example of the kind of functionality
        /// you can use in v6. In this example, the Auth::extend() method is used to create a brand new way of authenticating
        /// to MunkiReport by using an OSQuery Node Key.
        Auth::extend('nodekey', function ($app, $name, array $config) {
            return new NodeKeyGuard($name, new EloquentNodeProvider(Node::class), request());
        });

    }
```

### URL Routing ###

In v5, your modules would add new URLs depending on what you wrote in the `provides.yml|php`.

* Your dashboards could show up at `/show/dashboard/{name}`.
* Your reports could show up at `/show/report/{module}/{name}`.
* Your listings could show up at `/show/listing/{module}/{name}`.
* You could add some arbitrary admin_pages such as `/module/{module}/admin` if you required some admin page.

The URL depended on the names and types of things that you provided. If you invented some new type of page then it
must have fit under the dashboard, report, listing or admin category.

If you provided a Listing it had to support only the features that all listings supported.

All of these things still exist, but the options increase in v6.

In v6, you provide your own routes, using the ServiceProvider method `$this->loadRoutesFrom()`.
A routes.php file (or whatever you want to name it) defines the URLs and how they map to controllers.

They also define which middleware is applied to which controller. This is important for things like defining which
routes require a logged-in user, and which dont. This guide won't go deeply into detail about middleware in routes,
so please refer to [Laravel Routes](https://laravel.com/docs/10.x/routing) for more information.

#### Example: Creating an OSQuery Admin Page ####

In this example I'll demonstrate how we set up an admin page for updating Queries in OSQuery.

As above, we tell Laravel that our module will provide extra routes via `$this->loadRoutesFrom($packageDir . '/routes/osquery.php');`.

Inside the file `/routes/osquery.php`, I've defined the admin page route (Lines are cut for clarity):

```php 
Route::group(['prefix' => 'osquery'], function () {

    // ...
    
    Route::middleware(['web', 'auth'])->group(function () {

        // ... other routes ...
    
        Route::get('/queries', 'Munkireport\Osquery\Http\Controllers\QueriesController@index')
            ->name('osquery.admin.queries');

    });
});
```

The first method `Route::group(['prefix' => 'osquery']` instructs Laravel that all the routes inside this function
will have the prefix `/osquery` so that we don't have to write that for every line.

The second nested function `Route::middleware['web', 'auth'])` instructs Laravel to use both the *web* and *auth* groups
of middleware for every request. Don't worry if you aren't sure what a middleware group means yet. In this case it just
means that when someone visits our site, Laravel will check the request and walk through a list of items such as: is the person
authenticated, did we already start a session, is the user asking for a json content type etc.

The third function `Route::get('/queries'...)` connects a URL (`/osquery/queries`) to a controller plus its method 
*index()* (`Munkireport\Osquery\Http\Controllers\QueriesController@index`). Additionally we give this route a short
name called `osquery.admin.queries`, which we can refer to elsewhere in the code. This would just allow us to move the URL
without changing everything that would be affected.

Now we just need to provide the content of `Munkireport\Osquery\Http\Controllers\QueriesController`, which resides in
`src/Http/Controllers/QueriesController.php`.

## Views ##

In MunkiReport v5, view files are simple .php files. This still works, but the function `view()` has been replaced by
`mr_view()` if you want view files to be rendered this way (include the .php and output directly).

In Laravel there are two approaches to the UI side of things: 

* You can use the Laravel Blade templating system for performing server side rendering. This is most similar to the view.php files.
* You can use InertiaJS to provide a view which is entirely rendered on the Client Side using VueJS and others.

### Blade Views ###

As always, consult the official documentation on [Blade Views](https://laravel.com/docs/10.x/blade) for a deeper understanding.

Blade is a templating system. Similar to all other HTML templating systems it works by rendering HTML and brings its own
pseudo-PHP language for introducing control structures and other code-like constructs.

Using a templating system like this can often be more concise than raw PHP alone. It allows for patterns like inheritance
or providing reusable functions for formatting strings/dates.

Most modern applications use some kind of isomorphic rendering of a frontend framework, but HTML templates are still a reasonable
choice for simplicity's sake.

Blade Views have also been used to provide a new version of custom widgets, using the [Blade Components](https://laravel.com/docs/10.x/blade#components)
functionality. This allows each widget to access all the backend code that is part of the core project.

### Blade Widgets ###

If you use a v6 module format, you have the option of registering widgets in your module service provider.

Widgets are implemented as [Blade Components](https://laravel.com/docs/10.x/blade#components).
By default, Laravel will understand that the `views/components` directory of your module contains
Blade component templates.

In your service provider, you can add the following to your `boot()` method:

```injectablephp
// to use in views: <x-hardware::widget.displays>
Blade::componentNamespace('Munkireport\\Hardware\\Components', 'hardware');

$this->widgets([
    'helloworld' => [
        'version' => 6,
        'component' => 'hardware::widget.helloworld',
    ]
]);
```

The component namespace describes a prefix that will be shared by all widgets in your module.
It is good practice to namespace your components so that they will not clash with other modules.

The function `$this->widgets()` registers each widget that has the `'version' => 6` key/value so that it may be
dynamically loaded in any context that uses blade components (Like the dashboard).

## Navigation (for Listings, Reports and Admin Pages) ##

In v5, you could provide the `view` property in your `provides.php|yml` to indicate that you wanted to render that
view name for your listing/report.

In v6, since you don't have to arrive at the `/show/listing` controller if you don't want to, you have access to some
new attributes which control the navigation menu for your item(s):

* `route` will use the Laravel *route()* helper function to look up your listing by its route name, *OR*
* `url` will specify an absolute URL to use when clicking on the navigation item.

## REST API ##

In v5, there was no API documentation or standard.

The rest of this section will be about how to provide an API for your module in v6.

As a convention, we ask that you first register a route underneath `/api/v6/{module}` for your API. 
Your route should be registered with the `auth:sanctum` middleware which will allow people to access it using their
login *AND* using their API keys.

Example route:

```php
Route::group(['prefix' => 'v6', 'namespace' => 'Api', 'middleware' => 'auth:sanctum'], function () {
    Route::apiResource('users', 'UsersController');
});
```

In this route we are using the `::apiResource()` method, which means that we assume the REST API covers: get, create, update, list,
delete. Laravel assumes your controller will provide eg. `index()` for the list method and so on. 
Refer to [API Resource Routes](https://laravel.com/docs/10.x/controllers#api-resource-routes) for more information.

If your API does not exactly match the shape of your database model, you can use [Eloquent Resources](https://laravel.com/docs/10.x/eloquent-resources#main-content)
to map between the API data and the database model. This could also be required for example if you are using different
date representations than what is natively accepted.

### Documentation ###

API Documentation is automatically compiled using l5-swagger/swagger-php based on Annotations or DocBlocks provided.

(To be explained in greater detail)


