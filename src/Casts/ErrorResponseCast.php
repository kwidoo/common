<?php

namespace Velia\Commnon\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

class ErrorResponseCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $value = json_decode($value);
        if (env('IGNITION_REMOTE_SITES_PATH') && env('IGNITION_LOCAL_SITES_PATH')) {
            $value->item = collect($value->trace)->map(function ($item, $key) {
                $item->link = preg_replace_array('/[\(\)]+/', [''], Str::replaceFirst(env('IGNITION_REMOTE_SITES_PATH'), env('IGNITION_LOCAL_SITES_PATH'), $item->file));
                $item->link .= ':' . $item->line;
                return $item;
            });
        }
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value);
    }
}
