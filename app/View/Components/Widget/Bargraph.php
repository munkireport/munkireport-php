<?php

namespace App\View\Components\Widget;

use Illuminate\View\Component;

class Bargraph extends Component
{
    /**
     * @var {string} Widget ID
     */
    public $id;

    /**
     * @var {string} The i18n key which represents the title, will be translated client side.
     */
    public $title;


    /**
     * @var {string} The icon displayed at the top left of the widget.
     */
    public $icon;

    /**
     * @var {string} The i18n tooltip which represents the tooltip, will be translated client side.
     */
    public $tooltip;

    /**
     * @var {string} A link to the listing that corresponds with this widget.
     */
    public $listingLink;

    public $apiUrl;

    public $margins;

    public $searchComponent;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $id,
        string $title,
        string $icon = null,
        string $tooltip = null,
        string $listingLink = null,
        string $apiUrl = null,
        array $margin = null,
        bool $searchComponent = false
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->icon = $icon;
        $this->tooltip = $tooltip;
        $this->listingLink = $listingLink;
        $this->apiUrl = $apiUrl;
        $this->searchComponent = $searchComponent;

        $graph_margins = ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 70];

        if(isset($margin) && is_array($margin)){
            $graph_margins = array_merge($graph_margins, $margin);
        }

//        if( ! isset($margin) || ! is_string($margin)){
//            $margin = json_encode($graph_margins);
//        }

        $this->margins = $graph_margins;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widget.bargraph');
    }
}
