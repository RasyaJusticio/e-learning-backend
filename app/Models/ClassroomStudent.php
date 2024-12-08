<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassroomStudent extends Pivot
{
    protected $fillable = [
        'classroom_id',
        'student_id',
        'joined_at',
    ];

    protected static function booted(): void
    {
        static::creating(function ($pivot) {
            $pivot->joined_at = now();
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }
}
