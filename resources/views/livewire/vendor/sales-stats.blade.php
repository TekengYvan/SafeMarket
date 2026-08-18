<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg border-l-4 border-green-500">
            <p class="text-sm text-gray-500 uppercase font-bold mb-1">{{ __('Ventes Terminées') }}</p>
            <p class="text-3xl font-bold">{{ $totalSalesCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg border-l-4 border-indigo-500">
            <p class="text-sm text-gray-500 uppercase font-bold mb-1">{{ __('Revenu Total') }}</p>
            <p class="text-3xl font-bold text-indigo-600">{{ number_format($totalRevenue, 2) }} FCFA</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500 uppercase font-bold mb-1">{{ __('Fonds en ESCROW') }}</p>
            <p class="text-3xl font-bold text-yellow-600">{{ number_format($pendingRevenue, 2) }} FCFA</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h3 class="text-xl font-bold mb-4">{{ __('Évolution des revenus (7 derniers jours)') }}</h3>
        <div class="h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h3 class="text-xl font-bold mb-4">{{ __('Ventes Récentes') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b dark:border-gray-700 text-gray-500 text-sm">
                        <th class="pb-3">{{ __('Produit') }}</th>
                        <th class="pb-3">{{ __('Acheteur') }}</th>
                        <th class="pb-3">{{ __('Montant') }}</th>
                        <th class="pb-3">{{ __('Date') }}</th>
                        <th class="pb-3">{{ __('Statut') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                            <td class="py-3 font-medium">{{ $sale->product->title }}</td>
                            <td class="py-3 text-sm">{{ $sale->buyer->name }}</td>
                            <td class="py-3 font-bold">{{ number_format($sale->amount, 2) }} FCFA</td>
                            <td class="py-3 text-xs text-gray-500">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3 text-[10px] font-bold uppercase">
                                <span class="px-2 py-0.5 rounded-full {{ $sale->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $sale->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [{
                            label: 'Revenus (FCFA)',
                            data: @json($chartData['values']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        });
    </script>
</div>
