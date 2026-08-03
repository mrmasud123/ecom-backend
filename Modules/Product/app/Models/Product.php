<?php
namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'brand_id', 'name', 'slug', 'sku', 'short_description', 'description',
        'price', 'compare_price', 'cost_price',
        'track_quantity', 'quantity', 'allow_backorder',
        'weight', 'length', 'width', 'height',
        'has_variants', 'is_active', 'is_featured',
        'meta_title', 'meta_description', 'published_at',
    ];

    protected $casts = [
        'track_quantity'  => 'boolean',
        'allow_backorder' => 'boolean',
        'has_variants'    => 'boolean',
        'is_active'       => 'boolean',
        'is_featured'     => 'boolean',
        'published_at'    => 'datetime',
        'price'           => 'decimal:2',
        'compare_price'   => 'decimal:2',
        'cost_price'      => 'decimal:2',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    protected static function booted(): void
    {
        
        static::saved(fn () => Cache::tags(['products'])->flush());
        static::deleted(fn () => Cache::tags(['products'])->flush());
    }
}

