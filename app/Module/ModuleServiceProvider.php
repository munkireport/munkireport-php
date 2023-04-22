<?php

namespace App\Module;

use Illuminate\Support\ServiceProvider;
use munkireport\lib\Widgets;

/**
 * This abstract ServiceProvider adds helper methods to the Laravel ServiceProvider class that can be used
 * to register widgets, dashboards, reports and listings.
 */
abstract class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register the package's custom widgets.
     *
     * Widgets should be an array of arrays which have the following metadata structure:
     *
     * [
     *   'name' => 'widget',
     *   'file' => '/fully/qualified/path/to/some_widget.php|yaml', // for v5 view based widgets
     *   'version' => 6, // for component based widgets
     *   'component' => 'module-widgets.name', // for v6 component based widgets: the name of the component to render.
     * ]
     *
     * @note There are two ways to load components from a package: `Blade::componentNamespace` or $this->loadViewComponentsAs(),
     *       but there is not any guidance on which one is suitable.
     */
    protected function widgets(array $addWidgets): void
    {
        $this->callAfterResolving(Widgets::class, function (Widgets $widgets) use ($addWidgets) {
            foreach ($addWidgets as $name => $meta) {
                if ($meta['version'] == 6) {
                    $widgets->addComponent($name, $meta['component']);
                }
            }
        });
    }
}
