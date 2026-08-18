<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class GlobalAnalytics extends Component
{
    public $totalVolume;
    public $totalTaxCollected;
    public $activeDisputesCount;
    public $totalUsersCount;
    
    public $chartData = [];

    public function mount()
    {
        $this->totalVolume = Order::where('status', 'completed')->sum('amount');
        $this->totalTaxCollected = $this->totalVolume * 0.05;
        $this->activeDisputesCount = Order::where('is_disputed', true)->where('dispute_status', 'pending')->count();
        $this->totalUsersCount = User::count();

        $this->loadGlobalStats();
    }

    public function loadGlobalStats()
    {
        $stats = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->chartData = [
            'labels' => $stats->pluck('date')->map(fn($d) => date('d M', strtotime($d))),
            'values' => $stats->pluck('total')
        ];
    }

    public function render()
    {
        return view('livewire.admin.global-analytics');
    }
}
