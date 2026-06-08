<?php

namespace App\Livewire\UnitManagement;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

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
        if ($this->unitId) {
            return [
                'kode_unit' => 'required|string|max:50|unique:unit,kode_unit,' . $this->unitId . ',unit_id',
                'nama_unit' => 'required|string|max:255',
                'email'     => 'nullable|email|max:255|unique:unit,email,' . $this->unitId . ',unit_id',
                'status'    => 'required|in:0,1',
            ];
        } else {
            return [
                'kode_unit' => 'required|string|max:50|unique:unit,kode_unit',
                'nama_unit' => 'required|string|max:255',
                'email'     => 'nullable|email|max:255|unique:unit,email',
                'status'    => 'required|in:0,1',
            ];
        }
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
        $messages = [
            'kode_unit.required' => 'Kode unit wajib diisi.',
            'kode_unit.unique'   => 'Kode unit sudah digunakan.',
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'status.in'          => 'Status tidak valid.',
        ];

        $this->validate($this->rules(), $messages);

        $unit = Unit::updateOrCreate(
            ['unit_id' => $this->unitId],
            [
                'kode_unit' => $this->kode_unit,
                'nama_unit' => $this->nama_unit,
                'status'    => $this->status,
            ]
        );

        // update email akun unit
        if ($unit->user) {

            $unit->user->update([
                'email' => $this->email,
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
        $unit = Unit::find($unitId);

        if (!$unit) {
            session()->flash('error', 'Unit tidak ditemukan.');
            return;
        }

        $unit->status = 1;
        $unit->save();

        session()->flash('message', "Unit {$unit->nama_unit} berhasil di-approve!");
    }
}
