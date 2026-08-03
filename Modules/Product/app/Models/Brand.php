<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// use Modules\Product\Database\Factories\BrandFactory;

class Brand extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['name', 'slug', 'logo', 'description', 'is_active'];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }
}
