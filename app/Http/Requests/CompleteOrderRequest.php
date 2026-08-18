<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->order->buyer_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'release_code' => 'required|string',
        ];
    }
}
