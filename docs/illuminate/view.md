# Adding Illuminate/View to MunkiReport PHP #

- If you had a custom dashboard view called `custom_dashboard.php`, it should now be placed in `/resources/views/show/custom_dashboard.php`.

- Each tab in the client detail view is now represented by a custom instance of a Tab View Controller.

- Custom tabs via the `client_tabs` configuration option are not currently respected.


## Tabs (Client Detail) ##

The `provides.php` file has been altered to use `Illuminate/View` namespaces AND to allow for
tab controllers in order to separate the view logic from the database logic.
  
1. When writing a module, the tab view name needs a namespace prefix.
Just add the module name as a prefix before the double colon like so:

        'client_tabs' => array(
            'directory-tab' => array(
                'view' => 'directory_service::directory_tab',
                'i18n' => 'directory_service.title'
            ),
        ),
        
    The prefix hints to the template system which folder to look in for blade templates.

2. If the tab template originally contained calls to the database or constructed new models,
you have the option to split that into a controller and a view. To use a tab view controller:
add a `view_controller` key to the `client_tabs` item like so:
    
        'applications-tab' => array(
            'view' => 'applications_tab',
            'view_controller' => 'ApplicationsTabController',
            'i18n' => 'applications.applications',
            'badge' => 'applications-cnt',
        ),    
        
    The named controller will be called whenever munkireport attempts to render that tab.
    Take a look at one of the existing TabController classes for more information.
    

 