# Adding Illuminate/View to MunkiReport PHP #

- If you had a custom dashboard view called `custom_dashboard.php`, it should now be placed in `/resources/views/show/custom_dashboard.php`.

- `provides.php` for each module should now include the module name. This will be part of registering
  module namespaces so that views will be loaded out of the module if you use a prefix such as module::viewname