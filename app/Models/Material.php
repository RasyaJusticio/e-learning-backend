<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = [
        'classroom_id',
        'title',
        'content'
    ];

    public function files(): HasMany
    {
        return $this->hasMany(MaterialFile::class);
    }
}
