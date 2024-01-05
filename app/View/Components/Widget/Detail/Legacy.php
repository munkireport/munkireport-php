<?php

namespace App\View\Components\Widget\Detail;

use Illuminate\View\Component;
use Compatibility\Kiss\View;
use munkireport\lib\Widgets;

class Legacy extends Component
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
     * @return string
     */
    public function render(): string
    {
        $obj = new View();
        $widget = app(Widgets::class)->getDetail($this->data);
        $output = $obj->viewFetch($widget['file'], $widget['vars'], $widget['path']);
        return $output;
    }
}
