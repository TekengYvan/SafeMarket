<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTitleAttribute(?string $value): ?string
    {
        return \App\Support\LocalizedMessage::render($value);
    }

    public function getContentAttribute(?string $value): ?string
    {
        return \App\Support\LocalizedMessage::render($value);
    }
}
