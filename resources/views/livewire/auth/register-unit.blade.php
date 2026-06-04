{{-- resources/views/livewire/register-unit.blade.php --}}
<div class="eletter-register-page">
    <style>
        .eletter-register-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 20px;
            color: #121827;
            background:
                linear-gradient(rgba(205, 218, 238, .38) 1px, transparent 1px),
                linear-gradient(90deg, rgba(205, 218, 238, .38) 1px, transparent 1px),
                radial-gradient(circle at 17% 21%, rgba(77, 116, 190, .14), transparent 31%),
                radial-gradient(circle at 88% 82%, rgba(79, 121, 188, .24), transparent 29%),
                linear-gradient(115deg, #f8fbff 0%, #eef5ff 54%, #dcecff 100%);
            background-size: 54px 54px, 54px 54px, 100% 100%, 100% 100%, 100% 100%;
            position: relative;
            overflow: hidden;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .eletter-register-page::before,
        .eletter-register-page::after {
            content: "";
            position: absolute;
            pointer-events: none;
            opacity: .28;
        }

        .eletter-register-page::before {
            width: 138px;
            height: 138px;
            left: calc(50% - 520px);
            top: calc(50% - 315px);
            border-radius: 34px;
            background: #a7c8bd;
            transform: rotate(14deg);
        }

        .eletter-register-page::after {
            width: 152px;
            height: 152px;
            right: calc(50% - 620px);
            bottom: calc(50% - 315px);
            border-radius: 50%;
            background: #c6b9a2;
        }

        .register-card {
            width: min(100%, 1160px);
            min-height: 640px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 28px 80px rgba(24, 53, 96, .18);
            position: relative;
            z-index: 1;
        }

        /* LEFT PANEL */
        .register-left {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 74px 68px;
            color: #ffffff;
            overflow: hidden;
            background:
                linear-gradient(rgba(255, 255, 255, .045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .045) 1px, transparent 1px),
                linear-gradient(153deg, #173e65 0%, #244e85 48%, #3156d3 100%);
            background-size: 52px 52px, 52px 52px, 100% 100%;
        }

        .register-left::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 128px;
            opacity: .42;
            background:
                url("data:image/svg+xml,%3Csvg width='700' height='150' viewBox='0 0 700 150' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 92C57 82 85 45 140 62C191 78 197 109 250 88C312 63 325 35 390 69C443 96 459 102 500 78C556 44 586 30 700 79' stroke='white' stroke-width='4'/%3E%3Cpath d='M0 115C57 105 85 68 140 85C191 101 197 132 250 111C312 86 325 58 390 92C443 119 459 125 500 101C556 67 586 53 700 102' stroke='white' stroke-width='3' opacity='.36'/%3E%3C/svg%3E") center bottom / cover no-repeat;
        }

        .register-brand,
        .register-title,
        .register-copy,
        .register-features,
        .register-help {
            position: relative;
            z-index: 1;
        }

        .register-brand {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 34px;
            font-size: 20px;
            font-weight: 800;
        }

        .register-brand svg {
            width: 28px;
            height: 28px;
        }

        .register-title {
            max-width: 450px;
            margin: 0;
            font-size: 40px;
            line-height: 1.18;
            letter-spacing: 0;
            font-weight: 900;
        }

        .register-copy {
            max-width: 470px;
            margin-top: 26px;
            color: rgba(255, 255, 255, .82);
            font-size: 19px;
            line-height: 1.65;
            font-weight: 500;
        }

        .register-features {
            margin-top: 32px;
            display: grid;
            gap: 8px;
            font-size: 18px;
            font-weight: 700;
        }

        .register-feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .register-feature-item svg {
            width: 28px;
            height: 28px;
            flex: 0 0 auto;
        }

        .register-help {
            margin-top: 38px;
            color: rgba(255, 255, 255, .78);
            font-size: 16px;
            line-height: 1.45;
        }

        .register-help a {
            color: #ffffff;
            font-weight: 800;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        /* RIGHT PANEL */
        .register-right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 66px;
            background: #ffffff;
        }

        .register-form-header {
            margin-bottom: 30px;
        }

        .register-badge {
            display: inline-flex;
            color: #111827;
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 22px;
        }

        .register-form-header h1 {
            margin: 0;
            font-size: 31px;
            line-height: 1.15;
            font-weight: 900;
            letter-spacing: 0;
        }

        .register-form-header p {
            margin: 16px 0 0;
            color: #6b7280;
            font-size: 17px;
            font-weight: 600;
        }

        .register-alert-error {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .register-alert-success {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #16a34a;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        /* Logo circle */
        .register-logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 28px;
        }

        .register-logo-wrap .logo-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid #e1e5eb;
            box-shadow: 0 4px 16px rgba(49, 86, 211, .12);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-logo-wrap .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Divider */
        .register-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            color: #9ca3af;
            font-size: 13px;
            font-weight: 600;
        }

        .register-divider::before,
        .register-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* Google button */
        .register-google-btn {
            display: flex;
            width: 100%;
            height: 58px;
            border-radius: 16px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #ffffff;
            font-size: 17px;
            font-weight: 900;
            text-decoration: none;
            background: #ef4444;
            box-shadow: 0 10px 18px rgba(239, 68, 68, .18);
            border: 0;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .register-google-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(239, 68, 68, .24);
        }

        .register-google-btn svg {
            width: 22px;
            height: 22px;
        }

        /* Already have account note */
        .login-note {
            margin-top: 18px;
            text-align: center;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
        }

        .login-note a {
            color: #3156d3;
            font-weight: 800;
            text-decoration: none;
        }

        .login-note a:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* Footer note */
        .register-footer-note {
            margin-top: 26px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #111827;
            font-size: 16px;
            line-height: 1.45;
            font-weight: 600;
        }

        .register-footer-note svg {
            width: 25px;
            height: 25px;
            flex: 0 0 auto;
            margin-top: 2px;
        }

        .register-copyright {
            margin-top: 22px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }

        @media (max-width: 860px) {
            .eletter-register-page {
                align-items: flex-start;
                padding: 24px 14px;
            }

            .register-card {
                grid-template-columns: 1fr;
                min-height: 0;
            }

            .register-left,
            .register-right {
                padding: 42px 28px;
            }

            .register-title {
                font-size: 32px;
            }

            .register-copy,
            .register-features,
            .register-footer-note {
                font-size: 16px;
            }
        }
    </style>

    <div class="register-card">

        {{-- LEFT PANEL --}}
        <div class="register-left">
            <div class="register-brand">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="5" width="18" height="14" rx="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9h18M7 14h4" />
                </svg>
                <span>E-Letter UNLA</span>
            </div>

            <h2 class="register-title">Daftarkan Unit Anda</h2>

            <p class="register-copy">
                Registrasi unit untuk mulai mengelola surat masuk dan keluar secara digital di Universitas
                Langlangbuana.
            </p>

            <div class="register-features">
                <div class="register-feature-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                    <span>Unit langsung terdaftar di sistem</span>
                </div>
                <div class="register-feature-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                    <span>Email digunakan sebagai identitas unit</span>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="register-right">
            <div class="register-logo-wrap">
                <div class="logo-circle">
                    <img src="{{ asset('assets/img/logo-unla2.png') }}" alt="Logo UNLA">
                </div>
            </div>

            <div class="register-form-header">
                <span class="register-badge">Registrasi Unit</span>
                <h1>Daftar Unit Baru</h1>
                <p>Isi data unit untuk mendaftarkan unit ke sistem</p>
            </div>

            @if (session()->has('error'))
                <div class="register-alert-error">{{ session('error') }}</div>
            @endif

            @if (session()->has('success'))
                <div class="register-alert-success">{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="register" class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email Unit
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l9 6 9-6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
                            </svg>
                        </span>
                        <input type="email" wire:model="email" placeholder="email@unla.ac.id"
                            class="w-full pl-9 pr-4 py-3 rounded-2xl border border-gray-200 bg-gray-50 text-sm
                                   focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Unit
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 21V7a2 2 0 012-2h14a2 2 0 012 2v14M9 21v-6h6v6" />
                            </svg>
                        </span>
                        <input type="text" wire:model="nama_unit" placeholder="Contoh: Unit Keuangan"
                            class="w-full pl-9 pr-4 py-3 rounded-2xl border border-gray-200 bg-gray-50 text-sm
                                   focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                    </div>
                    @error('nama_unit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Unit
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 11c-1.657 0-3 1.343-3 3v3h6v-3c0-1.657-1.343-3-3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 11V7a6 6 0 0112 0v4" />
                            </svg>
                        </span>

                        <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="Minimal 8 karakter"
                            class="w-full pr-10 pl-9 py-3 rounded-2xl border border-gray-200 bg-gray-50 text-sm
                   focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 focus:outline-none">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.77-3.823M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="register-google-btn"
                    style="background:#173e65; box-shadow: 0 10px 18px rgba(23,62,101,.18);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 21v-2a4 4 0 00-8 0v2M12 11a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    Daftarkan Unit
                </button>

            </form>

            <div class="login-note">
                Sudah punya akun unit?
                <a href="{{ route('login-unit') }}">Login di sini</a>
            </div>

            <div class="register-footer-note">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                </svg>
                <span>Data unit terlindungi dan hanya digunakan untuk keperluan sistem E-Letter UNLA.</span>
            </div>

            <p class="register-copyright">
                © {{ date('Y') }} Universitas Langlangbuana. All rights reserved.
            </p>
        </div>
    </div>
</div>
