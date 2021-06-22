<?php

namespace App\Models\Traits;

use App\Models\Slug;

trait Slugable {
    public function slugs()
    {
        return $this->morphMany(Slug::class, 'slugable', 'slugable_type', 'slugable_id', 'id');
    }
}
