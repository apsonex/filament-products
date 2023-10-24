<?php

namespace Apsonex\FilamentProducts;

use Apsonex\FilamentProducts\Models\Product;
use Illuminate\Support\Collection;

class FilamentProducts
{

    protected Collection $plans;

    public function getPlans(): Collection
    {
        return $this->plans ??= collect(
            Product::query()->typeSubscription()->get()->toArray()
        );
    }


    public function getSubscriptionProductBySlug(string $slug): ?Product
    {
        return Product::query()->typeSubscription()->where('slug', $slug)->first();
    }
}
