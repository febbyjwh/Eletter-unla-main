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

class ArsipAdmin extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;

    public $isModalOpen = false;
    public $isEdit = false;

    public $arsipId;

    // form fields
    public $jenis_surat, $no_surat, $pengirim, $penerima, $perihal, $tanggal;
    public $file_surat, $new_file;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }


    public function save()
    {
        $this->validate([
            'jenis_surat' => 'required',
            'no_surat' => 'required',
            'pengirim' => 'required',
            'penerima' => 'required',
            'perihal' => 'required',
            'tanggal' => 'required|date',
            'new_file' => ($this->isEdit ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $filePath = $this->file_surat;

        if ($this->new_file) {
            try {

                $upload = GoogleController::uploadFileToDrive(
                    $this->new_file,
                    $this->jenis_surat,
                    $this->tanggal
                );

                $filePath = $upload['url'] ?? null;
            } catch (\Exception $e) {
                $this->addError('new_file', $e->getMessage());
                return;
            }
        }

        if (!$filePath) {
            $this->addError('new_file', 'File wajib diupload.');
            return;
        }

        $pengirim = auth()->user();

        $penerimaUser = \App\Models\User::where('email', $this->penerima)->first();

        $data = [
            'jenis_surat' => $this->jenis_surat,
            'no_surat' => $this->no_surat,
            'pengirim' => $this->pengirim,
            'penerima' => $this->penerima,
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
                'unit_pengirim_id' => auth()->user()->unit_id,
                'unit_penerima_id' => auth()->user()->unit_id,
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
        $this->penerima = $arsip->penerima;
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
        if ($arsip->file_surat && Storage::disk('public')->exists($arsip->file_surat)) {
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
            'penerima',
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

        if ($user->role_id == 1) {
            return $query->orderBy('tanggal', 'desc');
        }

        if ($user->role_id == 3) {
            $query->where(function ($q) use ($user) {
                $q->where('unit_pengirim_id', $user->unit_id)
                    ->orWhere('unit_penerima_id', $user->unit_id);
            });
            return $query->orderBy('tanggal', 'desc');
        }

        return $query->where('user_id', $user->id)
            ->orderBy('tanggal', 'desc');
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
                        ->orWhere('penerima', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.arsip.arsip-admin', compact('arsip'));
    }
}
