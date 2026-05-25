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
        class="bg-gradient-to-r from-blue-400 to-blue-500 text-white px-4 py-2 rounded-xl mb-5 text-sm sm:text-base shadow hover:from-blue-500 hover:to-blue-600 transition-all duration-200">
        + Tambah User
    </button>

    <!-- Filter & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
        <div class="relative w-full sm:w-64">
            <div class="absolute ml-2 inset-y-0 left-0 flex items-center pl-3">
                <i class="fi fi-rr-search text-gray-400 text-sm leading-none"></i>
            </div>

            <input type="text" wire:model.live="search" placeholder="Cari nama / email..."
                class="w-full rounded-xl border py-2 pl-10 pr-3 text-sm
               focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
        </div>

        {{-- <select wire:model.live="filterRole"
            class="border rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            <option value="">-- Semua Role --</option>
            @foreach ($roles as $r)
                <option value="{{ $r->id }}">{{ $r->name }}</option>
            @endforeach
        </select> --}}

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
        <table class="min-w-[1200px] text-xs sm:text-sm border-collapse">
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
                            {{ $user->unit ?: '-' }}
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
                            <div class="flex flex-wrap gap-2">

                                @if ($user->status == 0)
                                    <select wire:model.live="selectedRole.{{ $user->id }}"
                                        class="rounded-xl border border-gray-300 px-3 py-2 text-sm">

                                        <option value="">
                                            Pilih Role
                                        </option>

                                        @foreach ($roles->whereIn('id', [1, 2]) as $role)
                                            <option value="{{ $role->id }}">
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" wire:click="approve({{ $user->id }})"
                                        class="flex items-center gap-2 px-3 py-2 rounded-xl bg-green-500 text-white hover:bg-green-600 shadow transition">

                                        <i class="fi fi-sr-badge-check"></i>
                                        <span>Approve</span>
                                    </button>
                                @endif

                                <button wire:click="openModal(true, {{ $user->id }})"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs bg-yellow-400 text-white shadow hover:bg-yellow-500 transition">

                                    <i class="fi fi-sr-pencil"></i>
                                    <span>Edit</span>
                                </button>

                                <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin hapus user ini?"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs bg-red-500 text-white shadow hover:bg-red-600 transition">

                                    <i class="fi fi-sr-trash"></i>
                                    <span>Hapus</span>
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
        <div class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50 px-4">
            @if ($isMinimized)
                <div class="fixed bottom-6 left-6 z-50">
                    <div class="flex items-center gap-3 bg-white/90 backdrop-blur-md rounded-full px-3 py-2 shadow-lg border border-gray-200 cursor-pointer"
                        wire:click="restore">
                        <div class="flex items-center gap-2">
                            <button wire:click.stop="closeModal"
                                class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-600"></button>
                            <button wire:click.stop="restore"
                                class="w-3 h-3 rounded-full bg-yellow-400 hover:bg-yellow-500"></button>
                            <button wire:click.stop="toggleFullscreen"
                                class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-600"></button>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-800">
                            {{ $isEdit ? '✏️ Edit User' : '➕ Tambah User' }}
                        </span>
                    </div>
                </div>
            @else
                <div
                    class="@if ($isFullscreen) w-full max-w-none h-[90vh] max-h-[90vh] rounded-xl @else w-full max-w-lg rounded-3xl @endif
                            bg-white/95 backdrop-blur-xl shadow-2xl border border-gray-200 overflow-hidden transition-all duration-200">
                    <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border-b">
                        <div class="flex items-center space-x-2">
                            <button wire:click="closeModal"
                                class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-600"></button>
                            <button wire:click="minimize"
                                class="w-3 h-3 rounded-full bg-yellow-400 hover:bg-yellow-500"></button>
                            <button wire:click="toggleFullscreen"
                                class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-600"></button>
                        </div>
                        <h3 class="text-sm sm:text-base font-semibold text-gray-700">
                            {{ $isEdit ? '✏️ Edit User' : '➕ Tambah User' }}
                        </h3>
                        <div class="w-6"></div>
                    </div>

                    <div class="p-4 sm:p-6 overflow-auto h-full">
                        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="space-y-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Nama</label>
                                <input type="text" wire:model="name"
                                    class="w-full border border-gray-300 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Email</label>
                                <input type="email" wire:model="email"
                                    class="w-full border border-gray-300 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Unit</label>
                                <input type="text" wire:model="unit"
                                    class="w-full border border-gray-300 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                                    placeholder="Contoh: Fakultas Teknik, TU, Prodi Informatika">
                                @error('unit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Password</label>
                                <input type="password" wire:model="password"
                                    class="w-full border border-gray-300 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                                    placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : '' }}">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Role</label>
                                <select wire:model="role"
                                    class="w-full border border-gray-300 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-wrap justify-end gap-3 pt-3">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm shadow-sm transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-400 to-blue-500 text-white text-sm shadow hover:from-blue-500 hover:to-blue-600 transition">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    @endif

</div>
