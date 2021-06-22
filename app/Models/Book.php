<?php

namespace App\Models;

use App\Models\Traits\Imageable;
use App\Models\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, Imageable, Slugable;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'publisher_id',
        'slug',
        'title',
        'original_title',
        'language',
        'original_language',
        'description',
        'ebook',
        'price',
        'club_price',
        'currency',
        'cover',
        'pages',
        'format',
        'age_restriction',
        'rating',
        'reviews',
        'isbn',
        'details',
    ];

    protected $with = [
        'slugs',
        'images',
        'authors',
        'genres',
        'publisher',
    ];

    public function authors() {
        return $this->belongsToMany(Author::class);
    }

    public function genres() {
        return $this->belongsToMany(Genre::class);
    }

    public function publisher() {
        return $this->belongsTo(Publisher::class);
    }
}
