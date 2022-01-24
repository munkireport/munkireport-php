# v6to5 Compatibility Layer #

Because v6 was an (almost) complete rewrite, it could have broken compatibility with a substantial
number of modules.

Instead, a whole set of adapters were created which function as a "v5 compatibility layer", which
allows modules developed for version 5 to continue working as normal.

This document describes the compatibility layer and refers to some of its implementations.


## KISSMVC (Controller, View, Model) ##

A number of modules make use of the KISSMVC framework (from 2008-2009) 
which now has its website deleted. MunkiReport made some small additions to the framework after
that.

The KISSMVC framework declares un-namespaced classes: `\Controller`, `\Engine`, `\Model`, `\View`.
These were placed into the `\Compatibility\Kiss` namespace in order to satisfy composers PSR-4 class loading 
mechanism, and to avoid collision with the laravel `View` facade as well as the laravel `View` class.

But modules will still refer to the kissmvc view as `\View`, so to provide compatibility for accessing the
un-namespaced class, several files inside `/shims` alias the `\Compatibility\Kiss` classes back to their un-namespaced
counterparts using `class_alias()`.

The same approach was used to provide support for `\Model` and `\Controller`.

### Laravel Controllers with KISSMVC views ###

A forwards/backwards compatible View Engine has been registered with Laravel so that whenever the `view()` helper function
tries to render a `.php` file, it actually constructs an instance of Compatibility\Kiss\View underneath. The main difference
being that the Laravel captures the output buffer and returns it rather than piping it straight to stdout.

The main motivation for this is that stdout isn't testable by the PHPUnit HTTP Client, but the Response type is.

KISSMVC View templates live in the same directory structure as blade templates. The blade template is always preferred
by `view()`.

### Usage of $this->connectDB() ###

KISSMVC `\Controller` had a connectDB() method to connect to the database which is not in the base
controller class for Laravel. If you are migrating a KISSMVC controller, a trait has been added to
reintroduce that method, called `connectDBTrait`.

### v5 Module Views ###

Module views are automatically registered under the modules own namespace by extending the ViewFinder in
ModuleServiceProvider. This means that `machine::viewname` will actually try the v5 view path to find
viewname.php

## Usage of Capsule for ORM ##

During MunkiReport v5 some modules used the Eloquent Capsule which was the Laravel ORM engine that had
been lifted out of the framework as a standalone project.

A shim, called `FakeCapsuleManager` has been added and aliased to `Illuminate\Database\Capsule\Manager`
so that any modules using Capsule will be passed through to the real Eloquent ORM from the framework.

The capsule compatibility layer does not implement events.

## Un-namespaced models: \Machine_model and \Reportdata_model ##

The `shims/` directory contains class aliases that move these into the `\munkireport\models` namespace.

## MunkiReport Classloader replaced with Composer class loading ##

Composer provides its own autoloader and this is the primary autoloader for Laravel framework.

MunkiReport has always had its own implementation for autoloading model classes out of addon modules, if the
class ended in `_model`. This kind of scheme is directly incompatible with any configuration inside composer.

The workaround was to provide a customised ClassLoader.php which considers whether the class ends in `_model`
and use the old behaviour, otherwise it goes on with the modern loader.

Because ClassLoader.php isn't something you can normally override, a script is added whenever `composer dump-autoload`
and similar commands are executed with composer to overwrite the `ClassLoader.php` after it generates a new one.

## Support for clients that still request index.php?/path ##

New clients should never request index.php, but you can't always patch the older ones in time.

A Laravel Request Middleware, called RemoveIndexPhp, rewrites these requests so that the Laravel routing
thinks you have requested the path not including the filename.

## Core Configuration ##

Care has been taken to provide aliases from old environment variables to the new ones, although because
functionality *DOES* change, some will have been added or removed.

## Authentication ##

### adldap2-laravel doesnt support group whitelisting ###

The v5 implementation of AD authentication supported whitelisting security groups, which is not a 
feature of the adldap2-laravel package. The feature has been re-added via the `Rules` functionality.

## Authorization ##

