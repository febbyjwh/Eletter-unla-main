<div class="p-4 sm:p-6 bg-gray-100 min-h-screen">

    <h2 class="text-lg sm:text-3xl font-bold mb-6 text-gray-800">Manajemen User</h2>

    @if (session()->has('success'))
        <div
            class="bg-green-50 text-green-700 px-4 py-2 rounded-2xl mb-4 shadow-sm border border-green-200 text-sm sm:text-base">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Tombol Tambah -->
    <button wire:click="openModal(false)"
        class="bg-gradient-to-r from-blue-400 to-blue-500 text-white px-4 py-2 rounded-xl mb-3 text-sm sm:text-base shadow hover:from-blue-500 hover:to-blue-600 transition-all">
        + Tambah User
    </button>

    <!-- Filter & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-3">

        <div class="relative w-full sm:w-64">
            <div class="absolute ml-2 inset-y-0 left-0 flex items-center pl-3">
                <i class="fi fi-rr-search text-gray-400 text-sm leading-none"></i>
            </div>
            <input type="text" wire:model.live="search" placeholder="Cari nama / email..."
                class="w-full rounded-xl border py-2 pl-10 pr-3 text-sm
                       focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
        </div>

        <div class="relative w-100">
            <select wire:model.live="perPage"
                class="appearance-none rounded-2xl border border-gray-200
                       bg-white py-2.5 pl-4 pr-10 text-sm font-medium text-gray-700
                       shadow-sm transition-all duration-200
                       hover:border-blue-300
                       focus:border-blue-400 focus:ring-4 focus:ring-blue-100
                       focus:outline-none cursor-pointer">
                <option value="10">10 / halaman</option>
                <option value="25">25 / halaman</option>
                <option value="50">50 / halaman</option>
                <option value="100">100 / halaman</option>
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <i class="fi fi-rr-angle-small-down text-gray-400 text-sm"></i>
            </div>
        </div>

    </div>

    <!-- Table -->
    <div class="w-full p-3 overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
        <table class="w-full text-xs sm:text-sm border-collapse table-auto">
            <thead class="text-gray-600 border-b bg-gray-50/70">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left cursor-pointer select-none" wire:click="sortBy('name')">
                        <div class="flex items-center gap-2">
                            Nama
                            @if ($sortField === 'name')
                                <i
                                    class="fi {{ $sortDirection === 'asc' ? 'fi-rr-sort-up' : 'fi-rr-sort-down' }} text-xs text-blue-500"></i>
                            @else
                                <i class="fi fi-rr-sort text-xs text-gray-400"></i>
                            @endif
                        </div>
                    </th>
                    <th class="p-3 text-left hidden sm:table-cell cursor-pointer select-none"
                        wire:click="sortBy('email')">
                        <div class="flex items-center gap-2">
                            Email
                            @if ($sortField === 'email')
                                <i
                                    class="fi {{ $sortDirection === 'asc' ? 'fi-rr-sort-up' : 'fi-rr-sort-down' }} text-xs text-blue-500"></i>
                            @else
                                <i class="fi fi-rr-sort text-xs text-gray-400"></i>
                            @endif
                        </div>
                    </th>
                    <th class="p-3 text-left cursor-pointer select-none" wire:click="sortBy('unit')">
                        <div class="flex items-center gap-2">
                            Unit
                            @if ($sortField === 'unit')
                                <i
                                    class="fi {{ $sortDirection === 'asc' ? 'fi-rr-sort-up' : 'fi-rr-sort-down' }} text-xs text-blue-500"></i>
                            @else
                                <i class="fi fi-rr-sort text-xs text-gray-400"></i>
                            @endif
                        </div>
                    </th>
                    <th class="p-3 text-left">Role</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($users as $index => $user)
                    <tr class="border-b hover:bg-gray-50/70 transition">

                        <td class="p-3">
                            {{ $users->firstItem() + $index }}
                        </td>

                        <td class="p-3 font-medium">
                            {{ $user->name }}
                        </td>

                        <td class="p-3 hidden sm:table-cell">
                            {{ $user->email }}
                        </td>

                        <td class="p-3">
                            {{ $user->unit?->nama_unit ?? '-' }}
                        </td>

                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded-full text-xs
                                {{ ($user->role?->name ?? '-') === 'Admin'
                                    ? 'bg-red-100 text-red-700'
                                    : (($user->role?->name ?? '-') === 'User'
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-gray-100 text-gray-700') }}">
                                {{ $user->role?->name ?? '-' }}
                            </span>
                        </td>

                        <td class="p-3">
                            @if ($user->status == 1)
                                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex flex-wrap items-center gap-2">

                                @if ($user->status == 0)
                                    <select wire:model.live="selectedRole.{{ $user->id }}"
                                        class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                                        <option value="">Pilih Role</option>
                                        @foreach ($roles->whereIn('id', [1, 2, 3]) as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" wire:click="approve({{ $user->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs
                                               bg-green-500 text-white rounded-xl hover:bg-green-600 transition duration-200">
                                        <i class="fi fi-sr-badge-check leading-none"></i>
                                        Approve
                                    </button>
                                @endif

                                <button wire:click="openModal(true, {{ $user->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs
                                           bg-amber-400 text-white rounded-xl hover:bg-amber-500 transition duration-200">
                                    <i class="fi fi-rr-pencil leading-none"></i>
                                    Edit
                                </button>

                                <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin hapus user ini?"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs
                                           bg-red-500 text-white rounded-xl hover:bg-red-600 transition duration-200">
                                    <i class="fi fi-rr-trash leading-none"></i>
                                    Hapus
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">
                            🙅 Belum ada data user.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">

            <div class="w-full max-w-2xl overflow-hidden rounded-[28px] bg-white shadow-2xl border border-gray-100">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $isEdit ? 'Edit User' : 'Tambah User' }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $isEdit ? 'Perbarui data user yang ada' : 'Tambahkan user baru ke sistem' }}
                        </p>
                    </div>

                    <button wire:click="closeModal"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition hover:bg-red-100 hover:text-red-500">
                        <i class="fi fi-rr-cross-small text-lg"></i>
                    </button>
                </div>

                <!-- Body -->
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}"
                    class="max-h-[75vh] overflow-y-auto p-6 space-y-5">

                    <!-- Grid: Nama & Email -->
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" wire:model="name" placeholder="Masukkan nama lengkap"
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm
                                       focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" wire:model="email" placeholder="Masukkan email"
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm
                                       focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Grid: Unit & Role -->
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Unit</label>
                            <select wire:model="unit_id"
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-500
                                       focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                                <option value="">-- Pilih Unit --</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->unit_id }}">{{ $unit->nama_unit }}</option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Role</label>
                            <select wire:model="selectedRole.{{ $user->id }}">
                                <option value="">Pilih Role</option>

                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div x-data="{ show: false }">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password"
                                placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}"
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm
                                       focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-gray-600">
                                <i x-show="!show" class="fi fi-rr-eye"></i>
                                <i x-show="show" class="fi fi-rr-eye-crossed"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                        <button type="button" wire:click="closeModal"
                            class="rounded-2xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="cursor-pointer rounded-2xl bg-blue-500 px-5 py-2.5 text-sm font-medium text-white shadow-md transition hover:bg-blue-600">
                            {{ $isEdit ? 'Update User' : 'Simpan User' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

</div>
