<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Marketing\Models\Discount;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable')->withTimestamps();
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::tags(['categories'])->flush());
        static::deleted(fn () => Cache::tags(['categories'])->flush());
    }
}
