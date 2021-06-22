<?php

namespace App\Models;

use App\Models\Traits\Imageable;
use App\Models\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory, Imageable, Slugable;

    protected $fillable = [
        'slug',
        'name',
        'surname',
        'biography',
    ];

    protected $with = [
        'slugs',
        'images',
    ];

    public function books() {
        return $this->belongsToMany(Book::class);
    }
}
