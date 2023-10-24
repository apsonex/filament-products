<?php

namespace Apsonex\FilamentProducts\Livewire;

use Apsonex\FilamentProducts\Facades\FilamentProducts;
use Illuminate\Support\Collection;
use Livewire\Component;

// $plans = computed(function () {
//     return \Apsonex\FilamentProducts\FilamentProducts::getPlans();
// });

class PricingTable extends Component
{

    public bool $showHeader = true;
    public null|string $onPlanSelection = null;
    public null|bool $showFree = true;
    public array $colors = [];

    public Collection $plans;

    public function mount($onPlanSelection = null)
    {
        $this->plans = FilamentProducts::getPlans();
        $this->onPlanSelection = $onPlanSelection;
    }

    public function render()
    {
        return view('filament-products::livewire.pricing-plans');
    }
}
