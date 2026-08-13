<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Product\Database\Factories\AttributeValueFactory;

class AttributeValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function attributes(){
        return $this->belongsTo(Attribute::class);
    }
    // protected static function newFactory(): AttributeValueFactory
    // {
    //     // return AttributeValueFactory::new();
    // }
}
