<?php

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discountable extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function discountable()
    {
        return $this->morphTo();
    }

    // protected static function newFactory(): DiscountableFactory
    // {
    //     // return DiscountableFactory::new();
    // }
}
