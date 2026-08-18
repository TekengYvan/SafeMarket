<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['negotiation_id', 'user_id', 'content'])]
class Message extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class);
    }
}
