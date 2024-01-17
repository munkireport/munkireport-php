<?php

namespace App\View\Components\Widget\Detail;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * This component replaces the machine_detail_widget1.php widget with a blade.
 */
class MachineDetail extends Component
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
     */
    public function render(): View|Closure|string
    {
        return view('components.widget.detail.machine-detail', $this->data);
    }
}
