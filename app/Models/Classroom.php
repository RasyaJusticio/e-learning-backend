<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Classroom extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'teacher_id',
    ];

    public function isMember(User $user): bool
    {
        if ($user->id === $this->teacher_id) {
            return true;
        }

        return $this->students()->where('student_id', $user->id)->exists();
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_student', 'student_id', 'classroom_id')
            ->using(ClassroomStudent::class);
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
