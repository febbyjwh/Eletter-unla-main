<div class="p-4 sm:p-6 bg-gray-100 min-h-screen">

    <h2 class="text-lg sm:text-xl font-bold mb-6 text-gray-800">
        👥 Manajemen User
    </h2>

    {{-- Alert Success --}}
    @if (session()->has('success'))
        <div
            class="bg-green-50 text-green-700 px-4 py-2 rounded-2xl mb-4 shadow-sm border border-green-200 text-sm sm:text-base">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Alert Error --}}
    @if (session()->has('error'))
        <div
            class="bg-red-50 text-red-700 px-4 py-2 rounded-2xl mb-4 shadow-sm border border-red-200 text-sm sm:text-base">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Tombol Tambah --}}
    <button
        wire:click="openModal(false)"
        class="bg-gradient-to-r from-blue-400 to-blue-500 text-white px-4 py-2 rounded-xl mb-5 text-sm sm:text-base shadow hover:from-blue-500 hover:to-blue-600 transition-all duration-200">
        + Tambah User
    </button>

    {{-- Filter & Search --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">

        <input
            type="text"
            wire:model.live="search"
            placeholder="🔍 Cari user..."
            class="w-full sm:w-64 border rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">

        <select
            wire:model.live="filterRole"
            class="border rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">

            <option value="">-- Semua Role --</option>

            @foreach ($roles as $r)
                <option value="{{ $r->id }}">
                    {{ ucfirst($r->name) }}
                </option>
            @endforeach

        </select>

        <select
            wire:model.live="perPage"
            class="border rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">

            <option value="10">10 / halaman</option>
            <option value="25">25 / halaman</option>
            <option value="50">50 / halaman</option>
            <option value="100">100 / halaman</option>

        </select>
    </div>

    {{-- Table --}}
    <div class="w-full overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">

        <table class="min-w-[1200px] text-sm border-collapse w-full">

            <thead class="bg-gray-50 border-b text-gray-700">
                <tr>
                    <th class="p-3 text-left">No</th>

                    <th
                        class="p-3 text-left cursor-pointer"
                        wire:click="sortBy('name')">
                        Nama
                        {!! $sortField === 'name'
                            ? ($sortDirection === 'asc' ? '⬆️' : '⬇️')
                            : '' !!}
                    </th>

                    <th
                        class="p-3 text-left hidden sm:table-cell cursor-pointer"
                        wire:click="sortBy('email')">
                        Email
                        {!! $sortField === 'email'
                            ? ($sortDirection === 'asc' ? '⬆️' : '⬇️')
                            : '' !!}
                    </th>

                    <th class="p-3 text-left">Role</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                @forelse ($users as $index => $user)

                    <tr class="border-b hover:bg-gray-50 transition">

                        {{-- No --}}
                        <td class="p-3">
                            {{ $users->firstItem() + $index }}
                        </td>

                        {{-- Nama --}}
                        <td class="p-3 font-medium">
                            {{ $user->name }}
                        </td>

                        {{-- Email --}}
                        <td class="p-3 hidden sm:table-cell">
                            {{ $user->email }}
                        </td>

                        {{-- Role --}}
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                {{ ucfirst($user->role?->name ?? 'Guest') }}
                            </span>
                        </td>

                        {{-- Status --}}
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

                        {{-- Aksi --}}
                        <td class="p-3">

                            <div class="flex flex-wrap items-center gap-2">

                                {{-- Approve untuk user pending --}}
                                @if ($user->status == 0)

                                    <select
                                        wire:model.live="selectedRole.{{ $user->id }}"
                                        class="rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                    >
                                        <option value="">
                                            Pilih Role
                                        </option>

                                        @foreach ($roles->where('id', '!=', 5) as $role)
                                            <option value="{{ $role->id }}">
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach

                                    </select>

                                    <button
                                        type="button"
                                        wire:click="approve({{ $user->id }})"
                                        class="px-3 py-2 rounded-xl bg-green-500 text-white hover:bg-green-600 shadow"
                                    >
                                        ✅ Approve
                                    </button>

                                @endif

                                {{-- Edit --}}
                                <button
                                    type="button"
                                    wire:click="openModal(true, {{ $user->id }})"
                                    class="px-3 py-2 rounded-xl text-xs bg-yellow-400 text-white shadow hover:bg-yellow-500"
                                >
                                    ✏️ Edit
                                </button>

                                {{-- Delete --}}
                                <button
                                    type="button"
                                    wire:click="delete({{ $user->id }})"
                                    onclick="return confirm('Yakin hapus user ini?')"
                                    class="px-3 py-2 rounded-xl text-xs bg-red-500 text-white shadow hover:bg-red-600"
                                >
                                    🗑️ Hapus
                                </button>

                            </div>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-500">
                            🙅 Belum ada data user.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>