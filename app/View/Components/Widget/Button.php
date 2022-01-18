<?php

namespace App\View\Components\Widget;

use Illuminate\View\Component;

class Button extends Component
{
    /**
     * @var {string} Widget name/template name
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
        return view('components.widget.button', $this->data);
    }
}
