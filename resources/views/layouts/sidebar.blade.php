<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.css">
<aside id="sidebar"
    class="fixed inset-y-0 left-0 w-64 bg-white shadow-md p-4 flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50 overflow-y-auto will-change-transform"
    x-data="{ openUser: false }">
    <div class="overflow-y-auto">
        <h2 class="text-3xl font-bold text-blue-600 mb-5">📨 E-Letter</h2>
        <hr>
        <ul class="space-y-4 mt-5">
            <p class="px-2 mb-2 text-xs font-semibold tracking-widest text-gray-400 uppercase">
                Main Menu
            </p>
            @if (auth()->user()->hasPermission('view_dashboard'))
                <li>
                    <a href="/dashboard"
                        class="flex items-center gap-3 px-2 py-1 rounded-md 
             {{ request()->is('dashboard') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-500' }}">
                        <i class="fi fi-rr-dashboard-monitor"></i> <span>Dashboard</span>
                    </a>
                </li>
            @endif

            {{-- @if (auth()->user()->hasPermission('manage_letters_in'))
        <li>
          <a href="{{ route('manajemen-suratmasuk') }}" 
             class="flex items-center gap-3 px-2 py-1 rounded-md 
             {{ request()->is('manajemen-suratmasuk') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-500' }}">
            📥 <span>Surat Masuk</span>
          </a>
        </li>
      @endif

      @if (auth()->user()->hasPermission('manage_letters_out'))
        <li>
          <a href="{{ route('manajemen-suratkeluar') }}" 
             class="flex items-center gap-3 px-2 py-1 rounded-md 
             {{ request()->is('manajemen-suratkeluar') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-500' }}">
            📤 <span>Surat Keluar</span>
          </a>
        </li>
      @endif --}}

            @if (auth()->user()->hasPermission('manage_letters'))
                <li>
                    <a href="{{ route('arsip.admin') }}"
                        class="flex items-center gap-3 px-2 py-1 rounded-md 
             {{ request()->is('arsip.admin') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-500' }}">
                        <i class="fi fi-rs-envelope-open-text"></i></i> <span>Arsip</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('manage_templates'))
                <li>
                    <a href="{{ route('template-surat.index') }}"
                        class="flex items-center gap-3 px-2 py-1 rounded-md 
             {{ request()->is('template-surat') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-500' }}">
                        <i class="fi fi-rr-template"></i> <span>Template Surat</span>
                    </a>
                </li>
            @endif

            {{-- 🔽 Hapus menu disposisi karena sudah jadi sub-modul surat masuk --}}

            @if (auth()->user()->hasPermission('view_reports'))
                <li>
                    <a href="/laporan"
                        class="flex items-center gap-3 px-2 py-1 rounded-md 
             {{ request()->is('laporan') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-500' }}">
                        <i class="fi fi-rr-newspaper"></i> <span>Laporan</span>
                    </a>
                </li>
            @endif

            {{-- 🔽 Menu Manajemen User --}}
            @if (auth()->user()->hasPermission('manage_users') || auth()->user()->hasPermission('manage_roles'))
                <li>
                    <p class="px-2 mb-2 text-xs font-semibold tracking-widest text-gray-400 uppercase">
                        Manajement User
                    </p>
                    <button @click="openUser = !openUser"
                        class="flex items-center justify-between w-full 
              {{ request()->is('manajemen-user') || request()->is('manajemen-role') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-500' }}">
                        <span class="px-2 flex items-center gap-3 mt-2">
                            <i class="fi fi-rr-users-alt"></i> <span>Manajemen User</span>
                        </span>
                        <svg :class="{ 'rotate-180': openUser }"
                            class="w-4 h-4 transform transition-transform duration-300 mr-5 mt-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="openUser" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 ml-6 space-y-2 text-sm">
                        @if (auth()->user()->hasPermission('manage_roles'))
                            <li>
                                <a href="{{ route('manajemen-role') }}"
                                    class="flex items-center gap-2 px-2 py-1 rounded-md 
                   {{ request()->is('manajemen-role') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500' }}">
                                    <i class="fi fi-rr-dice-d20"></i> <span>Role</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('manage_users'))
                            <li>
                                <a href="{{ route('manajemen-user') }}"
                                    class="flex items-center gap-2 px-2 py-1 rounded-md 
                   {{ request()->is('manajemen-user') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500' }}">
                                    <i class="fi fi-rr-circle-user"></i> <span>User</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

        </ul>
    </div>

    <div class="flex items-center gap-3 mt-6">
        {{-- SVG User --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5Zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5Z" />
        </svg>
        <div>
            <p class="font-semibold">{{ auth()->user()->name }}</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:underline">
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
