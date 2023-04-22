# Developing Module Widgets

At present, you can develop widgets using a backwards compatible (v5) format, or using a forwards
compatible (v6) format, the differences are explained in the following table:

| v5 Module Widget                       | v6 Module Widget                                            |
|----------------------------------------|-------------------------------------------------------------|
| .php view based                        | blade component view based                                  |
| supports .yaml                         | yaml not supported (except as a blade view that loads yaml) |
| discovered by folder/file name         | must be registered using name, from any folder              |
| not usable/discoverable across modules | all modules can use registered widgets of other modules     |

## Developing v5 Module Widgets

Recall that the v5 module structure looks something like below:

```shell 
foo/
  locales/
  migrations/
  scripts/
  views/
  foo_controller.php
  foo_model.php
  composer.json
  provides.php
```

The widget loading mechanism for v5 modules is roughly described as:

* The `munkireport\lib\Modules` class enumerates over each module directory and decides that it contains a module if either `*_model.php` exists or `scripts/install.sh` exists.
* If the module contains `provides.yml` or `provides.php`, the module metadata is loaded from that.
* The provides file may declare a "widgets" section that provides a mapping of a widget name to its view name and metadata.

The format of a `provides.php` file describing a widget looks like this 
(the .yml format will be the same structure but with the corresponding YAML syntax):

```injectablephp
<?php
    return array(
        'widgets' => array(
            '32_bit_apps' => array( // The array key is the widget name, used in the dashboard layout
                'view' => 'applications_32_bit_apps_widget', // The view name that will be loaded (with a .php suffix)
            ),
        ),
    )
?>
```

When the widget is required by a dashboard, the widget view.php will be included and collected into an
output buffer to be displayed directly.

During this process the widget has access to some global helper functions.
(TODO) Not yet documented here, but you can browse the source at `compatibility/helpers` to get an idea of what is available.

## Developing v6 Module Widgets

If you use a v6 module format, you have the option of registering widgets in your module service provider.

Widgets are implemented as [Blade Components](https://laravel.com/docs/9.x/blade#components).
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


## How v5 Widget Compatibility is Provided

(TODO) Describe how we have blade components which provide backwards compatibility for .yaml defined widgets in v5 modules.

