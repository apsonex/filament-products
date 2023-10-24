<?php

namespace Apsonex\FilamentProducts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Apsonex\FilamentProducts\FilamentProducts
 */
class FilamentProducts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Apsonex\FilamentProducts\FilamentProducts::class;
    }
}
