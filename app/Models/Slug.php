<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    use HasFactory;

    protected $table = 'slugables';

    protected $fillable = [
        'slugable_id',
        'slugable_type',
        'slug',
        'source',
    ];

    public function slugable()
    {
        return $this->morphTo(__FUNCTION__, 'slugable_type', 'slugable_id', 'id');
    }
}
