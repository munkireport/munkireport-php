<?php
namespace Mr\Core\Clients;

use Illuminate\Contracts\View\View;
use Mr\Contracts\TabController;

class SummaryTabController implements TabController
{

    /**
     * Get the name of the view to include in the tab content area. The view name is passed to blade as an
     * @include directive.
     *
     * @return string
     */
    public function viewName()
    {
        return 'client.summary_tab';
    }

    /**
     * Get the name of the view to include as the "tab" item.
     *
     * @return string
     */
    public function menuItemViewName()
    {
        return 'client.summary_tab_item';
    }

    /**
     * Fetch and provide variable contents to the passed-in constructed view.
     *
     * The params argument provides parameters that were supplied to the page controller.
     *
     * @param View $view
     * @param array $params
     * @return void
     */
    public function render(View &$view, array $params = Array())
    {
        // TODO: Implement render() method.
    }
}