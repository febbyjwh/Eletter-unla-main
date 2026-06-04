<?php

namespace App\Livewire\UnitManagement;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class UnitManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public $unitId;
    public $nama_unit;
    public $kode_unit;
    public $deskripsi;

    public $isModalOpen = false;
    public $isEdit = false;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'nama_unit' => 'required|string|max:255',
        'kode_unit' => 'required|string|max:50',
        'deskripsi' => 'nullable|string',
    ];

    public function render()
    {
        $units = Unit::query()
            ->where('nama_unit', 'like', '%' . $this->search . '%')
            ->orWhere('kode_unit', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.unit-management.unit-management', [
            'units' => $units
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function save()
    {
        $this->validate();

        Unit::updateOrCreate(
            ['id' => $this->unitId],
            [
                'nama_unit' => $this->nama_unit,
                'kode_unit' => $this->kode_unit,
                'deskripsi' => $this->deskripsi,
            ]
        );

        session()->flash(
            'message',
            $this->isEdit
                ? 'Unit berhasil diperbarui'
                : 'Unit berhasil ditambahkan'
        );

        $this->closeModal();
        $this->resetForm();
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);

        $this->unitId = $unit->id;
        $this->nama_unit = $unit->nama_unit;
        $this->kode_unit = $unit->kode_unit;
        $this->deskripsi = $unit->deskripsi;

        $this->isEdit = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Unit::findOrFail($id)->delete();

        session()->flash('message', 'Unit berhasil dihapus');
    }

    private function resetForm()
    {
        $this->reset([
            'unitId',
            'nama_unit',
            'kode_unit',
            'deskripsi'
        ]);

        $this->isEdit = false;
    }
}