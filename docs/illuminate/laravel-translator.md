# Laravel Porting Guide #

This document tries to answer the question: How would I do (MunkiReport function) in (Laravel).

## site_helper ##

* **old**: `mr_view($file = '', $vars = '', $view_path = '') AKA view()`
* **new**: `view($name, $data)`

Not like for like - view() will return a Blade template instance instead of a KISSMVC View instance.

* **old**: `get_version()` - Gets the MunkiReport version
* **new**: *Suggested* `config('munkireport.version')` or similar to retrieve version from config/munkireport.php

* **old**: `uncaught_exception_handler()` - Used to be the exception handler for uncaught exceptions.
* **new**: Laravel automatically handles uncaught exceptions

* `custom_error()` - No known usages

* `munkireport_autoload($classname)` - Replaced by custom patch to composer's ClassLoader.php in
  [shims/ClassLoader_Modified.php](../../shims/ClassLoader_Modified.php).

* **old**: `function mr_url($url = '', $fullurl = false, $queryArray = [])`, renamed from `url()`.
* **new**: It depends on the type of URL. Usually `url('controller/method')`, but you can link paths to
  things like public assets (javascript/css) differently. You can also use `route()` as a more formal
  way of generating a URL that moves with changing route names.

* **old**: `function getRemoteAddress()` - Get the Remote IP (Taking proxy into account).
* **new**: Part of the $request object sent to every controller action.

* **old**: `function mr_secure_url($url = '')` - Tries to strip out URL fragments that could be dangerous
  I think? renamed from `secure_url()`.
* **new**: input sanitization automatically handled.

* **old**: `function mr_redirect($uri = '', $method = 'location', $http_response_code = 302)` - renamed from
  `redirect()`
* **new**: `redirect()`. Same function name, different parameters.

* **old**: `function post($what = '', $alt = '')` Get $_POST variable but dont error with unset vars.
* **new**: Just use `$request->input()` in the controller method.

* **old**: `function get_guid()` - Generate a UUID
* **new**: `Str::uuid();` - Same thing

* **old**: `function arrayToAssoc($array)` and `function assocToArray($array)` convert pairs, step by 2 into an
  associative array
* **new**: TODO not sure, something in [collections](https://laravel.com/docs/7.x/collections)

* **old**: `verifyCSRF()` verify CSRF token for POST/PUT requests.
* **new**: Laravel CSRF middleware. Actually, requires you to use their helper function in your views to work.

* **old**: `mr_storage_path($append = "")` renamed from `storage_path()`.
* **new**: `storage_path()`, pretty much the same thing.

* **old**: `truncate_string()` - Shorten string that would otherwise be too wide for the view.
* **new**: `Str::limit()` - Same thing.

* **old**: `random($length = 16)` - Create random string
* **new**: `Str::random()` - Same thing.

* **old**: `jsonError()` / `jsonView()` - Return a JSON style view instead of a rendered view.
* **new**: Please see [JSON Responses](https://laravel.com/docs/7.x/responses#json-responses)


### Alerts ###

Via `$GLOBALS['alerts']`, munkireport had registered global alert messages, like "flash" messages to appear
prominently on pages. The equivalent for that in laravel (if using session based authentication), is
`$request->session()->flash('status', 'Task was successful!');`, for example.

The affected items (to be removed), are:

* `$GLOBALS['alerts']`
* `function alert($msg, $type = "info")`
* `function error($msg, $i18n = '')`

### Authentication / Authorization ###

Most controllers will check authorization with a call to `$this->authorized($what)`, which is passed to
the base controller, which then calls the site_helper method.

`site_helper.php:authorized($what)`

There's almost no comparison to make between MunkiReport's built-in authorization mechanism and Laravel's.

These are really worlds apart, so you have to take on the new model entirely.

There are a few requirements to satisfy in Laravel that wont be out-of-the-box experiences:

* roles
* limit by machine group
    * authorized_for_serial()
    * get_filtered_groups()
* limit by business unit membership

### Singletons ###

site_helper uses the singleton pattern for both `getMrModuleObj()`, the module registry object, and
`getDashboard()`, the dashboard "loader" object.

Laravel provides a Dependency Injection container *specifically* for things like this. So called "services"
that should be available for use to any controller.

## configuration - app/config vs config/ ##

MunkiReport configuration is stored in [app/config](../../app/config), and the Laravel standard is just
[config](../../config).

Config variables are *similar*, but not the same. So special care has to be taken to provide aliases for the
older values.

Old Vs New Values

| Old Value           | New Value     | Old Default      | New Default              |
| :------------------ | :------------ | :--------------- | :----------------------- |
| CONNECTION_DRIVER   | DB_CONNECTION | 
| CONNECTION_DATABASE | DB_DATABASE   | app/db/db.sqlite | database/database.sqlite |


## TODO ##

* `site_helper.php:passphrase_to_group($passphrase)` - Should be a method on Eloquent model, not a global
  func.

