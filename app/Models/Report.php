<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['reporter_id', 'reported_id', 'product_id', 'reason', 'status'])]
class Report extends Model
{
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reported()
    {
        return $this->belongsTo(User::class, 'reported_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
