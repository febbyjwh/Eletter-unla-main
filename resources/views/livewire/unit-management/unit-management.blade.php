{{-- resources/views/livewire/manajemen-unit.blade.php --}}
<div class="p-4 sm:p-6 bg-gray-100 min-h-screen">

    <h2 class="text-lg sm:text-3xl font-bold mb-6 text-gray-800">
        Manajemen Unit
    </h2>

    @if (session()->has('message'))
        <div
            class="bg-green-50 text-green-700 px-4 py-2 rounded-2xl mb-4 shadow-sm border border-green-200 text-sm sm:text-base">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tombol Tambah -->
    {{-- <button wire:click="openModal"
        class="bg-gradient-to-r from-blue-400 to-blue-500 text-white px-4 py-2 rounded-xl mb-3 text-sm sm:text-base shadow hover:from-blue-500 hover:to-blue-600 transition-all">
        + Tambah Unit
    </button> --}}

    <!-- Search & Per Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-3">

        <div class="relative w-full sm:w-64">
            <div class="absolute ml-2 inset-y-0 left-0 flex items-center pl-3">
                <i class="fi fi-rr-search text-gray-400 text-sm leading-none"></i>
            </div>
            <input type="text" wire:model.live="search" placeholder="Cari kode / nama unit..."
                class="w-full rounded-xl border py-2 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
        </div>

        <div class="relative">
            <select wire:model.live="perPage"
                class="appearance-none rounded-2xl border border-gray-200 bg-white py-2.5 pl-4 pr-10 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:border-blue-300 focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none cursor-pointer">
                <option value="10">10 / halaman</option>
                <option value="25">25 / halaman</option>
                <option value="50">50 / halaman</option>
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
                    <th class="p-3 text-left">Kode</th>
                    <th class="p-3 text-left">Nama Unit</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($units as $index => $unit)
                    <tr class="border-b hover:bg-gray-50/70 transition">

                        <td class="p-3">{{ $units->firstItem() + $index }}</td>
                        <td class="p-3 font-medium text-gray-800">{{ $unit->kode_unit }}</td>
                        <td class="p-3 text-gray-700">{{ $unit->nama_unit }}</td>
                        <td class="p-3 text-gray-500">{{ $unit->email }}</td>
                        <td class="p-3">
                            @if ($unit->status == 1)
                                <span
                                    class="inline-block px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                    Aktif
                                </span>
                            @elseif($unit->status == 0)
                                <span
                                    class="inline-block px-3 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                    Nonaktif
                                </span>
                            @else
                                <span
                                    class="inline-block px-3 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                    -
                                </span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex items-center gap-2">

                                @if ($unit->status == 0)
                                    <button wire:click="approve({{ $unit->unit_id }})" wire:loading.attr="disabled"
                                        class="px-3 py-1.5 text-xs bg-green-500 text-white rounded-xl hover:bg-green-600 transition duration-200">
                                        <i class="fi fi-rr-check"></i> Approve
                                    </button>
                                @endif

                                <button wire:click="edit({{ $unit->unit_id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs bg-amber-400 text-white rounded-xl hover:bg-amber-500 transition duration-200">
                                    <i class="fi fi-rr-pencil leading-none"></i> Edit
                                </button>

                                <button wire:click="confirmDelete({{ $unit->unit_id }})"
                                    class="px-3 py-1.5 text-xs bg-red-500 text-white rounded-xl hover:bg-red-600 transition duration-200">
                                    <i class="fi fi-rr-trash"></i> Hapus
                                </button>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-10 text-gray-400">
                            <i class="fi fi-rr-folder-open text-2xl block mb-2"></i>
                            Belum ada data unit
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $units->links() }}
    </div>

    <!-- MODAL TAMBAH / EDIT -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="w-full max-w-lg overflow-hidden rounded-[28px] bg-white shadow-2xl border border-gray-100">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $isEdit ? 'Edit Unit' : 'Tambah Unit' }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $isEdit ? 'Perbarui data unit' : 'Tambahkan data unit baru' }}
                        </p>
                    </div>
                    <button wire:click="closeModal"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition hover:bg-red-100 hover:text-red-500">
                        <i class="fi fi-rr-cross-small text-lg"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4">

                    {{-- <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Kode Unit</label>
                        <input wire:model="kode_unit" placeholder="Masukkan kode unit"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                        @error('kode_unit')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nama Unit</label>
                        <input wire:model="nama_unit" placeholder="Masukkan nama unit"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                        <input wire:model="email" placeholder="Masukkan email"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                        @error('email')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Status
                        </label>

                        <select wire:model="status"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm mr-6">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                        <button wire:click="closeModal"
                            class="rounded-2xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                            Batal
                        </button>
                        <button wire:click="save"
                            class="cursor-pointer rounded-2xl bg-blue-500 px-5 py-2.5 text-sm font-medium text-white shadow-md transition hover:bg-blue-600">
                            {{ $isEdit ? 'Update Unit' : 'Simpan Unit' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <div wire:loading.flex class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 items-center justify-center">
        <div class="bg-white px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3">
            <svg class="w-5 h-5 animate-spin text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="text-sm text-gray-700">Sedang memproses...</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-delete-confirmation', (event) => {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteConfirmed', {
                        id: event.id
                    });
                }
            });
        });
    });
</script>
