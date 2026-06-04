{{-- resources/views/livewire/manajemen-role.blade.php --}}
<div class="p-4 sm:p-6 bg-gray-100 min-h-screen">

    <h2 class="text-lg sm:text-3xl font-bold mb-6 text-gray-800">Manajemen Role</h2>

    @if (session()->has('success'))
        <div class="bg-green-50 text-green-700 px-4 py-2 rounded-2xl mb-4 shadow-sm border border-green-200 text-sm sm:text-base">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (auth()->user()->hasPermission('manage_roles'))
        <button wire:click="openModal(false)"
            class="bg-gradient-to-r from-blue-400 to-blue-500 text-white px-4 py-2 rounded-xl mb-3 text-sm sm:text-base shadow hover:from-blue-500 hover:to-blue-600 transition-all">
            + Tambah Role
        </button>
    @endif

    <!-- Search & Per Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-3">

        <div class="relative w-full sm:w-64">
            <div class="absolute ml-2 inset-y-0 left-0 flex items-center pl-3">
                <i class="fi fi-rr-search text-gray-400 text-sm leading-none"></i>
            </div>
            <input type="text" wire:model.live="search" placeholder="Cari role..."
                class="w-full rounded-xl border py-2 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
        </div>

        <div class="relative">
            <select wire:model.live="perPage"
                class="appearance-none rounded-2xl border border-gray-200 bg-white py-2.5 pl-4 pr-10 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:border-blue-300 focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none cursor-pointer">
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

    <!-- TABLE -->
    <div class="w-full p-3 overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
        <table class="w-full text-xs sm:text-sm border-collapse table-auto">

            <thead class="text-gray-600 border-b bg-gray-50/70">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('name')">
                        Nama {!! $sortField === 'name' ? ($sortDirection === 'asc' ? '⬆️' : '⬇️') : '↕' !!}
                    </th>
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('description')">
                        Deskripsi {!! $sortField === 'description' ? ($sortDirection === 'asc' ? '⬆️' : '⬇️') : '↕' !!}
                    </th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($roles as $role)
                    <tr class="border-b hover:bg-gray-50/70 transition">
                        <td class="p-3">{{ $roles->firstItem() + $loop->index }}</td>
                        <td class="p-3 font-semibold text-gray-800">{{ $role->name }}</td>
                        <td class="p-3 text-gray-500 text-xs">{{ $role->description ?? '-' }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                @can('manage_roles')
                                    <button wire:click="openModal(true, {{ $role->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs bg-amber-400 text-white rounded-xl hover:bg-amber-500 transition duration-200">
                                        <i class="fi fi-rr-pencil leading-none"></i> Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $role->id }})"
                                        class="px-3 py-1.5 text-xs bg-red-500 text-white rounded-xl hover:bg-red-600 transition duration-200">
                                        <i class="fi fi-rr-trash"></i> Hapus
                                    </button>
                                    <button wire:click="openPermissionModal({{ $role->id }})"
                                        class="px-3 py-1.5 text-xs bg-green-500 text-white rounded-xl hover:bg-green-600 transition duration-200">
                                        <i class="fi fi-rr-settings"></i> Permissions
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-500">Belum ada data role.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div class="mt-4">{{ $roles->links() }}</div>

    <!-- MODAL TAMBAH / EDIT -->
    @if ($showModal)
        @can('manage_roles')
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
                <div class="w-full max-w-lg overflow-hidden rounded-[28px] bg-white shadow-2xl border border-gray-100">

                    <!-- macOS-style header -->
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <button wire:click="closeModal" class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-600 transition"></button>
                            <button wire:click="minimize" class="w-3 h-3 rounded-full bg-yellow-400 hover:bg-yellow-500 transition"></button>
                            <button wire:click="toggleFullscreen" class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-600 transition"></button>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">
                            {{ $isEdit ? '✏️ Edit Role' : '➕ Tambah Role' }}
                        </span>
                        <div class="w-14"></div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-5">
                        <div class="mb-5">
                            <h3 class="text-base font-bold text-gray-800">{{ $isEdit ? 'Edit Role' : 'Tambah Role' }}</h3>
                            <p class="text-sm text-gray-400 mt-1">{{ $isEdit ? 'Perbarui data role' : 'Tambahkan role baru ke sistem' }}</p>
                        </div>

                        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="space-y-4">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Nama Role</label>
                                <input type="text" wire:model="name" placeholder="Masukkan nama role"
                                    class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea wire:model="description" rows="3" placeholder="Masukkan deskripsi role"
                                    class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition resize-none"></textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                                <button type="button" wire:click="closeModal"
                                    class="rounded-2xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="rounded-2xl bg-blue-500 px-5 py-2.5 text-sm font-medium text-white shadow-md hover:bg-blue-600 transition">
                                    {{ $isEdit ? 'Update Role' : 'Simpan Role' }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        @endcan
    @endif

    <!-- MODAL PERMISSIONS -->
    @if ($showPermissionModal)
        @can('manage_roles')
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
                <div class="w-full max-w-lg overflow-hidden rounded-[28px] bg-white shadow-2xl border border-gray-100">

                    <!-- macOS-style header -->
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <button wire:click="closePermissionModal" class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-600 transition"></button>
                            <button wire:click="minimizePermissionModal" class="w-3 h-3 rounded-full bg-yellow-400 hover:bg-yellow-500 transition"></button>
                            <button wire:click="toggleFullscreenPermission" class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-600 transition"></button>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">⚙️ Permissions: {{ $roleName }}</span>
                        <div class="w-14"></div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-5">
                        <div class="mb-5">
                            <h3 class="text-base font-bold text-gray-800">Permissions Role: {{ $roleName }}</h3>
                            <p class="text-sm text-gray-400 mt-1">Centang permission yang berlaku untuk role ini</p>
                        </div>

                        <form wire:submit.prevent="savePermissions" class="space-y-3">

                            @foreach ($allPermissions as $permission)
                                <label class="flex items-center gap-3 p-3 rounded-2xl border border-gray-200 bg-gray-50 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition text-sm text-gray-700">
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                        class="w-4 h-4 accent-blue-500 cursor-pointer">
                                    {{ $permission->description }}
                                </label>
                            @endforeach

                            <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                                <button type="button" wire:click="closePermissionModal"
                                    class="rounded-2xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="rounded-2xl bg-green-500 px-5 py-2.5 text-sm font-medium text-white shadow-md hover:bg-green-600 transition">
                                    Simpan Permissions
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        @endcan
    @endif

</div>