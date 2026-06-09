{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<div class="p-4 sm:p-6 bg-gray-100 min-h-screen">

    <h2 class="text-lg sm:text-3xl font-bold mb-6 text-gray-800">
        Manajemen Arsip Surat
    </h2>

    @if (session()->has('message'))
        <div
            class="bg-green-50 text-green-700 px-4 py-2 rounded-2xl mb-4 shadow-sm border border-green-200 text-sm sm:text-base">
            ✅ {{ session('message') }}
        </div>
    @endif

    <!-- 🔘 Tombol Tambah -->
    <button wire:click="openModal"
        class="bg-gradient-to-r from-blue-400 to-blue-500 text-white px-4 py-2 rounded-xl mb-3 text-sm sm:text-base shadow hover:from-blue-500 hover:to-blue-600 transition-all">
        + Tambah Arsip
    </button>

    <!-- 🔍 Search & Per Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-3">

        <div class="relative w-full sm:w-64">
            <div class="absolute ml-2 inset-y-0 left-0 flex items-center pl-3">
                <i class="fi fi-rr-search text-gray-400 text-sm leading-none"></i>
            </div>

            <input type="text" wire:model.live="search" placeholder="Cari no surat / perihal..."
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

    <!-- TABLE -->
    <div class="w-full p-3 overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">

        <table class="w-full text-xs sm:text-sm border-collapse table-auto">

            <thead class="text-gray-600 border-b bg-gray-50/70">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">No Surat</th>
                    <th class="p-3 text-left">Jenis</th>
                    <th class="p-3 text-left">Pengirim</th>
                    <th class="p-3 text-left">Penerima</th>
                    <th class="p-3 text-left">Perihal</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">File</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($arsip as $index => $item)
                    <tr class="border-b hover:bg-gray-50/70 transition">

                        <td class="p-3">
                            {{ $arsip->firstItem() + $index }}
                        </td>

                        <td class="p-3 font-medium">
                            {{ $item->no_surat }}
                        </td>

                        <td class="p-3">
                            @if ($item->jenis_surat === 'masuk')
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600">
                                    <i class="fi fi-sr-inbox-in"></i></i> Masuk
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">
                                    <i class="fi fi-sr-paper-plane"></i> Keluar
                                </span>
                            @endif
                        </td>

                        <td>
                            {{ $item->pengirim }}
                            <div class="text-xs text-gray-400 mt-1">
                                Dikirim oleh: {{ $item->unitPengirim->nama_unit ?? '-' }}
                            </div>
                        </td>

                        <td>
                            {{ $item->penerima }}
                            <div class="text-xs text-gray-400 mt-1">
                                Diterima oleh: {{ $item->unitPenerima->nama_unit ?? '-' }}
                            </div>
                        </td>

                        {{-- Perihal + metadata --}}
                        <td class="p-3">
                            <div class="font-medium text-gray-800">
                                {{ $item->perihal }}
                            </div>

                            <div class="text-xs text-gray-400 mt-1">
                                dibuat oleh
                                <span class="font-medium">
                                    {{ optional($item->creator)->name ?? 'System' }}
                                </span>
                            </div>
                        </td>

                        {{-- Tanggal + update info --}}
                        <td class="p-3">
                            <div class="text-gray-800">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </div>

                            <div class="text-xs text-gray-600 mt-1">
                                update {{ $item->updated_at->diffForHumans() }}
                            </div>

                            <div class="text-[11px] text-gray-500">
                                create {{ $item->updated_at->format('H:i') }}
                            </div>
                        </td>
                        {{-- <td class="p-3">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td> --}}

                        <td class="p-3">
                            @if ($item->file_surat)
                                <a href="{{ $item->file_surat }}" target="_blank"
                                    class="font-medium text-blue-500 hover:bg-blue-200 hover:text-blue-500 px-2 py-1 rounded-full">
                                    <i class="fi fi-rr-file"></i> Preview
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-2">

                                <button
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs
                   bg-amber-400 text-white rounded-xl
                   hover:bg-amber-500 transition duration-200"
                                    wire:click="edit({{ $item->id }})">

                                    <i class="fi fi-rr-pencil leading-none"></i>
                                    Edit
                                </button>

                                <button wire:click="confirmDelete({{ $item->id }})"
                                    class="px-3 py-1 text-xs bg-red-500 text-white rounded-xl hover:bg-red-600">

                                    <i class="fi fi-rr-trash"></i>
                                    Hapus
                                </button>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center p-4 text-gray-500">
                            Belum ada data arsip
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $arsip->links() }}
    </div>

    <!-- 📦 MODAL TAMBAH -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">

            <div class="w-full max-w-2xl overflow-hidden rounded-[28px] bg-white shadow-2xl border border-gray-100">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $isEdit ? 'Edit Arsip' : 'Tambah Arsip' }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            {{ $isEdit ? 'Perbarui data arsip surat' : 'Tambahkan arsip surat baru' }}
                        </p>
                    </div>

                    <button wire:click="closeModal"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition hover:bg-red-100 hover:text-red-500">

                        <i class="fi fi-rr-cross-small text-lg"></i>
                    </button>
                </div>

                <!-- Body -->
                <form wire:submit.prevent="save" class="max-h-[75vh] overflow-y-auto p-6 space-y-5">

                    <!-- Jenis Surat -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Jenis Surat
                        </label>

                        <select wire:model="jenis_surat"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm
                           focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">

                            <option value="">Pilih Jenis Surat</option>
                            <option value="masuk">📥 Surat Masuk</option>
                            <option value="keluar">📤 Surat Keluar</option>
                        </select>
                    </div>

                    <!-- Grid Form -->
                    <div class="grid gap-4 sm:grid-cols-2">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                No Surat
                            </label>

                            <input wire:model="no_surat" placeholder="Masukkan nomor surat"
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm
                               focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Tanggal
                            </label>

                            <input type="date" wire:model="tanggal"
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm
                               focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Pengirim
                            </label>

                            <input wire:model="pengirim" placeholder="Nama pengirim"
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm
                               focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Penerima
                            </label>

                            <input wire:model="penerima" placeholder="Nama penerima"
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm
                               focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                        </div>

                    </div>

                    <!-- Perihal -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Perihal
                        </label>

                        <input wire:model="perihal" placeholder="Masukkan perihal surat"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm
                           focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                    </div>

                    <!-- Upload -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Upload File
                        </label>

                        <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 p-5 text-center">

                            <input type="file" wire:model="new_file"
                                class="block w-full text-sm text-gray-500
                               file:mr-4 file:rounded-xl
                               file:border-0 file:bg-blue-100
                               file:px-4 file:py-2
                               file:text-sm file:font-medium
                               file:text-blue-600
                               hover:file:bg-blue-200">

                            @error('new_file')
                                <p class="mt-3 text-sm text-red-500">{{ $message }}</p>
                            @enderror

                            <!-- Loading -->
                            <div wire:loading wire:target="new_file"
                                class="mt-3 flex items-center justify-center gap-2 text-sm text-blue-500">

                                <i class="fi fi-rr-spinner animate-spin"></i>
                                Uploading file...
                            </div>

                            <!-- File lama -->
                            {{-- @if ($file_surat)
                                <a href="{{ asset('storage/' . $file_surat) }}" target="_blank"
                                    class="mt-3 inline-flex items-center gap-2 text-sm text-blue-600 hover:underline">

                                    <i class="fi fi-rr-eye"></i>
                                    Preview file lama
                                </a>
                            @endif --}}
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">

                        <button type="button" wire:click="closeModal"
                            class="rounded-2xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">

                            Batal
                        </button>

                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="cursor-pointer rounded-2xl bg-blue-500 px-5 py-2.5 text-sm font-medium text-white shadow-md transition hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">

                            <span wire:loading.remove wire:target="save">
                                {{ $isEdit ? 'Update Arsip' : 'Simpan Arsip' }}
                            </span>

                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" class="opacity-25" />
                                    <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4"
                                        class="opacity-75" />
                                </svg>

                                Menyimpan...
                            </span>

                        </button>

                    </div>

                </form>
            </div>
        </div>
    @endif
    <div wire:loading.flex wire:target="save,edit,openModal,deleteConfirmed"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 items-center justify-center">

        <div class="bg-white px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3">

            <svg class="w-5 h-5 animate-spin text-blue-500" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                    class="opacity-25" />
                <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" class="opacity-75" />
            </svg>

            <span class="text-sm text-gray-700">
                Memproses data...
            </span>
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
                borderRadius: 20,
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
