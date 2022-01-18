# Laravel Migration Notes #

## Overview ##

The framework migration has started with the main goal of swapping out the KISSMVC Engine, Controller, Model and
View objects with their Laravel equivalents.

All effort has been given to maintain backwards compatibility with modules written against the KISSMVC engine,
however some of the Laravel functions supersede functions that already exist within MunkiReport.

## Process ##

### Copy empty Laravel Project on top of MunkiReport ###

The first step was to take an empty laravel project and carefully copy items into the MunkiReport directory
so that we ended up with a combination of both structures.

In places where naming clashes, mostly within bootstrap/ and public/, the old versions had a kiss prefix added
to denote that they were reliant on the KISSMVC framework we are removing.

### Extracting KISSMVC from the Global Namespace ###

There is a lot of baggage in the MunkiReport repo which lives in the global \ namespace such as the KISSMVC
classes `\View, \Model, \Controller, and \Engine`.

The first thing that was done was to move all of these classes under the `MR\Kiss` namespace so that you could
safely use the similarly named classes eg. `\Illuminate\View` without unexpected name clashes.

To allow legacy modules to run, for the moment we alias all of those `MR\Kiss` classes back to their global
counterparts via `class_alias()` i.e. for `MR\Kiss\View` we alias back to `\View`, so that when a module which
has not been upgraded, decides to instantiate View via `new View()`, it still loads the old implementation.

This is the same for Model, Controller etc.

The class aliases/renames are preserved inside the [shims/](shims/) directory/

### Removing the munkireport_autoload() autoloader ###

Composer provides its own autoloader and this is the primary autoloader for Laravel framework.

MunkiReport has always had its own implementation for autoloading model classes out of addon modules, if the
class ended in `_model`. This kind of scheme is directly incompatible with any configuration inside composer.

The workaround was to provide a customised ClassLoader.php which considers whether the class ends in `_model`
and use the old behaviour, otherwise it goes on with the modern loader.

Because ClassLoader.php isn't something you can normally override, a script is added whenever `composer dump-autoload` 
and similar commands are executed with composer to overwrite the `ClassLoader.php` after it generates a new one.

### Moving static includes to autoloader ###

All of the files inside [app/helpers](app/helpers) contain utility functions which are normally loaded by the
[bootstrap/app.php](bootstrap/app.php) file. When we add Laravel to the mix, we have to remove the old app file
and replace it with Laravel's implementation, which means that the include()'s and import()'s within this file
are lost.

To fix this, we supply composer with a list of files to statically include every single time. This way we just
need to use composers autoloader and we automatically get everything in app/helpers.

### Core Controllers ###

Core Controllers like /show, /datatables, /locale etc are copy pasted and then made to inherit Laravel Controller
instead of KISSMVC controller. If the controller relies on functionality from KISSMVC then the function is patched
to return the same data in whatever the Laravel convention is.

### Routes ###

MunkiReport takes the old-style MVC framework approach of mapping directly path fragments to routes, I.E, you
always see a pattern where /<first>/<second> is equally mapped to a file or class called FirstController, and 
a method called Second().

With Laravel, you have to be explicit about routing, which means it does not make any assumptions at all about
which file or method is used for which URL. This means all of the implied routes had to be mapped into the
[routes/web.php](routes/web.php) file explicitly.

Also, with functions that used to just take the whole URL and split it into constituent parts, this method
kind of works against Laravel's stance on pattern matching URL parameters, so some of the methods that used
to break URL fragments now are captured by Laravel Route Parameters.

### Database Models ###

As most of the models were already migrated towards the Illuminate\Database capsule, they are fully compatible
with the built-in Eloquent ORM within Laravel. The Capsule is still present in case there are classes that
manually instantiate the Capsule via `new Capsule;`.

### Configuration ###

MunkiReport configuration mimics the structure of Laravel but is not 100% compatible with the configuration
naming of Laravel.

This is going to require some variable aliasing/renaming in order to support the old-style configuration while
opening up the laravel configuration.

Also, we will have to preserve two config directories in parallel - app/config and config/

### Authentication and Authorization ###

This is (probably) the most breaking change in terms of not being able to preserve backwards compatibility.

Laravel comes with its own model for authenticating and authorizing requests. MunkiReport had a simple session
globals based model which really really does not gel with the framework.

As Laravel will use Middleware to intercept and decide about authorized requests, this is going to be the hardest
one to maintain backwards compatibility on.

For simplicity we have created Gates which match exactly the style of authz done in the controller authorized() method.

### Views ###

MR\Kiss\View is still available for complete backwards compatibility.

A forwards/backwards compatible View Engine has been registered with Laravel so that whenever the `view()` helper function
tries to render a `.php` file, it actually constructs an instance of MR\Kiss\View underneath. The main difference
being that the Laravel captures the output buffer and returns it rather than piping it straight to stdout.

The main motivation for this is that stdout isn't testable by the PHPUnit HTTP Client, but the Response type is.

KISSMVC View templates live in the same directory structure as blade templates. The blade template is always preferred
by `view()`.

Module views are automatically registered under the modules own namespace by extending the ViewFinder in 
ModuleServiceProvider. This means that `machine::viewname` will actually try the v5 view path to find
viewname.php

### Dashboards ###

The dashboards class is only slightly refactored to support rendering the dashboard outside of Kiss\View using the
Blade templating engine. This is currently only enabled in the default dashboard.

### Widgets ###

The widgets class is also only slightly touched to support rendering outside of Kiss\View.

Some .yaml style widgets have been converted to use [Blade Dynamic Components](https://laravel.com/docs/8.x/blade#dynamic-components)


