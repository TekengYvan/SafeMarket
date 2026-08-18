<?php

namespace App\Http\Controllers\Web\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Negotiation;
use Illuminate\Http\Request;

class NegotiationController extends Controller
{
    public function index()
    {
        $negotiations = Negotiation::with(['product', 'buyer'])
            ->where('seller_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('vendor.negotiations.index', compact('negotiations'));
    }
}
