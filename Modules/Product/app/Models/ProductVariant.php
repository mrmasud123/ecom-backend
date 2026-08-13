<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Models\Discount;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// use Modules\Product\Database\Factories\ProductVariantFactory;

class ProductVariant extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['product_id', 'sku', 'price', 'compare_price', 'quantity', 'is_active'];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_attribute_value'
        )->withTimestamps();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable')->withTimestamps();
    }
}
