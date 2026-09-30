<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Negotiation;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NegotiationChat extends Component
{
    public Negotiation $negotiation;
    public string $newMessage = '';

    public function mount(Negotiation $negotiation)
    {
        $this->authorizeParticipant($negotiation);
        $this->negotiation = $negotiation;

        // Ensure initial offer message is present in chat history
        if ($this->negotiation->messages()->count() === 0 && !empty($this->negotiation->message)) {
            Message::create([
                'negotiation_id' => $this->negotiation->id,
                'user_id' => $this->negotiation->buyer_id,
                'content' => $this->negotiation->message,
            ]);
        }
    }

    public function sendMessage()
    {
        $this->authorizeParticipant($this->negotiation);
        $content = trim($this->newMessage);
        if (empty($content) || ! in_array($this->negotiation->status, ['pending', 'accepted'], true)) {
            return;
        }

        $this->validate(['newMessage' => 'required|string|max:1000']);

        $msg = Message::create([
            'negotiation_id' => $this->negotiation->id,
            'user_id' => Auth::id(),
            'content' => $content,
        ]);

        $this->newMessage = '';

        // Notify recipient
        $recipientId = Auth::id() === $this->negotiation->buyer_id
            ? $this->negotiation->seller_id
            : $this->negotiation->buyer_id;

        Notification::create([
            'user_id' => $recipientId,
            'title' => \App\Support\LocalizedMessage::store('events.new_negotiation_message'),
            'content' => \App\Support\LocalizedMessage::store('events.sent_you_a_message_about', ['value1' => Auth::user()->name, 'value2' => $this->negotiation->product->title]),
        ]);

        $this->dispatch('messageSent');
    }

    public function updateStatus($status)
    {
        $this->authorizeParticipant($this->negotiation);
        if (!in_array($status, ['accepted', 'rejected', 'cancelled'])) return;
        if ($this->negotiation->status !== 'pending') return;

        if ($status === 'cancelled') {
            if ($this->negotiation->buyer_id !== Auth::id()) abort(403);
        } else {
            if ($this->negotiation->seller_id !== Auth::id()) abort(403);
        }

        $this->negotiation->update(['status' => $status]);

        $statusText = match($status) {
            'accepted' => 'acceptée',
            'rejected' => 'refusée',
            'cancelled' => 'annulée',
            default => $status
        };

        Notification::create([
            'user_id' => Auth::id() === $this->negotiation->buyer_id ? $this->negotiation->seller_id : $this->negotiation->buyer_id,
            'title' => \App\Support\LocalizedMessage::store('events.negotiation', ['value1' => \App\Support\LocalizedMessage::label($statusText)]),
            'content' => \App\Support\LocalizedMessage::store('events.the_offer_for_has_been', ['value1' => $this->negotiation->product->title, 'value2' => \App\Support\LocalizedMessage::label($statusText)]),
        ]);
    }

    public function render()
    {
        $this->negotiation->refresh();
        $this->negotiation->load(['product', 'buyer', 'seller']);
        $messages = Message::where('negotiation_id', $this->negotiation->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.negotiation-chat', compact('messages'));
    }

    private function authorizeParticipant(Negotiation $negotiation): void
    {
        abort_unless(Auth::check() && in_array(Auth::id(), [$negotiation->buyer_id, $negotiation->seller_id], true), 403);
    }
}
