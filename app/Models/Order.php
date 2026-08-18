<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['buyer_id', 'product_id', 'amount', 'phone', 'location', 'status', 'tracking_number', 'release_code', 'is_disputed', 'dispute_reason', 'dispute_status'])]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'is_disputed' => 'boolean',
            'amount' => 'decimal:2',
        ];
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
