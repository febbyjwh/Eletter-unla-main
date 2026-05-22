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
            <h1 class="text-2xl font-bold text-gray-900">Login E-Letter UNLA</h1>
            <p class="text-gray-500 text-sm mt-1">Silakan masuk menggunakan akun Anda</p>
        </div>

        <!-- Alert -->
        @if (session()->has('error'))
            <div class="bg-red-50 text-red-600 px-4 py-2 rounded-lg mb-5 border border-red-200 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form wire:submit.prevent="login" class="space-y-5">
            <!-- Email -->
            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-700">Email</label>
                <input type="email" wire:model="email"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition"
                    placeholder="contoh@unla.ac.id" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="text-sm font-semibold text-gray-700">Password</label>
                    <!-- 🔗 Tombol lupa password -->
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-blue-500 hover:text-blue-600 font-medium">
                        Lupa Password?
                    </a>
                </div>
                <input type="password" wire:model="password"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition"
                    placeholder="Masukkan password" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="space-y-3">

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-400 to-blue-500 text-white py-2.5 rounded-lg font-semibold text-sm hover:from-blue-500 hover:to-blue-600 transition-all duration-200">
                    Login
                </button>

                <a href="{{ route('google.login') }}"
                    class="w-full flex items-center justify-center gap-1 px-2 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-150">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M21.35 11.1h-9.18v2.9h5.28c-.23 1.4-1.8 4.1-5.28 4.1-3.18 0-5.8-2.64-5.8-5.9s2.62-5.9 5.8-5.9c1.82 0 3.04.78 3.74 1.45l2.56-2.48C16.96 3.88 14.84 3 12.17 3 6.92 3 2.7 7.2 2.7 12.35S6.92 21.7 12.17 21.7c5.36 0 9.13-3.75 9.13-9.02 0-.6-.07-1.1-.15-1.58z" />
                    </svg>
                    Login dengan Google
                </a>
            </div>
            <div class="text-gray-800 text-sm text-center">
                Belum punya akun?
                <a class="font-medium text-blue-600 text-sm hover:underline" href="{{ route('register') }}">Buat akun
                    E-Letter</a>
            </div>
        </form>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-xs mt-8">
            © {{ date('Y') }} Universitas Langlangbuana. All rights reserved.
        </p>
    </div>
</div>
