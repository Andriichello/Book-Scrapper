<?php

namespace App\Models\Traits;

use App\Models\Slug;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Slugable {
    public function slugs(): MorphMany
    {
        return $this->morphMany(Slug::class, 'slugable', 'slugable_type', 'slugable_id', 'id');
    }
}
