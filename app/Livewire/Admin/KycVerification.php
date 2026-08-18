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
        $title = $status === 'verified' ? 'Compte commerçant validé ! 🎉' : 'Dossier KYC rejeté ⚠️';
        $content = $status === 'verified' 
            ? 'Félicitations, vos pièces d\'identité ont été validées. Vous pouvez maintenant accéder à votre boutique vendeur.'
            : 'Malheureusement, vos pièces d\'identité ont été rejetées. Veuillez soumettre un document conforme dans votre profil.';
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
        ]);

        session()->flash('status', "Le statut KYC de {$user->name} a été mis à jour : {$status}");
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
