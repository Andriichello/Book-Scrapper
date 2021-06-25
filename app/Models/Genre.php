<?php

namespace App\Models;

use App\Models\Traits\Imageable;
use App\Models\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory, Slugable;

    protected $fillable = [
        'name',
        'description',
    ];

    public function books() {
        return $this->belongsToMany(Book::class);
    }
}
