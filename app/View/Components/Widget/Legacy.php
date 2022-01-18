<?php

namespace App\View\Components\Widget;

use Illuminate\View\Component;
use MR\Kiss\View;
use munkireport\lib\Widgets;

class Legacy extends Component
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
    public function render(): string
    {
        $obj = new View();
        $widget = app(Widgets::class)->get($this->name, $this->data);
        $output = $obj->viewFetch($widget->file, $widget->vars, $widget->path);
        return $output;
    }
}
