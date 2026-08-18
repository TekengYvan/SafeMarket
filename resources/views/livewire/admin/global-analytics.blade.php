<div class="space-y-6">
    <!-- Top Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-t-4 border-indigo-500">
            <p class="text-xs text-gray-500 uppercase font-bold">{{ __('Volume de Ventes (HT)') }}</p>
            <p class="text-2xl font-bold">{{ number_format($totalVolume, 2) }} FCFA</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-t-4 border-green-500">
            <p class="text-xs text-gray-500 uppercase font-bold">{{ __('Taxe Plateforme (5%)') }}</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($totalTaxCollected, 2) }} FCFA</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-t-4 border-red-500">
            <p class="text-xs text-gray-500 uppercase font-bold">{{ __('Litiges Actifs') }}</p>
            <p class="text-2xl font-bold text-red-600">{{ $activeDisputesCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-t-4 border-blue-500">
            <p class="text-xs text-gray-500 uppercase font-bold">{{ __('Utilisateurs Totaux') }}</p>
            <p class="text-2xl font-bold">{{ $totalUsersCount }}</p>
        </div>
    </div>

    <!-- Global Chart -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        <h3 class="font-bold mb-4">{{ __('Volume d\'affaires global (30 derniers jours)') }}</h3>
        <div class="h-80">
            <canvas id="globalSalesChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            const ctx = document.getElementById('globalSalesChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [{
                            label: 'Volume de ventes (FCFA)',
                            data: @json($chartData['values']),
                            backgroundColor: '#4f46e5',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        });
    </script>
</div>
