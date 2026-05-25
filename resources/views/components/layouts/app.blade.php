{{-- @php
    dd([
        'user' => Auth::user(),
        'role' => Auth::user()?->role,
        'permissions' => Auth::user()?->role?->permissions,
    ]);
@endphp --}}
{{-- @php
dd(Gate::abilities());
@endphp --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>E-Letter Dashboard</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Alpine.js (dibutuhkan untuk dropdown) --}}
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

    @livewireStyles
</head>

<body class="bg-gray-100 font-sans">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Overlay (mobile) --}}
    <div id="overlay" class="fixed inset-0 bg-black/50 hidden md:hidden z-40"></div>

    {{-- Main Content --}}
    <div class="flex-1 md:ml-64 p-4 md:p-6">
        {{-- Navbar --}}
        <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow mb-6">
            <div class="flex items-center gap-3">
                {{-- Burger button hanya tampil di mobile --}}
                <button id="menuBtn"
                    class="md:hidden p-2 rounded-lg bg-blue-500 text-white focus:outline-none focus:ring">
                    ☰
                </button>
                <h1 class="text-xl font-bold">E-Letter UNLA</h1>
            </div>

            {{-- Bagian kanan (notif + user dropdown) --}}
            <div class="flex items-center gap-4" x-data="{ notifOpen: false, userOpen: false }">
                {{-- Livewire notifikasi --}}
                @php
                    use App\Models\Arsip;
                    $list = Arsip::where('jenis_surat', 'masuk')->latest()->take(5)->get();

                    $jumlah = Arsip::where('jenis_surat', 'masuk')->count();
                @endphp

                {{-- Notifikasi --}}
                <div x-data="{ notifOpen: false }" class="relative">
                    {{-- Tombol Notifikasi --}}
                    <button @click="notifOpen = !notifOpen" class="p-2 bg-gray-200 rounded-full relative focus:ring">
                        🔔
                        @if ($jumlah > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                                {{ $jumlah }}
                            </span>
                        @endif
                    </button>

                    {{-- Dropdown Notifikasi --}}
                    <div x-show="notifOpen" @click.outside="notifOpen = false" x-transition
                        class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-50">
                        <div class="p-3 border-b text-sm font-semibold text-gray-700">Notifikasi Surat Masuk</div>

                        @if (count($list) > 0)
                            <ul class="max-h-60 overflow-y-auto text-sm">
                                @foreach ($list as $surat)
                                    <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer">
                                        📄 <span class="font-medium">{{ $surat->pengirim }}</span> -
                                        {{ $surat->perihal }}
                                        <div class="text-xs text-gray-500">{{ $surat->tanggal }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="p-4 text-sm text-gray-500 text-center">Belum ada surat masuk</div>
                        @endif

                        <div class="p-2 text-center border-t text-xs text-blue-600 hover:bg-gray-50 cursor-pointer">
                            <a href="{{ route('manajemen-suratmasuk') }}">Lihat semua</a>
                        </div>
                    </div>
                </div>

                {{-- User Profile --}}
                <div class="relative">
                    <button @click="userOpen = !userOpen" class="flex items-center gap-2 focus:outline-none">
                        {{-- Avatar User --}}
                        {{-- SVG User --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5Zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5Z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Pengguna' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown User --}}
                    <div x-show="userOpen" @click.outside="userOpen = false" x-transition
                        class="absolute right-0 mt-2 w-52 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <div class="p-3 border-b text-sm font-semibold text-gray-700">
                            Halo, {{ Auth::user()->name ?? 'User' }}
                            <p class="text-xs text-gray-500">
                                Role: {{ optional(Auth::user()->role)->name ?? 'Default' }}
                            </p>
                        </div>

                        <ul class="text-sm text-gray-700">
                            <li>
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-50 items-center gap-2 flex">
                                    <i class="fi fi-sr-settings"></i> Pengaturan</a>
                            </li>

                            {{-- Menu dinamis berdasarkan role --}}
                            
                            @if (optional(Auth::user()->role)->name === 'Admin')
                                <li>
                                    <a href="{{ route('arsip.admin') }}" class="block px-4 py-2 hover:bg-gray-50 items-center gap-2 flex">
                                        <i class="fi fi-sr-folder-open"></i> Arsip Semua Surat</a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('arsip.user') }}" class="block px-4 py-2 hover:bg-gray-50 items-center gap-2 flex">
                                        <i class="fi fi-sr-folder-open"></i> Arsip Saya</a>
                                </li>
                            @endif

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-50">
                                        <i class="fi fi-sr-user-logout"></i> Logout </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slot konten (isi dari child view) --}}
        {{ $slot }}
    </div>

    @livewireScripts
    @stack('scripts')

    <script>
        // === Sidebar Mobile Toggle ===
        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        menuBtn?.addEventListener('click', () => {
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });

        overlay?.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>

</html>
