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

        <!-- Alert Error -->
        @if (session()->has('error'))
            <div class="bg-red-50 text-red-600 px-4 py-2 rounded-lg mb-5 border border-red-200 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Alert Success -->
        @if (session()->has('success'))
            <div class="bg-green-50 text-green-600 px-4 py-2 rounded-lg mb-5 border border-green-200 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form -->
        <button wire:click="register"
            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition">

            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path
                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path
                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                <path
                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>

            Daftar dengan Google
        </button>

        <!-- Sudah punya akun -->
        <div class="text-gray-800 text-sm text-center mt-4">
            Sudah punya akun?
            <a class="font-medium text-blue-600 hover:underline" href="{{ route('login') }}">Login di sini</a>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-xs mt-8">
            © {{ date('Y') }} Universitas Langlangbuana. All rights reserved.
        </p>
    </div>
</div>

{{-- <script>
    document.getElementById('googleRegisterBtn').addEventListener('click', function () {
        const role = document.getElementById('roleSelect').value;
        const errorEl = document.getElementById('roleError');

        if (!role) {
            errorEl.classList.remove('hidden');
            return;
        }

        errorEl.classList.add('hidden');
        window.location.href = `/auth/google/register/${role}`;
    });
</script> --}}
