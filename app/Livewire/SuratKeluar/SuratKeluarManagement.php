<?php

namespace App\Livewire\SuratKeluar;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SuratKeluar;

class SuratKeluarManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Form fields
    public $suratId, $no_surat, $pengirim, $pembuat, $tujuan, $penanda_tangan, $pengupload, $penerima, $perihal, $tanggal, $file_surat, $existingFile, $preview;

    // UI states
    public $isEdit = false;
    public $showModal = false;      // nama lama (kompatibilitas)
    public $isModalOpen = false;    // nama baru (Blade)
    public $isMinimized = false;
    public $isFullscreen = false;

    // Table controls
    public $search = '';
    public $perPage = 10;
    public $sortField = 'tanggal';
    public $sortDirection = 'desc';

    protected $rules = [
        'no_surat'   => 'required|string|max:100',
        'pengirim'   => 'required|string|max:150',
        'pembuat'    => 'nullable|string|max:150',
        'tujuan'     => 'required|string|max:150',
        'penanda_tangan' => 'nullable|string|max:150',
        'pengupload' => 'nullable|string|max:150',
        'perihal'    => 'required|string|max:200',
        'tanggal'    => 'required|date',
        'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function render()
    {
        $query = SuratKeluar::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_surat', 'like', '%'.$this->search.'%')
                  ->orWhere('pengirim', 'like', '%'.$this->search.'%')
                  ->orWhere('pembuat', 'like', '%'.$this->search.'%')
                  ->orWhere('tujuan', 'like', '%'.$this->search.'%')
                  ->orWhere('penanda_tangan', 'like', '%'.$this->search.'%')
                  ->orWhere('pengupload', 'like', '%'.$this->search.'%')
                  ->orWhere('perihal', 'like', '%'.$this->search.'%');
            });
        }

        $allowedSort = ['no_surat', 'pengirim', 'pembuat', 'tujuan', 'penanda_tangan', 'pengupload', 'perihal', 'tanggal'];
        $sortField = in_array($this->sortField, $allowedSort) ? $this->sortField : 'tanggal';

        $surats = $query->orderBy($sortField, $this->sortDirection)
                        ->paginate($this->perPage);

        return view('livewire.surat-keluar.surat-keluar-management', [
            'surats' => $surats
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    /**
     * Open modal.
     * Parameter $id = null -> create mode; $id = number -> edit mode
     */
    public function openModal($id = null)
    {
        $this->resetForm();
        $this->isEdit = $id ? true : false;
        $this->suratId = $id;

        if ($id) {
            $surat = SuratKeluar::findOrFail($id);
            $this->no_surat     = $surat->no_surat;
            $this->pengirim     = $surat->pengirim;
            $this->pembuat      = $surat->pembuat;
            $this->tujuan       = $surat->tujuan ?? $surat->penerima;
            $this->penanda_tangan = $surat->penanda_tangan;
            $this->pengupload   = $surat->pengupload;
            $this->penerima     = $surat->penerima;
            $this->perihal      = $surat->perihal;
            $this->tanggal      = $surat->tanggal;
            $this->existingFile = $surat->file_surat;
        }

        // keep both flags in sync
        $this->showModal = $this->isModalOpen = true;
        $this->isMinimized = false;
        $this->isFullscreen = false;
    }

    public function closeModal()
    {
        $this->showModal = $this->isModalOpen = false;
    }

    public function resetForm()
    {
        $this->suratId = null;
        $this->no_surat = '';
        $this->pengirim = '';
        $this->pembuat = '';
        $this->tujuan = '';
        $this->penanda_tangan = '';
        $this->pengupload = '';
        $this->penerima = '';
        $this->perihal = '';
        $this->tanggal = '';
        $this->file_surat = null;
        $this->existingFile = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'no_surat'        => $this->no_surat,
            'pengirim'        => $this->pengirim,
            'pembuat'         => $this->pembuat,
            'tujuan'          => $this->tujuan,
            'penanda_tangan'  => $this->penanda_tangan,
            'pengupload'      => $this->pengupload,
            'penerima'        => $this->tujuan,
            'perihal'         => $this->perihal,
            'tanggal'         => $this->tanggal,
            'user_id'         => auth()->id(),
            'updated_by'      => auth()->id(),
            'updated_role_id' => auth()->user()->role_id ?? null,
        ];

        if ($this->file_surat) {
            if ($this->suratId) {
                $old = SuratKeluar::find($this->suratId);
                if ($old && $old->file_surat && Storage::disk('public')->exists($old->file_surat)) {
                    Storage::disk('public')->delete($old->file_surat);
                }
            }

            $extension = $this->file_surat->getClientOriginalExtension();
            $namaFile = Str::slug($this->no_surat . '_' . $this->tanggal . '_' . $this->pengirim . '_' . $this->perihal, '_') . '.' . $extension;

            $data['file_surat'] = $this->file_surat->storeAs('surat_keluar_files', $namaFile, 'public');
        } elseif ($this->existingFile) {
            $data['file_surat'] = $this->existingFile;
        }

        if ($this->suratId) {
            SuratKeluar::find($this->suratId)->update($data);
            session()->flash('message', 'Surat keluar berhasil diperbarui.');
        } else {
            $data['created_by'] = auth()->id();
            $data['created_role_id'] = auth()->user()->role_id ?? null;

            SuratKeluar::create($data);
            session()->flash('message', 'Surat keluar berhasil ditambahkan.');
        }

        $this->closeModal();
        $this->resetForm();
    }

    public function update()
    {
        // tetap sediakan jika ada kode lain memanggil update()
        $this->store();
    }

    public function delete($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
            Storage::disk('public')->delete($surat->file_surat);
        }

        $surat->delete();
        session()->flash('message', 'Surat keluar berhasil dihapus.');
    }

    // Modal controls
    public function minimize() { $this->isMinimized = true; }
    public function restore() { $this->isMinimized = false; }
    public function toggleFullscreen() { $this->isFullscreen = !$this->isFullscreen; }
}
