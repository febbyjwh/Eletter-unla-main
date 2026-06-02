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

            <h2 class="register-title">Mulai Kelola Surat Digital</h2>

            <p class="register-copy">
                Daftar dan nikmati kemudahan pengelolaan surat masuk, surat keluar secara digital di Universitas Langlangbuana.
            </p>

            <div class="register-features">
                <div class="register-feature-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                    <span>Akses cepat via akun Google</span>
                </div>

                <div class="register-feature-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                    <span>Tidak perlu daftar manual</span>
                </div>

                <div class="register-feature-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                    <span>Langsung terhubung ke dashboard</span>
                </div>
            </div>

            <small class="register-help">
                Sudah punya akun?<br>
                <a href="{{ route('login') }}">Login ke E-Letter</a>
            </small>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="register-right">
            <div class="register-logo-wrap">
                <div class="logo-circle">
                    <img src="{{ asset('assets/img/logo-unla2.png') }}" alt="Logo UNLA">
                </div>
            </div>

            <div class="register-form-header">
                <span class="register-badge">Buat Akun Baru</span>
                <h1>Daftar E-Letter</h1>
                <p>Buat akun Anda untuk mulai menggunakan sistem</p>
            </div>

            @if (session()->has('error'))
                <div class="register-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if (session()->has('success'))
                <div class="register-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="register-divider">atau daftar dengan</div>

            <button wire:click="register" class="register-google-btn">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                Daftar dengan Google
            </button>

            <div class="login-note">
                Sudah punya akun?
                <a href="{{ route('login') }}">Login di sini</a>
            </div>

            <div class="register-footer-note">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                </svg>
                <span>Data Anda terlindungi dan hanya digunakan untuk keperluan sistem E-Letter UNLA.</span>
            </div>

            <p class="register-copyright">
                © {{ date('Y') }} Universitas Langlangbuana. All rights reserved.
            </p>
        </div>
    </div>
</div>