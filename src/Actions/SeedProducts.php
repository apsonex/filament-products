<?php

namespace Apsonex\FilamentProducts\Actions;


use Illuminate\Support\Arr;
use Apsonex\FilamentProducts\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Apsonex\FilamentProducts\Concerns\Makeable;

class SeedProducts
{
    use Makeable;

    protected array $subscriptions = [];
    protected array $products = [];
    protected \Stripe\StripeClient $stripe;

    public function handle()
    {
        $this->stripe = new \Stripe\StripeClient(
            config('cashier.secret')
        );

        $products = require base_path('stubs/products.php');

        foreach ($products as $key => $items) {
            if ($key === 'subscriptions') {
                $this->seedSubscripiton($items);
            } else if ($key === 'products') {
                $this->seedProducts($items);
            }
        }

        $this->seedSubscripitonsToStripe();
        $this->seedProductsToStripe();
    }

    protected function seedSubscripiton(array $items)
    {
        foreach ($items as $data) {
            foreach ($data['prices'] as $priceType => $price) {
                $toStore = Arr::except($data, ['prices']);
                $toStore = array_merge($toStore, [
                    'slug' => $data['name'] . '_' . $priceType,
                    'frequency' => $priceType,
                    'price' => $price * 100,
                ]);
                $this->subscriptions[] = Product::query()->create($toStore);
            }
        }
    }

    protected function seedProducts(array $items)
    {
        foreach ($items as $data) {
            $toStore = $data;
            $toStore = array_merge($toStore, [
                'price' => $data['price'] * 100,
            ]);
            $this->products[] = Product::query()->create($toStore);
        }
    }

    protected function seedSubscripitonsToStripe()
    {
        Product::query()->typeSubscription()->get()->groupBy('name')->each(function (Collection $items, $key) {
            $response = $this->stripe->products->search([
                'query' => 'metadata[\'product_name\']:\'' . $items->first()->name . '\'',
                'limit' => 1,
            ]);

            $stripeProduct = !$response->isEmpty()
                ? $response->first()
                : $this->stripe->products->create([
                    'name' => $items->first()->label,
                    'description' => $items->first()->description,
                    'metadata' => [
                        'product_name' => $items->first()->name,
                    ]
                ]);

            $items->each(function (Product $productItem) use ($stripeProduct) {

                $response = $this->stripe->prices->search([
                    'query' => 'metadata[\'slug\']:\'' . $productItem->slug . '\'',
                    'limit' => 1,
                ]);

                $price = !$response->isEmpty()
                    ? $response->first()
                    : $this->stripe->prices->create([
                        'unit_amount' => $productItem->price,
                        'currency' => $productItem->currency ?: 'usd',
                        'recurring' => ['interval' => $productItem->frequency],
                        'product' => $stripeProduct->id,
                        'metadata' => [
                            'slug' => $productItem->slug,
                        ]
                    ]);

                $productItem->update([
                    'stripe_product_id' => $stripeProduct->id,
                    'stripe_price_id' => $price->id
                ]);
            });
        });
    }

    protected function seedProductsToStripe()
    {
        Product::query()->get()
            ->filter(fn ($item) => in_array($item['type'], [Product::PRODUCT_TYPE_PRODUCT, Product::PRODUCT_TYPE_SERVICE]))
            ->each(function (Product $item, $key) {
                $response = $this->stripe->products->search([
                    'query' => 'metadata[\'product_name\']:\'' . $item->name . '\'',
                    'limit' => 1,
                ]);

                $stripeProduct = !$response->isEmpty()
                    ? $response->first()
                    : $this->stripe->products->create([
                        'name' => $item->label,
                        'description' => $item->description,
                        'metadata' => [
                            'product_name' => $item->name,
                        ]
                    ]);

                $response = $this->stripe->prices->search([
                    'query' => 'metadata[\'slug\']:\'' . $item->slug . '\'',
                    'limit' => 1,
                ]);

                $price = !$response->isEmpty()
                    ? $response->first()
                    : $this->stripe->prices->create([
                        'unit_amount' => $item->price,
                        'currency' => $item->currency ?: 'usd',
                        'product' => $stripeProduct->id,
                        'metadata' => [
                            'slug' => $item->slug,
                        ]
                    ]);

                $item->update([
                    'stripe_product_id' => $stripeProduct->id,
                    'stripe_price_id' => $price->id
                ]);
            });
    }
}
