<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Negotiation;
use App\Http\Requests\StoreNegotiationRequest;
use App\Http\Requests\SendNegotiationMessageRequest;
use App\Http\Requests\UpdateNegotiationRequest;
use App\Services\NegotiationService;
use Illuminate\Http\Request;

class NegotiationController extends Controller
{
    protected $negotiationService;

    public function __construct(NegotiationService $negotiationService)
    {
        $this->negotiationService = $negotiationService;
    }

    public function show(Negotiation $negotiation)
    {
        if ($negotiation->buyer_id !== auth()->id() && $negotiation->seller_id !== auth()->id()) {
            abort(403);
        }

        $negotiation->load(['product', 'buyer', 'seller', 'messages.user']);
        
        return view('negotiations.show', compact('negotiation'));
    }

    public function sendMessage(SendNegotiationMessageRequest $request, Negotiation $negotiation)
    {
        $this->negotiationService->sendMessage($negotiation, $request->validated()['content']);

        return back();
    }

    public function index()
    {
        $negotiations = Negotiation::with(['product', 'seller'])
            ->where('buyer_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('marketplace.negotiations', compact('negotiations'));
    }

    public function store(StoreNegotiationRequest $request)
    {
        try {
            $this->negotiationService->createNegotiation($request->validated());
            return back()->with('status', 'Votre proposition a été envoyée au vendeur.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(UpdateNegotiationRequest $request, Negotiation $negotiation)
    {
        try {
            $this->negotiationService->updateStatus($negotiation, $request->validated()['status']);
            return back()->with('status', "La proposition a été {$request->status}.");
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
    }
}
