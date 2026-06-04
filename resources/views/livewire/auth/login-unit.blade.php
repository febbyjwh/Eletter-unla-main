<div class="eletter-login-page">
    <style>
        .eletter-login-page {
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

        .eletter-login-page::before,
        .eletter-login-page::after {
            content: "";
            position: absolute;
            pointer-events: none;
            opacity: .28;
        }

        .eletter-login-page::before {
            width: 138px;
            height: 138px;
            left: calc(50% - 520px);
            top: calc(50% - 315px);
            border-radius: 34px;
            background: #a7c8bd;
            transform: rotate(14deg);
        }

        .eletter-login-page::after {
            width: 152px;
            height: 152px;
            right: calc(50% - 620px);
            bottom: calc(50% - 315px);
            border-radius: 50%;
            background: #c6b9a2;
        }

        .login-card {
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

        .login-left {
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

        .login-left::after {
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

        .login-brand,
        .login-title,
        .login-copy,
        .login-features,
        .login-help {
            position: relative;
            z-index: 1;
        }

        .login-brand {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 34px;
            font-size: 20px;
            font-weight: 800;
        }

        .login-brand svg {
            width: 28px;
            height: 28px;
        }

        .login-title {
            max-width: 450px;
            margin: 0;
            font-size: 40px;
            line-height: 1.18;
            letter-spacing: 0;
            font-weight: 900;
        }

        .login-copy {
            max-width: 470px;
            margin-top: 26px;
            color: rgba(255, 255, 255, .82);
            font-size: 19px;
            line-height: 1.65;
            font-weight: 500;
        }

        .login-features {
            margin-top: 32px;
            display: grid;
            gap: 8px;
            font-size: 18px;
            font-weight: 700;
        }

        .login-feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-feature-item svg {
            width: 28px;
            height: 28px;
            flex: 0 0 auto;
        }

        .login-help {
            margin-top: 38px;
            color: rgba(255, 255, 255, .78);
            font-size: 16px;
            line-height: 1.45;
        }

        .login-help a {
            color: #ffffff;
            font-weight: 800;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .login-right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 66px;
            background: #ffffff;
        }

        .login-form-header {
            margin-bottom: 30px;
        }

        .login-badge {
            display: inline-flex;
            color: #111827;
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 22px;
        }

        .login-form-header h1 {
            margin: 0;
            font-size: 31px;
            line-height: 1.15;
            font-weight: 900;
            letter-spacing: 0;
        }

        .login-form-header p {
            margin: 16px 0 0;
            color: #6b7280;
            font-size: 17px;
            font-weight: 600;
        }

        .login-alert {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .login-form {
            display: grid;
            gap: 18px;
        }

        .login-field-top {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 8px;
        }

        .login-field-top a {
            color: #3156d3;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
        }

        .login-field-top a:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .login-input {
            width: 100%;
            height: 58px;
            border: 1px solid #e1e5eb;
            border-radius: 16px;
            background: #fbfcfe;
            padding: 0 18px;
            color: #111827;
            font-size: 16px;
            font-weight: 700;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .login-input::placeholder {
            color: #a5adba;
        }

        .login-input:focus {
            background: #ffffff;
            border-color: #3156d3;
            box-shadow: 0 0 0 4px rgba(49, 86, 211, .12);
        }

        .login-field-error {
            margin-top: 6px;
            color: #dc2626;
            font-size: 12px;
        }

        .login-actions {
            display: grid;
            gap: 12px;
        }

        .login-submit,
        .google-login {
            width: 100%;
            height: 58px;
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #ffffff;
            font-size: 18px;
            font-weight: 900;
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .login-submit {
            border: 0;
            background: linear-gradient(90deg, #234f83 0%, #3156d3 100%);
            box-shadow: 0 10px 20px rgba(49, 86, 211, .22);
            cursor: pointer;
        }

        .login-submit:hover,
        .google-login:hover {
            transform: translateY(-1px);
        }

        .login-submit:hover {
            box-shadow: 0 14px 28px rgba(49, 86, 211, .28);
        }

        .google-login {
            background: #ef4444;
            box-shadow: 0 10px 18px rgba(239, 68, 68, .18);
        }

        .google-login:hover {
            background: #dc2626;
            box-shadow: 0 14px 24px rgba(239, 68, 68, .24);
        }

        .login-submit svg {
            width: 26px;
            height: 26px;
        }

        .google-login svg {
            width: 22px;
            height: 22px;
        }

        .register-note {
            margin-top: 2px;
            text-align: center;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
        }

        .register-note a {
            color: #3156d3;
            font-weight: 800;
            text-decoration: none;
        }

        .register-note a:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .login-footer-note {
            margin-top: 26px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #111827;
            font-size: 18px;
            line-height: 1.45;
            font-weight: 600;
        }

        .login-footer-note svg {
            width: 25px;
            height: 25px;
            flex: 0 0 auto;
            margin-top: 2px;
        }

        .login-copyright {
            margin-top: 22px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }

        @media (max-width: 860px) {
            .eletter-login-page {
                align-items: flex-start;
                padding: 24px 14px;
            }

            .login-card {
                grid-template-columns: 1fr;
                min-height: 0;
            }

            .login-left,
            .login-right {
                padding: 42px 28px;
            }

            .login-title {
                font-size: 32px;
            }

            .login-copy,
            .login-features,
            .login-footer-note {
                font-size: 16px;
            }
        }
    </style>

    <div class="login-card">
        {{-- LEFT PANEL --}}
        <div class="login-left">
            <div class="login-brand">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="5" width="18" height="14" rx="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9h18M7 14h4" />
                </svg>
                <span>E-Letter UNLA</span>
            </div>

            <h2 class="login-title">Kelola Surat Lebih Terarah</h2>

            <p class="login-copy">
                Sistem persuratan digital untuk membantu pencatatan, monitoring,
                dan pengelolaan surat masuk maupun surat keluar secara lebih rapi.
            </p>

            <div class="login-features">
                <div class="login-feature-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                    <span>Monitoring surat masuk dan keluar</span>
                </div>

                {{-- <div class="login-feature-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                    <span>Disposisi surat lebih terkontrol</span>
                </div> --}}

                <div class="login-feature-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                    <span>Dashboard persuratan real-time</span>
                </div>
            </div>

            {{-- <small class="login-help">
                Belum punya akun?<br>
                <a href="{{ route('register') }}">Buat akun E-Letter</a>
            </small> --}}

        </div>

        {{-- RIGHT PANEL --}}
        <div class="login-right">
            <div class="login-form-header">
                <span class="login-badge">Secure Login</span>
                <h1>Silakan Login</h1>
                <p>Masuk ke sistem E-Letter UNLA</p>
            </div>

            @if (session()->has('error'))
                <div class="login-alert">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="login" class="login-form">
                <div>
                    <input type="email" wire:model="email" class="login-input" placeholder="contoh@unla.ac.id"
                        required>
                    @error('email')
                        <p class="login-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="login-field-top">
                        <a href="{{ route('password.request') }}">Lupa Password?</a>
                    </div>
                    <input type="password" wire:model="password" class="login-input" placeholder="Masukkan password"
                        required>
                    @error('password')
                        <p class="login-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="login-actions">
                    <button type="submit" class="login-submit">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3" />
                        </svg>
                        Masuk ke Dashboard
                    </button>

                    <a href="{{ route('google.login') }}" class="google-login">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M21.35 11.1h-9.18v2.9h5.28c-.23 1.4-1.8 4.1-5.28 4.1-3.18 0-5.8-2.64-5.8-5.9s2.62-5.9 5.8-5.9c1.82 0 3.04.78 3.74 1.45l2.56-2.48C16.96 3.88 14.84 3 12.17 3 6.92 3 2.7 7.2 2.7 12.35S6.92 21.7 12.17 21.7c5.36 0 9.13-3.75 9.13-9.02 0-.6-.07-1.1-.15-1.58z" />
                        </svg>
                        Login dengan Google
                    </a>
                </div>

                <div class="register-note">
                    Daftarkan Akun Unit?
                    <a href="{{ route('register-unit') }}">Buat akun unit E-Letter</a>
                </div>
            </form>

            <div class="login-footer-note">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                </svg>
                <span>Data persuratan terlindungi dan hanya dapat diakses oleh pengguna berwenang.</span>
            </div>

            <p class="login-copyright">
                © {{ date('Y') }} Universitas Langlangbuana. All rights reserved.
            </p>
        </div>
    </div>
</div>
