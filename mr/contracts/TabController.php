<?php
namespace Mr\Contracts;

use Illuminate\Contracts\View\View;

/**
 * Each tab controller should implement this interface.
 *
 * @package Mr\Contracts
 */
interface TabController
{
    /**
     * Get the name of the view to include in the tab content area. The view name is passed to blade as an
     * @include directive.
     *
     * @return string
     */
    public function viewName();

    /**
     * Fetch and provide variable contents to the passed-in constructed view.
     *
     * The params argument provides parameters that were supplied to the page controller.
     *
     * @param View $view
     * @param array $params
     * @return void
     */
    public function render(View &$view, array $params = Array());
}