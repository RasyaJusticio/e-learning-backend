<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MaterialFile extends Model
{
    protected $fillable = [
        'material_id',
        'file_url',
        'type'
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    protected static function booted(): void
    {
        static::deleting(function ($file) {
            if (Storage::disk('public')->exists($file->file_url)) {
                Storage::disk('public')->delete($file->file_url);
            }
        });
    }
}
