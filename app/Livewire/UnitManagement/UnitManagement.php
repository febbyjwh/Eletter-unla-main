<?php

namespace App\Livewire\UnitManagement;

use App\Models\Unit;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class UnitManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;

    public $unitId;
    public $kode_unit;
    public $nama_unit;
    public $email;
    public $status = 1;

    public $isModalOpen = false;
    public $isEdit = false;



    protected function rules()
    {
        return [
            'kode_unit' => $this->unitId
                ? 'nullable|string|max:50|unique:unit,kode_unit,' . $this->unitId . ',unit_id'
                : 'nullable|string|max:50|unique:unit,kode_unit',
            'nama_unit' => 'nullable|string|max:255',
            'email'     => $this->unitId
                ? 'nullable|email|max:255|unique:unit,email,' . $this->unitId . ',unit_id'
                : 'nullable|email|max:255|unique:unit,email',
            'status'    => 'required|in:0,1',
        ];
    }

    public function render()
    {
        $units = Unit::with('user')
            ->where(function ($query) {
                $query->where('kode_unit', 'like', "%{$this->search}%")
                    ->orWhere('nama_unit', 'like', "%{$this->search}%")
                    ->orWhereHas('user', function ($q) {
                        $q->where('email', 'like', "%{$this->search}%");
                    });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.unit-management.unit-management', [
            'units' => $units,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($unitId = null)
    {
        $this->resetForm();
        if ($unitId) {
            $this->edit($unitId);
        } else {
            $this->isEdit = false;
        }
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'nama_unit' => $this->nama_unit,
            'email'     => $this->email,
            'status'    => $this->status,
        ];

        // hanya generate saat tambah
        if (!$this->unitId) {
            $data['kode_unit'] = 'UNIT-' . strtoupper(Str::random(6));
        }

        $unit = Unit::updateOrCreate(
            ['unit_id' => $this->unitId],
            $data
        );

        if ($unit->user) {
            $unit->user->update([
                'email'  => $this->email,
                'status' => $this->status,
            ]);
        }

        session()->flash(
            'message',
            $this->isEdit
                ? 'Unit berhasil diperbarui'
                : 'Unit berhasil ditambahkan'
        );

        $this->closeModal();
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);

        $this->unitId = $unit->unit_id;
        $this->kode_unit = $unit->kode_unit;
        $this->nama_unit = $unit->nama_unit;
        $this->email = $unit->user?->email;
        $this->status = $unit->status;

        $this->isEdit = true;
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->dispatch('show-delete-confirmation', id: $id);
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed($id)
    {
        Unit::findOrFail($id)->delete();

        session()->flash('message', 'Unit berhasil dihapus');
    }

    private function resetForm()
    {
        $this->reset([
            'unitId',
            'kode_unit',
            'nama_unit',
            'email',
        ]);

        $this->status = 0;
        $this->isEdit = false;
    }

    public function approve($unitId)
    {
        $unit = Unit::where('unit_id', $unitId)->firstOrFail();

        try {
            $tokenData = json_decode($unit->google_access_token, true);

            $client = new Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));

            $client->setAccessToken([
                'access_token'  => $tokenData['access_token'],
                'refresh_token' => $unit->google_refresh_token,
                'expires_in'    => $tokenData['expires_in'] ?? 3600,
                'created'       => $tokenData['created'] ?? time(),
            ]);

            if ($client->isAccessTokenExpired()) {
                $newToken = $client->fetchAccessTokenWithRefreshToken($unit->google_refresh_token);

                $unit->update([
                    'google_access_token' => json_encode($newToken),
                    'google_token_expires_at' => now()->addSeconds($newToken['expires_in'] ?? 3600),
                ]);
            }

            $drive = new Drive($client);

            // helper aman
            $getOrCreateFolder = function ($name, $parentId = null) use ($drive) {
                $q = "mimeType='application/vnd.google-apps.folder' and name='{$name}' and trashed=false";

                if ($parentId) {
                    $q .= " and '{$parentId}' in parents";
                }

                $files = $drive->files->listFiles([
                    'q' => $q,
                    'fields' => 'files(id,name)'
                ]);

                if (count($files->files)) {
                    return $files->files[0]->id;
                }

                $meta = new DriveFile([
                    'name' => $name,
                    'mimeType' => 'application/vnd.google-apps.folder',
                    'parents' => $parentId ? [$parentId] : [],
                ]);

                return $drive->files->create($meta, ['fields' => 'id'])->id;
            };

            // ROOT
            $rootId = $getOrCreateFolder('E-Letter - ' . $unit->nama_unit);

            // safe check
            if (!$rootId) {
                throw new \Exception('Root folder gagal dibuat');
            }

            $yearId  = $getOrCreateFolder((string) now()->year, $rootId);
            // $monthId = $getOrCreateFolder(now()->translatedFormat('F'), $yearId);

            $getOrCreateFolder('Surat Masuk', $yearId);
            $getOrCreateFolder('Surat Keluar', $yearId);

            $unit->update([
                'google_drive_folder_id' => $rootId,
                'status' => 1,
            ]);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());

            $unit->update(['status' => 1]);
        }

        User::where('unit_id', $unitId)->update(['status' => 1]);

        return back()->with('success', "Unit {$unit->nama_unit} berhasil diaktifkan.");
    }

    public function rejectUnit($unitId)
    {
        Unit::where('unit_id', $unitId)->update(['status' => 2]);
        User::where('unit_id', $unitId)->update(['status' => 2]);

        return back()->with('success', 'Unit berhasil ditolak.');
    }
}
