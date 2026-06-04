<div class="p-6 bg-gray-100 min-h-screen">

    <h2 class="text-3xl font-bold mb-6">
        Manajemen Unit
    </h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <button
        wire:click="openModal"
        class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
        + Tambah Unit
    </button>

    <div class="flex justify-between mb-4">

        <input
            type="text"
            wire:model.live="search"
            placeholder="Cari unit..."
            class="border rounded px-3 py-2">

        <select wire:model.live="perPage"
            class="border rounded px-3 py-2">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>

    </div>

    <div class="bg-white rounded shadow overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Kode</th>
                    <th class="p-3 text-left">Nama Unit</th>
                    <th class="p-3 text-left">Deskripsi</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($units as $index => $unit)

                    <tr class="border-b">

                        <td class="p-3">
                            {{ $units->firstItem() + $index }}
                        </td>

                        <td class="p-3">
                            {{ $unit->kode_unit }}
                        </td>

                        <td class="p-3">
                            {{ $unit->nama_unit }}
                        </td>

                        <td class="p-3">
                            {{ $unit->deskripsi }}
                        </td>

                        <td class="p-3">

                            <button
                                wire:click="edit({{ $unit->id }})"
                                class="bg-yellow-500 text-white px-3 py-1 rounded">
                                Edit
                            </button>

                            <button
                                wire:click="delete({{ $unit->id }})"
                                class="bg-red-500 text-white px-3 py-1 rounded">
                                Hapus
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center p-4">
                            Belum ada data unit
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $units->links() }}
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center">

            <div class="bg-white p-6 rounded-xl w-full max-w-lg">

                <h3 class="font-bold text-xl mb-4">
                    {{ $isEdit ? 'Edit Unit' : 'Tambah Unit' }}
                </h3>

                <div class="space-y-4">

                    <input
                        wire:model="kode_unit"
                        placeholder="Kode Unit"
                        class="w-full border rounded px-3 py-2">

                    <input
                        wire:model="nama_unit"
                        placeholder="Nama Unit"
                        class="w-full border rounded px-3 py-2">

                    <textarea
                        wire:model="deskripsi"
                        placeholder="Deskripsi"
                        class="w-full border rounded px-3 py-2"></textarea>

                </div>

                <div class="flex justify-end gap-2 mt-4">

                    <button
                        wire:click="closeModal"
                        class="bg-gray-300 px-4 py-2 rounded">
                        Batal
                    </button>

                    <button
                        wire:click="save"
                        class="bg-blue-500 text-white px-4 py-2 rounded">
                        Simpan
                    </button>

                </div>

            </div>
        </div>
    @endif

</div>