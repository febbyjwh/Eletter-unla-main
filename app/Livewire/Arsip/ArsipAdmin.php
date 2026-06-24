<?php

namespace App\Livewire\Arsip;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Controllers\GoogleController;
use App\Services\GoogleDriveService;
use App\Models\Arsip;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArsipAdmin extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;

    public $isModalOpen = false;
    public $isEdit = false;

    public $arsipId;

    // form fields
    public $jenis_surat, $no_surat, $pengirim, $pembuat, $penerima, $tujuan, $penanda_tangan, $pengupload, $perihal, $tanggal;
    public $file_surat, $new_file;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->jenis_surat = 'masuk';
        $this->isEdit = false;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    // public function mount()
    // {
    //     dd(
    //         auth()->check(),
    //         auth()->id(),
    //         request()->cookie()
    //     );
    // }

    public function save()
    {
        $this->validate([
            'jenis_surat' => 'required',
            'no_surat' => 'required',
            'pengirim' => 'required',
            'pembuat' => 'nullable|string|max:150',
            'penerima' => 'nullable|string|max:150',
            'tujuan' => 'nullable|string|max:150',
            'penanda_tangan' => 'nullable|string|max:150',
            'pengupload' => 'nullable|string|max:150',
            'perihal' => 'required',
            'tanggal' => 'required|date',
            'new_file' => ($this->isEdit ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($this->jenis_surat === 'masuk' && blank($this->penerima)) {
            $this->addError('penerima', 'Penerima wajib diisi untuk surat masuk.');
            return;
        }

        if ($this->jenis_surat === 'keluar' && blank($this->tujuan)) {
            $this->addError('tujuan', 'Tujuan wajib diisi untuk surat keluar.');
            return;
        }

        $filePath = $this->file_surat;

        if ($this->new_file) {
            $unit = auth()->user()?->unit;

            if ($unit?->google_access_token && $unit?->google_drive_folder_id) {
                try {
                    $upload = GoogleController::uploadFileToDrive(
                        $this->new_file,
                        $this->jenis_surat,
                        $this->tanggal
                    );

                    $filePath = $upload['url'] ?? null;
                } catch (\Exception $e) {
                    $filePath = $this->storeFileLocally();
                    session()->flash('message', 'File disimpan lokal karena upload Google Drive gagal: ' . $e->getMessage());
                }
            } else {
                $filePath = $this->storeFileLocally();
            }
        }

        if (!$filePath) {
            $this->addError('new_file', 'File wajib diupload.');
            return;
        }

        $pengirim = auth()->user();
        $unitId = $pengirim->unit_id;

        $penerimaUser = \App\Models\User::where('email', $this->penerima)->first();

        $data = [
            'jenis_surat' => $this->jenis_surat,
            'no_surat' => $this->no_surat,
            'pengirim' => $this->pengirim,
            'pembuat' => $this->jenis_surat === 'keluar' ? $this->pembuat : null,
            'penerima' => $this->jenis_surat === 'masuk' ? $this->penerima : $this->tujuan,
            'tujuan' => $this->tujuan,
            'penanda_tangan' => $this->penanda_tangan,
            'pengupload' => $this->pengupload,
            'pengirim_user_id' => $pengirim->id,
            'pengirim_unit_id' => $pengirim->unit_id,
            'penerima_user_id' => $penerimaUser?->id,
            'penerima_unit_id' => $penerimaUser?->unit_id,
            'perihal' => $this->perihal,
            'tanggal' => $this->tanggal,
            'file_surat' => $filePath,
        ];
        if ($this->isEdit) {
            $this->arsipQuery()->findOrFail($this->arsipId)->update($data);
        } else {
            Arsip::create(array_merge($data, [
                'user_id' => auth()->id(),
                'unit_id' => auth()->user()->unit_id,
                'unit_pengirim_id' => $this->jenis_surat === 'keluar' ? $unitId : null,
                'unit_penerima_id' => $this->jenis_surat === 'masuk' ? $unitId : null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_role_id' => auth()->user()->role_id,
                'updated_role_id' => auth()->user()->role_id,
            ]));
        }

        $this->dispatch('arsipUpdated');
        $this->closeModal();
    }

    public function edit($id)
    {
        $arsip = $this->arsipQuery()->findOrFail($id);

        $this->arsipId = $arsip->id;
        $this->jenis_surat = $arsip->jenis_surat;
        $this->no_surat = $arsip->no_surat;
        $this->pengirim = $arsip->pengirim;
        $this->pembuat = $arsip->pembuat;
        $this->penerima = $arsip->penerima;
        $this->tujuan = $arsip->tujuan;
        $this->penanda_tangan = $arsip->penanda_tangan;
        $this->pengupload = $arsip->pengupload;
        $this->perihal = $arsip->perihal;
        $this->tanggal = $arsip->tanggal;

        // simpan file lama (jangan langsung overwrite)
        $this->file_surat = $arsip->file_surat;
        $this->new_file = null;

        $this->isEdit = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $arsip = $this->arsipQuery()->findOrFail($id);

        // hapus file kalau ada
        if ($arsip->file_surat && !Str::startsWith($arsip->file_surat, ['http://', 'https://']) && Storage::disk('public')->exists($arsip->file_surat)) {
            Storage::disk('public')->delete($arsip->file_surat);
        }

        $arsip->delete();

        session()->flash('message', 'Arsip berhasil dihapus');

        // refresh komponen (kalau ada live update)
        $this->dispatch('arsipUpdated');
    }

    #[On('deleteConfirmed')]
    public function handleDelete($id)
    {
        $this->delete($id);
    }

    public function resetForm()
    {
        $this->reset([
            'arsipId',
            'jenis_surat',
            'no_surat',
            'pengirim',
            'pembuat',
            'penerima',
            'tujuan',
            'penanda_tangan',
            'pengupload',
            'perihal',
            'tanggal',
            'file_surat',
            'new_file',
        ]);

        $this->isEdit = false;
    }

    public function confirmDelete($id)
    {
        $this->dispatch('show-delete-confirmation', id: $id);
    }

    protected function arsipQuery()
    {
        $user = auth()->user();

        $query = Arsip::query();

        // Super Admin lihat semua arsip
        if ((int) $user->role_id === 1) {
            return $query->orderBy('tanggal', 'desc');
        }

        // Unit dan User biasa sama-sama lihat semua arsip dalam unit yang sama
        return $query->where(function ($q) use ($user) {
            $q->where('unit_id', $user->unit_id)
                ->orWhere('unit_pengirim_id', $user->unit_id)
                ->orWhere('unit_penerima_id', $user->unit_id)
                ->orWhereHas('user', function ($userQuery) use ($user) {
                    $userQuery->where('unit_id', $user->unit_id);
                });
        })->orderBy('tanggal', 'desc');
    }

    private function storeFileLocally(): string
    {
        $extension = $this->new_file->getClientOriginalExtension();
        $fileName = Str::slug($this->no_surat . '_' . $this->tanggal . '_' . $this->perihal, '_') . '.' . $extension;

        return $this->new_file->storeAs('arsip_files', $fileName, 'public');
    }

    public function render()
    {
        // dd(auth()->id(), auth()->user()->role_id);
        $arsip = $this->arsipQuery()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('no_surat', 'like', "%{$this->search}%")
                        ->orWhere('perihal', 'like', "%{$this->search}%")
                        ->orWhere('pengirim', 'like', "%{$this->search}%")
                        ->orWhere('pembuat', 'like', "%{$this->search}%")
                        ->orWhere('penerima', 'like', "%{$this->search}%")
                        ->orWhere('tujuan', 'like', "%{$this->search}%")
                        ->orWhere('penanda_tangan', 'like', "%{$this->search}%")
                        ->orWhere('pengupload', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.arsip.arsip-admin', compact('arsip'));
    }
}
