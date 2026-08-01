<?php

namespace App\Support\Media;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class ModulePathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->basePath($media) . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->basePath($media) . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->basePath($media) . '/responsive-images/';
    }

    protected function basePath(Media $media): string
    {
        return Str::plural(Str::lower(class_basename($media->model_type)));
    }
}
