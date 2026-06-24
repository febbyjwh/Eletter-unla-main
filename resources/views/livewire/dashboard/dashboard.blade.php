<div>
    <!-- Top Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">

        <!-- Surat Masuk -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Surat Masuk</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $jumlahSuratMasuk }}</p>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Surat Keluar -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Surat Keluar</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $jumlahSuratKeluar }}</p>
                </div>
                <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-13v1" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Disposisi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Disposisi</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $jumlahDisposisi }}</p>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 012 2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4a2 2 0 012-2m12-4V6a2 2 0 00-2-2H7a2 2 0 00-2 2v2m14 0H5" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

        <!-- Bar Chart Card -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-800">
                    Statistik Surat Bulanan
                </h2>
                <span class="text-xs text-gray-400">12 bulan terakhir</span>
            </div>
            <div class="h-64">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-800">
                    Distribusi Surat
                </h2>
                <span class="text-xs text-gray-400">Total</span>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Surat Terbaru -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <p class="text-base font-semibold text-gray-800">Surat Terbaru</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="text-gray-500 border-b">
                    <tr>
                        <th class="text-left p-2">No Surat</th>
                        <th class="text-left p-2">Jenis</th>
                        <th class="text-left p-2">Pengirim</th>
                        <th class="text-left p-2">Tujuan/Penerima</th>
                        <th class="text-left p-2">Perihal</th>
                        <th class="text-left p-2">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($suratTerbaru as $surat)
                        <tr class="border-b last:border-0 hover:bg-gray-50/70 transition">
                            <td class="p-2 font-medium text-gray-800">{{ $surat->no_surat }}</td>
                            <td class="p-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $surat->jenis_surat === 'masuk' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                                    {{ $surat->jenis_surat === 'masuk' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="p-2">{{ $surat->pengirim }}</td>
                            <td class="p-2">{{ $surat->tujuan ?? $surat->penerima ?? '-' }}</td>
                            <td class="p-2">{{ $surat->perihal }}</td>
                            <td class="p-2 text-gray-500">{{ \Carbon\Carbon::parse($surat->tanggal)->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-400">
                                Belum ada surat terbaru
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ✅ Ambil data chart dari variabel Livewire
    const chartLabels = {!! json_encode($chartLabels ?? []) !!};
    const chartValues = {!! json_encode($chartData ?? []) !!};
    const distribusiMasuk = {{ $distribusiData['Masuk'] ?? 0 }};
    const distribusiKeluar = {{ $distribusiData['Keluar'] ?? 0 }};
    const distribusiDisposisi = {{ $distribusiData['Disposisi'] ?? 0 }};

    // --- Bar Chart ---
    const barCanvas = document.getElementById('barChart');
    if (barCanvas) {
        const ctxBar = barCanvas.getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Surat Masuk',
                    data: chartValues,
                    backgroundColor: 'rgba(59,130,246,0.6)',
                    borderColor: 'rgba(37,99,235,1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 28,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E3A8A',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(200,200,200,0.15)' },
                        ticks: { color: '#6B7280' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6B7280' }
                    }
                }
            }
        });
    }

    // --- Pie Chart ---
    const pieCanvas = document.getElementById('pieChart');
    if (pieCanvas) {
        const ctxPie = pieCanvas.getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Surat Masuk', 'Surat Keluar', 'Disposisi'],
                datasets: [{
                    data: [distribusiMasuk, distribusiKeluar, distribusiDisposisi],
                    backgroundColor: ['#3b82f6', '#22c55e', '#f59e0b'],
                    hoverOffset: 6,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', font: { size: 12 }, boxWidth: 10, padding: 16 }
                    }
                }
            }
        });
    }
});
</script>
@endpush
