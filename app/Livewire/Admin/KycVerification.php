<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class KycVerification extends Component
{
    use WithPagination;

    public function verify($userId, $status)
    {
        $user = User::findOrFail($userId);
        if (!in_array($status, ['verified', 'rejected'])) return;

        $user->update(['kyc_status' => $status]);
        
        if ($status === 'verified') {
            $user->assignRole('vendor');
        }

        // Send Notification to User
        $title = $status === 'verified' ? \App\Support\LocalizedMessage::store('events.seller_account_verified') : \App\Support\LocalizedMessage::store('events.identity_verification_rejected');
        $content = $status === 'verified' 
            ? \App\Support\LocalizedMessage::store('events.congratulations_your_identity_documents_have_been_verified_you')
            : \App\Support\LocalizedMessage::store('events.your_identity_documents_were_rejected_please_submit_a');
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
        ]);

        session()->flash('status', __('Le statut KYC de :value1 a été mis à jour : :value2', ['value1' => $user->name, 'value2' => \App\Support\LocalizedMessage::status($status)]));
    }

    public function render()
    {
        $pendingUsers = User::where('kyc_status', 'pending')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.kyc-verification', [
            'pendingUsers' => $pendingUsers
        ]);
    }
}
