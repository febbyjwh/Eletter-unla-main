<div class="p-4 sm:p-6 bg-gray-100 min-h-screen">
    <h2 class="text-lg sm:text-xl font-bold mb-6 text-gray-800">Manajemen Role</h2>

    @if (session()->has('success'))
        <div class="bg-green-50 text-green-700 px-4 py-2 rounded-2xl mb-4 shadow-sm border border-green-200 text-sm sm:text-base">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Tombol Tambah -->
    @if (auth()->user()->hasPermission('manage_roles'))
        <button wire:click="openModal(false)"
            class="bg-gradient-to-r from-blue-400 to-blue-500 text-white px-4 py-2 rounded-xl mb-5 text-sm sm:text-base shadow hover:from-blue-500 hover:to-blue-600 transition-all duration-200">
            + Tambah Role
        </button>
    @endif

    <!-- Filter & Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
        <!-- Search -->
        <input type="text" wire:model.live="search"
               placeholder="🔍 Cari role..."
               class="w-full sm:w-64 border rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">

        <!-- Per page -->
        <select wire:model.live="perPage"
                class="border rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            <option value="10">10 / halaman</option>
            <option value="25">25 / halaman</option>
            <option value="50">50 / halaman</option>
            <option value="100">100 / halaman</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto p-3 bg-white/80 backdrop-blur-md rounded-2xl shadow-lg border border-gray-200">
        <table class="w-full text-xs sm:text-sm">
            <thead class="text-gray-600 border-b bg-gray-50/70">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('name')">
                        Nama {!! $sortField === 'name' ? ($sortDirection === 'asc' ? '⬆️' : '⬇️') : '' !!}
                    </th>
                    <th class="p-3 text-left cursor-pointer" wire:click="sortBy('description')">
                        Deskripsi {!! $sortField === 'description' ? ($sortDirection === 'asc' ? '⬆️' : '⬇️') : '' !!}
                    </th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($roles as $role)
                    <tr class="border-b hover:bg-gray-50/70 transition">
                        <!-- NOMOR URUT -->
                        <td class="p-3">
                            {{ $roles->firstItem() + $loop->index }}
                        </td>
                        <td class="p-3 font-medium text-gray-800">{{ $role->name }}</td>
                        <td class="p-3">{{ $role->description ?? '-' }}</td>
                        <td class="p-3 flex gap-2">
                            @can('manage_roles')
                                <button wire:click="openModal(true, {{ $role->id }})"
                                    class="px-3 py-1 rounded-xl text-xs bg-yellow-400 text-white shadow hover:bg-yellow-500">
                                    ✏️ Edit
                                </button>
                                <button wire:click="delete({{ $role->id }})"
                                    class="px-3 py-1 rounded-xl text-xs bg-red-500 text-white shadow hover:bg-red-600"
                                    onclick="return confirm('Yakin ingin menghapus role ini?')">
                                    🗑️ Hapus
                                </button>
                                <button wire:click="openPermissionModal({{ $role->id }})"
                                    class="px-3 py-1 rounded-xl text-xs bg-green-500 text-white shadow hover:bg-green-600">
                                    ⚙️ Permissions
                                </button>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endcan
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

    <div class="mt-4">
        {{ $roles->links() }}
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50 px-4">
            {{-- MINIMIZED BAR --}}
            @if($isMinimized)
                <div class="fixed bottom-6 left-6 z-50">
                    <div
                        class="flex items-center gap-3 bg-white/90 backdrop-blur-md rounded-full px-3 py-2 shadow-lg border border-gray-200 cursor-pointer"
                        wire:click="restore">
                        <div class="flex items-center gap-2">
                            <button wire:click.stop="closeModal" class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-600"></button>
                            <button wire:click.stop="restore" class="w-3 h-3 rounded-full bg-yellow-400 hover:bg-yellow-500"></button>
                            <button wire:click.stop="toggleFullscreen" class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-600"></button>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-800">
                            {{ $isEdit ? '✏️ Edit Role' : '➕ Tambah Role' }}
                        </span>
                    </div>
                </div>
            @else
                {{-- NORMAL / FULLSCREEN MODAL --}}
                @can('manage_roles')
                    <div
                        class="@if($isFullscreen) w-full max-w-none h-[90vh] max-h-[90vh] rounded-xl @else w-full max-w-lg rounded-3xl @endif
                            bg-white/95 backdrop-blur-xl shadow-2xl border border-gray-200 overflow-hidden transition-all duration-200">
                        <!-- Header ala macOS -->
                        <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border-b">
                            <div class="flex items-center space-x-2">
                                <button wire:click="closeModal" class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-600"></button>
                                <button wire:click="minimize" class="w-3 h-3 rounded-full bg-yellow-400 hover:bg-yellow-500"></button>
                                <button wire:click="toggleFullscreen" class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-600"></button>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-gray-700">
                                {{ $isEdit ? '✏️ Edit Role' : '➕ Tambah Role' }}
                            </h3>
                            <div class="w-6"></div>
                        </div>

                        <!-- Body -->
                        <div class="p-4 sm:p-6 overflow-auto h-full">
                            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="space-y-4">
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-600">Nama</label>
                                    <input type="text" wire:model="name"
                                        class="w-full border border-gray-300 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-600">Deskripsi</label>
                                    <textarea wire:model="description"
                                        class="w-full border border-gray-300 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"></textarea>
                                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex justify-end gap-3 pt-3">
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
                @endcan
            @endif
        </div>
    @endif

    <!-- PERMISSION -->
    @if ($showPermissionModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50 px-4">
            {{-- MINIMIZED BAR --}}
            @if($isMinimized)
                <div class="fixed bottom-6 left-6 z-50">
                    <div class="flex items-center gap-3 bg-white/90 backdrop-blur-md rounded-full px-3 py-2 shadow-lg border border-gray-200 cursor-pointer" wire:click="restorePermissionModal">
                        <div class="flex items-center gap-2">
                            <button wire:click.stop="closePermissionModal" class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-600"></button>
                            <button wire:click.stop="restorePermissionModal" class="w-3 h-3 rounded-full bg-yellow-400 hover:bg-yellow-500"></button>
                            <button wire:click.stop="toggleFullscreenPermission" class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-600"></button>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-800">
                            ⚙️ Permissions
                        </span>
                    </div>
                </div>
            @else
                {{-- NORMAL / FULLSCREEN MODAL --}}
                @can('manage_roles')
                    <div class="@if($isFullscreenPermission) max-w-none h-[90vh] max-h-[90vh] rounded-xl @else w-full max-w-lg rounded-3xl @endif
                                bg-white/95 backdrop-blur-xl shadow-2xl border border-gray-200 overflow-hidden transition-all duration-200">
                        <!-- Header ala macOS -->
                        <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border-b">
                            <div class="flex items-center space-x-2">
                                <button wire:click="closePermissionModal" class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-600"></button>
                                <button wire:click="minimizePermissionModal" class="w-3 h-3 rounded-full bg-yellow-400 hover:bg-yellow-500"></button>
                                <button wire:click="toggleFullscreenPermission" class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-600"></button>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-gray-700">
                                ⚙️ Permissions Role: {{ $roleName }}
                            </h3>
                            <div class="w-6"></div>
                        </div>

                        <!-- Body -->
                        <div class="p-4 sm:p-6 overflow-auto h-full">
                            <form wire:submit.prevent="savePermissions" class="space-y-4">
                                @foreach($allPermissions as $permission)
                                <div class="flex items-center gap-2">
                                    {{-- <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" id="perm-{{ $permission->id }}"> --}}
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" id="perm-{{ $permission->id }}">
                                    {{-- <label for="perm-{{ $permission->id }}" class="text-sm text-gray-700">{{ $permission->name }}</label> --}}
                                    <label for="perm-{{ $permission->id }}" class="text-sm text-gray-700">{{ $permission->description }}</label>
                                </div>
                                @endforeach

                                <div class="flex justify-end gap-3 pt-3">
                                    <button type="button" wire:click="closePermissionModal"
                                            class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm shadow-sm transition">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 rounded-xl bg-gradient-to-r from-green-400 to-green-500 text-white text-sm shadow hover:from-green-500 hover:to-green-600 transition">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif
        </div>
    @endif

</div>
