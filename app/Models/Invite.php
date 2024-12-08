<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invite extends Model
{
    protected $fillable = [
        'classroom_id',
        'student_id',
        'status'
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function accept()
    {
        $this->update([
            'status' => 'accepted'
        ]);
    }

    public function decline()
    {
        $this->update([
            'status' => 'declined'
        ]);
    }
}
