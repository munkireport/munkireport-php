<?php

namespace App\View\Components\Widget\Detail;

use Illuminate\View\Component;

/**
 * The table widget is a conversion of `detail_widgets/table.php`.
 *
 * It is only meant for use with the detail view.
 */
class Table extends Component
{
    /**
     * @var string Widget name/template name
     */
    public $name;

    public $data;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, ?array $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widget.detail.table', $this->data);
    }
}
