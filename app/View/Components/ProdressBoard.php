<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProdressBoard extends Component
{
    public $title;
    public $percent;
    public $mainDigit;
    public $quantityFromOneHundred;

    /**
     * Create a new component instance.
     *
     * @param string $title
     * @param int $percent
     * @param int $mainDigit
     * @param int $quantityFromOneHundred
     * @return void
     */

    public function __construct($title, $percent, $mainDigit, $quantityFromOneHundred)
    {
        $this->title = $title;
        $this->percent = $percent;
        $this->mainDigit = $mainDigit;
        $this->quantityFromOneHundred = $quantityFromOneHundred;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.prodress-board');
    }
}
