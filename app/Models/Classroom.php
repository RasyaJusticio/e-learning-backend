<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Classroom extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'teacher_id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    protected static function booted(): void
    {
        static::creating(function ($classroom) {
            if (!$classroom->uuid) {
                $classroom->uuid = (string) Str::uuid();
            }
        });
    }
}
