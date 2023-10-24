<?php
namespace Apsonex\FilamentProducts\Concerns;


use Illuminate\Support\Arr;

trait HasMeta
{
    public function getMeta(string | array | null $keys = null): mixed
    {
        if (is_array($keys)) {
            return Arr::only($this->meta, $keys);
        }

        if (is_string($keys)) {
            return Arr::get($this->meta, $keys);
        }

        return $this->meta;
    }


    /**
     * @param  string | array<string>  $keys
     */
    public function hasMeta(string | array $keys): bool
    {
        return Arr::has($this->meta, $keys);
    }

    public function removeMetaKey($key)
    {
        $meta = $this->meta ?: [];
        Arr::forget($meta, $key);
        $this->meta = $meta;
        $this->save();
    }

    public function updateMetaKey(string $key, $value)
    {
        $meta = $this->meta ?: [];
        Arr::set($meta, $key, $value);
        $this->meta = $meta;
        $this->save();
    }
}
