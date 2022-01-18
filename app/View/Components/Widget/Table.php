<?php

namespace App\View\Components\Widget;

use Illuminate\View\Component;

/**
 * The table widget is a conversion of `detail_widgets/table.php`.
 *
 * It is only meant for use with the detail view.
 */
class Table extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widget.table');
    }
}
