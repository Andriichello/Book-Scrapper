<?php

namespace App\Models\Traits;

use App\Models\Image;

trait Imageable {
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable', 'imageable_type', 'imageable_id', 'id');
    }
}
