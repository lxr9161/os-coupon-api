<?php
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Price implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return number_format($value / 100, 2, '.', '');
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}