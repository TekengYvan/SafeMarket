<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadNotifications;
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->unreadNotifications = $user->notifications()->latest()->limit(5)->get();
            $this->unreadCount = $user->notifications()->where('is_read', false)->count();
        } else {
            $this->unreadNotifications = collect();
            $this->unreadCount = 0;
        }
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
