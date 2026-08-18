<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->order->product->vendor_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'tracking_number' => 'required|string|max:255',
        ];
    }
}
