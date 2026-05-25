<?php

namespace App\Livewire\UserManagement;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    // Form fields
    public $name, $email, $unit, $password, $role, $userId;
    public $isEdit = false;
    public $showModal = false;

    // Modal states (macOS style)
    public $isMinimized = false;
    public $isFullscreen = false;

    // Table controls
    public $search = '';
    public $filterRole = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'unit' => 'nullable|string|max:150',
        'password' => 'required|min:6',
        'role' => 'required'
    ];

    // Reset pagination when search/filter/perPage updated
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterRole() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function render()
    {
        $query = User::with('role');

        // Search by name/email/unit
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%')
                  ->orWhere('unit', 'like', '%'.$this->search.'%');
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

        return view('livewire.user-management.user-management', [
            'users' => $users,
            'roles' => Role::all()
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
            $this->unit = $user->unit;
            $this->role = $user->role_id;
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
        $this->unit = '';
        $this->password = '';
        $this->role = '';
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
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'unit' => $this->unit,
            'password' => Hash::make($this->password),
            'role_id' => $this->role,
        ]);

        session()->flash('success', 'User berhasil ditambahkan!');
        $this->closeModal();
        $this->resetForm();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'unit' => 'nullable|string|max:150',
            'role' => 'required',
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'unit' => $this->unit,
            'role_id' => $this->role,
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
}