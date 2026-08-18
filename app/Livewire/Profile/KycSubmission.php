<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class KycSubmission extends Component
{
    use WithFileUploads;

    public $idCard;
    public $status;

    public function mount()
    {
        $this->status = Auth::user()->kyc_status;
    }

    public function submit()
    {
        $this->validate([
            'idCard' => 'required|image|max:4096',
        ]);

        $user = Auth::user();
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $this->idCard->getClientOriginalName());
        $media = $user->addMedia($this->idCard->getRealPath())
             ->usingFileName($fileName)
             ->toMediaCollection('kyc_documents');

        $user->update([
            'id_card_photo' => $media->getUrl(),
            'kyc_status' => 'pending'
        ]);
        $this->status = 'pending';

        session()->flash('status', 'Votre document a été envoyé avec succès et est en attente de vérification.');
    }

    public function render()
    {
        return view('livewire.profile.kyc-submission');
    }
}
