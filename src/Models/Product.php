<?php

namespace Apsonex\FilamentProducts\Models;

use Apsonex\FilamentProducts\Concerns\HasMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use HasMeta;

    const PRODUCT_TYPE_SUBSSCRIPTION = 'subscription';
    const PRODUCT_TYPE_PRODUCT = 'product';
    const PRODUCT_TYPE_SERVICE = 'service';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'price' => 'integer',
        'percent_save' => 'integer',
        'trial_period_days' => 'integer',
        'features' => 'array',
        'is_shipable' => 'boolean',
        'is_popular' => 'boolean',
        'meta' => 'array',
        'features' => 'array',
    ];

    public function scopeTypeSubscription($query)
    {
        $query->where('type', static::PRODUCT_TYPE_SUBSSCRIPTION);
    }

    public function scopeTypeProduct($query)
    {
        $query->where('type', static::PRODUCT_TYPE_PRODUCT);
    }
}
