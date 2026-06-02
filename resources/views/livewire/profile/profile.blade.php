<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 px-4">
    <div
        class="bg-white/90 backdrop-blur-md p-8 rounded-3xl shadow-2xl w-full max-w-lg border border-blue-100 transform -translate-y-20">

        <!-- Avatar -->
        <div class="flex items-center justify-center mb-6">
            <div class="relative">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=3b82f6&color=fff"
                    alt="Avatar" class="w-24 h-24 rounded-full shadow-lg border-4 border-blue-500/20">
            </div>
        </div>

        <!-- Step Navigation -->
        <div class="flex justify-center mb-6 space-x-4">
            <button wire:click="$set('currentStep', 1)"
                class="px-4 py-2 rounded-xl font-semibold transition
                    {{ $currentStep === 1 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                👤 Profil
            </button>
            <button wire:click="$set('currentStep', 2)"
                class="px-4 py-2 rounded-xl font-semibold transition
                    {{ $currentStep === 2 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                🔐 Password
            </button>
        </div>

        @if (session()->has('message'))
            <div
                class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-xl mb-4 text-sm text-center">
                ✅ {{ session('message') }}
            </div>
        @endif

        <!-- STEP 1: Profile -->
        @if ($currentStep === 1)
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Profil Saya</h2>
            <p class="text-center text-gray-500 text-sm mb-6">Kelola data akun kamu dengan mudah ✨</p>

            <form wire:submit.prevent="updateProfile" class="space-y-5">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input type="text" wire:model="name"
                        class="w-full border border-gray-200 rounded-xl p-2.5 mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none transition duration-150 ease-in-out"
                        placeholder="Masukkan nama kamu...">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Alamat Email</label>
                    <input type="email" wire:model="email"
                        class="w-full border border-gray-200 rounded-xl p-2.5 mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none transition duration-150 ease-in-out"
                        placeholder="Masukkan email kamu...">
                </div>

                <div class="flex justify-end">
                    <button type="button" wire:click="nextStep"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-6 rounded-xl font-semibold shadow-md transition">
                        Selanjutnya →
                    </button>
                </div>
            </form>
        @endif

        <!-- STEP 2: Password -->
        @if ($currentStep === 2)
            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Ganti Password</h3>

            <form wire:submit.prevent="updatePassword" class="space-y-5">

                <!-- Password Lama -->
                <div x-data="{ show: false }">
                    <label class="text-sm font-semibold text-gray-700">
                        Password Lama
                    </label>

                    <div class="relative mt-1">
                        <input :type="show ? 'text' : 'password'" wire:model="password"
                            class="w-full border border-gray-200 rounded-xl p-2.5 pr-10
                       focus:ring-2 focus:ring-blue-400 focus:outline-none"
                            placeholder="Masukkan password lama...">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">

                            <i x-show="!show" class="fi fi-rr-eye"></i>
                            <i x-show="show" class="fi fi-rr-eye-crossed"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div x-data="{ show: false }">
                    <label class="text-sm font-semibold text-gray-700">
                        Password Baru
                    </label>

                    <div class="relative mt-1">
                        <input :type="show ? 'text' : 'password'" wire:model="new_password"
                            class="w-full border border-gray-200 rounded-xl p-2.5 pr-10
                       focus:ring-2 focus:ring-blue-400 focus:outline-none"
                            placeholder="Masukkan password baru...">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">

                            <i x-show="!show" class="fi fi-rr-eye"></i>
                            <i x-show="show" class="fi fi-rr-eye-crossed"></i>
                        </button>
                    </div>

                    @error('new_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div x-data="{ show: false }">
                    <label class="text-sm font-semibold text-gray-700">
                        Konfirmasi Password Baru
                    </label>

                    <div class="relative mt-1">
                        <input :type="show ? 'text' : 'password'" wire:model="confirm_password"
                            class="w-full border border-gray-200 rounded-xl p-2.5 pr-10
                       focus:ring-2 focus:ring-blue-400 focus:outline-none"
                            placeholder="Ketik ulang password baru...">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">

                            <i x-show="!show" class="fi fi-rr-eye"></i>
                            <i x-show="show" class="fi fi-rr-eye-crossed"></i>
                        </button>
                    </div>

                    @error('confirm_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between">
                    <button type="button" wire:click="previousStep"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold text-gray-700 transition">
                        ← Kembali
                    </button>

                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-md transition">
                        Simpan Password
                    </button>
                </div>

            </form>
        @endif

        <!-- Modal lama tetap disimpan, hanya tidak dipakai di mode wizard -->
        @if ($showPasswordModal)
            <div class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50">
                <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800">Ubah Password</h3>
                        <button wire:click="$set('showPasswordModal', false)"
                            class="text-gray-500 hover:text-gray-700">✖</button>
                    </div>

                    <form wire:submit.prevent="updatePassword" class="p-6 space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Password Lama</label>
                            <input type="password" wire:model="password"
                                class="w-full border border-gray-200 rounded-xl p-2.5 mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                placeholder="Masukkan password lama...">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Password Baru</label>
                            <input type="password" wire:model="new_password"
                                class="w-full border border-gray-200 rounded-xl p-2.5 mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                placeholder="Masukkan password baru...">
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" wire:model="confirm_password"
                                class="w-full border border-gray-200 rounded-xl p-2.5 mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                placeholder="Ketik ulang password baru...">
                            @error('confirm_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" wire:click="$set('showPasswordModal', false)"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 font-semibold transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
