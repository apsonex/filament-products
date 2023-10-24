<?php

namespace Apsonex\FilamentProducts\Concerns;


use Spatie\Url\Url;
use Illuminate\Http\Request;
use Apsonex\FilamentProducts\Models\Product;

trait HasProduct
{
    public null|Product $product = null;

    public null|string $frequency = null;

    public function checkoutSuccessUrl(?string $redirect = null): string
    {
        $url = Url::fromString(route('subscription.thank-you'));

        $data = [];

        if ($redirect) {
            $data['redirect'] = $redirect;
        }

        $url = $url->withQueryParameter('hash', encrypt(json_encode($data, true)));

        return $url->__toString();
    }

    protected function resoveRequestToPlan(Request $request)
    {
        $this->resolveProduct(
            $request->get('plan', ''),
            $request->get('frequency', '')
        );
    }

    public function resolveProduct(string $planId)
    {
        $this->product = Plan::make()->findPlan($planId);
        $this->frequency = $this->product?->frequency;
    }

    public function selectPlan($planId)
    {
        $this->resolveProduct($planId);

        return true;
    }
}
