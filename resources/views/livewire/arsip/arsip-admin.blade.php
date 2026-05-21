<div class="p-4 sm:p-6 bg-gray-100 min-h-screen">

    <h2 class="text-lg sm:text-xl font-bold mb-6 text-gray-800">
        📦 Manajemen Arsip Surat
    </h2>

    @if (session()->has('message'))
        <div
            class="bg-green-50 text-green-700 px-4 py-2 rounded-2xl mb-4 shadow-sm border border-green-200 text-sm sm:text-base">
            ✅ {{ session('message') }}
        </div>
    @endif

    <!-- 🔘 Tombol Tambah -->
    <button wire:click="openModal"
        class="bg-gradient-to-r from-blue-400 to-blue-500 text-white px-4 py-2 rounded-xl mb-5 text-sm sm:text-base shadow hover:from-blue-500 hover:to-blue-600 transition-all">
        + Tambah Arsip
    </button>

    <!-- 🔍 Search & Per Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">

        <input type="text" wire:model.live="search" placeholder="🔍 Cari no surat / perihal..."
            class="w-full sm:w-64 border rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400">

        <select wire:model.live="perPage" class="border rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400">
            <option value="10">10 / halaman</option>
            <option value="25">25 / halaman</option>
            <option value="50">50 / halaman</option>
            <option value="100">100 / halaman</option>
        </select>

    </div>

    <!-- 📋 TABLE -->
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
                                    📥 Masuk
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">
                                    📤 Keluar
                                </span>
                            @endif
                        </td>

                        {{-- Pengirim --}}
                        <td class="p-3">
                            {{ $item->pengirim }}
                        </td>

                        {{-- Penerima --}}
                        <td class="p-3">
                            {{ $item->penerima }}
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
                                    📄 Preview
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="p-3 flex gap-2">

                            <button class="px-3 py-1 text-xs bg-yellow-400 text-white rounded-xl hover:bg-yellow-500"
                                wire:click="edit({{ $item->id }})">
                                ✏️ Edit
                            </button>

                            <button class="px-3 py-1 text-xs bg-red-500 text-white rounded-xl hover:bg-red-600"
                                wire:click="delete({{ $item->id }})">
                                🗑️ Hapus
                            </button>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center p-4 text-gray-500">
                            🙅 Belum ada data arsip
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
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

            <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">

                <!-- Header -->
                <div class="px-4 py-3 bg-gray-50 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">
                        {{ $isEdit ? '✏️ Edit Arsip' : '➕ Tambah Arsip' }}
                    </h3>

                    <button wire:click="closeModal" class="text-red-500">✖</button>
                </div>

                <!-- Body -->
                <form wire:submit.prevent="save" class="p-5 space-y-3">

                    <!-- Jenis Surat -->
                    <select wire:model="jenis_surat" class="w-full border rounded-xl p-2">
                        <option value="">Pilih Jenis</option>
                        <option value="masuk">Masuk</option>
                        <option value="keluar">Keluar</option>
                    </select>

                    <input wire:model="no_surat" placeholder="No Surat" class="w-full border rounded-xl p-2">
                    <input wire:model="pengirim" placeholder="Pengirim" class="w-full border rounded-xl p-2">
                    <input wire:model="penerima" placeholder="Penerima" class="w-full border rounded-xl p-2">
                    <input wire:model="perihal" placeholder="Perihal" class="w-full border rounded-xl p-2">
                    <input type="date" wire:model="tanggal" class="w-full border rounded-xl p-2">

                    <!-- File -->
                    {{-- <input type="file" wire:model="new_file" class="w-full border rounded-xl p-2">

                    @if ($file_surat)
                        <a href="{{ Storage::url($file_surat) }}" target="_blank"
                            class="text-blue-500 text-sm underline">
                            📄 File lama
                        </a>
                    @endif --}}

                    <!-- File -->
                    <div>
                        <input type="file" wire:model="new_file" class="w-full border rounded-xl p-2">

                        {{-- Loading upload --}}
                        <div wire:loading wire:target="new_file" class="text-blue-500 text-sm mt-1">
                            ⏳ Uploading file...
                        </div>

                        {{-- Preview file lama --}}
                        <td class="p-3">
                            @if ($item->file_surat)
                                <a href="{{ asset('storage/' . $item->file_surat) }}" target="_blank"
                                    class="text-blue-500 underline">
                                    📄 Lihat
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </div>

                    <!-- Action -->
                    <div class="flex justify-end gap-2 pt-3">

                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 rounded-xl">
                            Batal
                        </button>

                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-xl">
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>
    @endif

</div>
