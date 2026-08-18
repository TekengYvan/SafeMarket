<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesStats extends Component
{
    public $totalSalesCount;
    public $totalRevenue;
    public $pendingRevenue;
    public $recentSales;
    
    public $chartData = [];

    public function mount()
    {
        $vendorId = Auth::id();

        $allSales = Order::whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->get();

        $this->totalSalesCount = $allSales->where('status', 'completed')->count();
        $this->totalRevenue = $allSales->where('status', 'completed')->sum('amount');
        $this->pendingRevenue = $allSales->whereIn('status', ['funds_held', 'shipped'])->sum('amount');
        
        $this->recentSales = Order::with('product', 'buyer')
            ->whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->latest()
            ->take(5)
            ->get();

        $this->loadChartData();
    }

    public function loadChartData()
    {
        $vendorId = Auth::id();
        
        // Revenue per day for the last 7 days
        $stats = Order::whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
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
        return view('livewire.vendor.sales-stats');
    }
}
