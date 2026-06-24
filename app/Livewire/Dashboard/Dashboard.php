<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
// use App\Models\SuratMasuk;
// use App\Models\SuratKeluar;
use App\Models\Arsip;
use App\Models\Disposisi;

class Dashboard extends Component
{
    // 🔹 Properti untuk menampung data ringkasan
    public $jumlahSuratMasuk = 0;
    public $jumlahSuratKeluar = 0;
    public $jumlahDisposisi = 0;

    // 🔹 Data untuk chart
    public $chartData = [];
    public $chartLabels = [];

    // 🔹 Data untuk distribusi surat
    public $distribusiData = [];

    // 🔹 Data surat terbaru
    public $suratTerbaru = [];

    /**
     * Lifecycle mount — dijalankan saat komponen pertama kali dimuat
     */

    public function mount()
    {
        // dd([
        //     'user' => auth()->user(),
        //     'role' => auth()->user()?->role,
        //     'permissions_relation' => auth()->user()?->role?->permissions,
        //     'permissions_array' => auth()->user()?->role?->permissions?->pluck('name'),
        // ]);
        // dd(auth()->user()->roles);
        
        // 🔸 Hitung jumlah surat masuk, keluar, dan disposisi
        $arsipQuery = $this->arsipQuery();

        $this->jumlahSuratMasuk = (clone $arsipQuery)->where('jenis_surat', 'masuk')->count();
        $this->jumlahSuratKeluar = (clone $arsipQuery)->where('jenis_surat', 'keluar')->count();
        $this->jumlahDisposisi = $this->disposisiQuery()->count();

        // 🔸 Ambil data untuk statistik surat masuk bulanan (bar chart)
        $dataBulanan = (clone $arsipQuery)
            ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->whereYear('tanggal', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // 🔸 Konversi hasil query ke format chart
        $this->chartLabels = [];
        $this->chartData = [];

        // 🔸 Daftar nama bulan Bahasa Indonesia (jika ingin tampil lebih familiar)
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        foreach ($dataBulanan as $item) {
            // Pastikan nama bulan ada di array
            $bulanNama = $namaBulan[$item->bulan] ?? 'Tidak Diketahui';
            $this->chartLabels[] = $bulanNama;
            $this->chartData[] = $item->total;
        }

        // 🔸 Jika tidak ada data sama sekali, tambahkan data default agar Chart.js tetap bisa render
        if (empty($this->chartLabels) || empty($this->chartData)) {
            $this->chartLabels = ['Tidak Ada Data'];
            $this->chartData = [0];
        }

        // 🔸 Data distribusi surat (pie chart)
        $this->distribusiData = [
            'Masuk' => $this->jumlahSuratMasuk,
            'Keluar' => $this->jumlahSuratKeluar,
            'Disposisi' => $this->jumlahDisposisi,
        ];

        // 🔸 Surat masuk terbaru
        $this->suratTerbaru = (clone $arsipQuery)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get(['no_surat', 'jenis_surat', 'pengirim', 'penerima', 'tujuan', 'perihal', 'tanggal']);
    }

    protected function arsipQuery()
    {
        $user = auth()->user();

        $query = Arsip::query();

        if ($user?->role_id == 1) {
            return $query;
        }

        if ($user?->unit_id) {
            return $query->where(function ($q) use ($user) {
                $q->where('unit_pengirim_id', $user->unit_id)
                    ->orWhere('unit_penerima_id', $user->unit_id);
            });
        }

        return $query->where('user_id', $user?->id);
    }

    protected function disposisiQuery()
    {
        $user = auth()->user();

        $query = Disposisi::query();

        if ($user?->role_id == 1) {
            return $query;
        }

        return $query->where('user_id', $user?->id);
    }

    /**
     * Render view dashboard dengan data dinamis
     */
    public function render()
    {
        // Pastikan semua variabel selalu ada untuk menghindari error undefined key
        $chartLabels = $this->chartLabels ?? [];
        $chartData = $this->chartData ?? [];
        $distribusiData = $this->distribusiData ?? [
            'Masuk' => 0,
            'Keluar' => 0,
            'Disposisi' => 0,
        ];

        return view('livewire.dashboard.dashboard', [
            'jumlahSuratMasuk' => $this->jumlahSuratMasuk,
            'jumlahSuratKeluar' => $this->jumlahSuratKeluar,
            'jumlahDisposisi' => $this->jumlahDisposisi,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'distribusiData' => $distribusiData,
            'suratTerbaru' => $this->suratTerbaru,
        ]);
    }
}
