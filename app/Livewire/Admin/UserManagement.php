<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';

    public function toggleAdmin($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->id === auth()->id()) return;
        
        $user->is_admin = !$user->is_admin;
        $user->save();

        if ($user->is_admin) {
            $user->assignRole('admin');
        } else {
            $user->removeRole('admin');
        }

        session()->flash('status', "Le statut administrateur de {$user->name} a été mis à jour.");
    }

    public function toggleSuspend($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->id === auth()->id()) {
            session()->flash('error', "Vous ne pouvez pas vous auto-suspendre.");
            return;
        }

        $user->is_suspended = !$user->is_suspended;
        $user->save();

        $action = $user->is_suspended ? 'suspendu' : 'réactivé';
        session()->flash('status', "Le compte de {$user->name} a été {$action} avec succès.");
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->id === auth()->id()) {
            session()->flash('error', "Vous ne pouvez pas supprimer votre propre compte.");
            return;
        }
        $user->delete();
        session()->flash('status', "Utilisateur supprimé avec succès.");
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone_number', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ]);
    }
}
