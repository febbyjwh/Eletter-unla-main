<?php

namespace App\Livewire\UserManagement;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    // Form fields
    public $name, $email, $unit_id, $password, $role_id, $userId;
    public $isEdit = false;
    public $showModal = false;

    // Modal states (macOS style)
    public $isMinimized = false;
    public $isFullscreen = false;
    public $selectedRole = [];

    // Table controls
    public $search = '';
    public $filterRole = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'unit_id' => 'nullable|exists:units,id',
        'password' => 'required|min:6',
        'role' => 'required'
    ];

    // Reset pagination when search/filter/perPage updated
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterRole()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with('role', 'unit');
        // dd($query->toSql(), $query->getBindings());

        // Search by name/email/unit
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('unit', function ($q) {
                        $q->where('nama_unit', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filter by role
        if ($this->filterRole) {
            $query->where('role_id', $this->filterRole);
        }

        // Allowed sort fields
        $allowedSort = ['name', 'email', 'unit', 'created_at'];
        $sortField = in_array($this->sortField, $allowedSort) ? $this->sortField : 'name';

        $users = $query->orderBy($sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $units = Unit::orderBy('nama_unit')->get();

        return view('livewire.user-management.user-management', [
            'users' => $users,
            'roles' => Role::all(),
            'units' => $units,
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

    public function openModal($edit = false, $id = null)
    {
        $this->resetForm();
        $this->isEdit = $edit;
        $this->isMinimized = false;
        $this->isFullscreen = false;

        if ($edit && $id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->unit_id = $user->unit_id;
            $this->role_id = $user->role_id;
        }

        $this->showModal = true;
    }

    public function createUser()
    {
        $this->openModal(false);
    }

    public function editUser($id)
    {
        $this->openModal(true, $id);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->unit_id = '';
        $this->password = '';
        $this->role_id = '';
        $this->userId = null;
        $this->isEdit = false;
    }

    // macOS modal controls
    public function minimize()
    {
        $this->isMinimized = true;
    }

    public function toggleFullscreen()
    {
        $this->isFullscreen = ! $this->isFullscreen;
    }

    public function restore()
    {
        $this->isMinimized = false;
    }

    public function store()
    {
        // dd([
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'unit_id' => $this->unit_id,
        //     'role' => $this->role,
        // ]);
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'unit_id' => 'nullable|exists:unit,unit_id',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'unit_id' => $this->unit_id ?: null,
            'password' => Hash::make($this->password),
            'role_id' => $this->role_id,
            'status' => 0,
        ]);
        // dd($user->toArray());

        session()->flash('success', 'User berhasil ditambahkan!');
        $this->closeModal();
        $this->resetForm();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'unit_id' => 'nullable|exists:unit,unit_id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'unit_id' => $this->unit_id ?: null,
            'role_id' => $this->role_id,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        session()->flash('success', 'User berhasil diupdate!');
        $this->closeModal();
        $this->resetForm();
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'User berhasil dihapus!');
    }

    public function approve($id)
    {
        if (!isset($this->selectedRole[$id]) || empty($this->selectedRole[$id])) {
            session()->flash('error', 'Pilih role terlebih dahulu.');
            return;
        }

        $user = User::findOrFail($id);

        $user->update([
            'role_id' => $this->selectedRole[$id],
            'status' => 1,
        ]);

        if ($user->unit_id) {
            Unit::where('unit_id', $user->unit_id)
                ->update([
                    'status' => 1,
                ]);
        }

        unset($this->selectedRole[$id]);

        session()->flash('success', 'User berhasil diverifikasi!');
    }
}
