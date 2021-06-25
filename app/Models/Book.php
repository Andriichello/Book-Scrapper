<?php

namespace App\Models;

use App\Models\Traits\Imageable;
use App\Models\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory, Imageable, Slugable;

    protected $fillable = [
        'id',
        'publisher_id',
        'code',
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
        'publisher',
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }
}
