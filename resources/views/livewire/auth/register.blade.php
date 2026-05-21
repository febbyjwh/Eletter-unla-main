<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-6">
            <div
                class="w-24 h-24 rounded-full overflow-hidden shadow-md border border-gray-200 flex items-center justify-center">
                <img src="{{ asset('assets/img/logo-unla2.png') }}" alt="Logo UNLA" class="object-contain w-full h-full">
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Register E-Letter UNLA</h1>
            <p class="text-gray-500 text-sm mt-1">Silakan buat akun Anda</p>
        </div>

        <!-- Alert -->
        @if (session()->has('error'))
            <div class="bg-red-50 text-red-600 px-4 py-2 rounded-lg mb-5 border border-red-200 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form wire:submit.prevent="register" class="space-y-5">
            
            <!-- Role -->
            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-700">
                    Daftar Sebagai
                </label>

                <select id="roleSelect"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition bg-white">

                    <option value="">
                        Pilih Role
                    </option>

                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('google.redirect') }}" id="googleLoginBtn"
                class="inline-flex items-center justify-center w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl transition">

                Login dengan Google
            </a>

            {{-- <a href="{{ route('google.redirect') }}"
                class="inline-block px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl transition relative z-50">
                Login dengan Google
            </a> --}}
        </form>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-xs mt-8">
            © {{ date('Y') }} Universitas Langlangbuana. All rights reserved.
        </p>
    </div>
</div>

<script>
    document.getElementById('googleLoginBtn')
        .addEventListener('click', function(e) {

            e.preventDefault();

            const role =
                document.getElementById('roleSelect')
                .value;

            if (!role) {
                alert(
                    'Silakan pilih role terlebih dahulu.'
                );
                return;
            }

            window.location.href =
                `/auth/google/${role}`;
        });
</script>