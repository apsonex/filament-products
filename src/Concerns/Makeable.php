<?php
namespace Apsonex\FilamentProducts\Concerns;


trait Makeable
{
    public static function make(...$args): static
    {
        return new static(...$args);
    }
}
