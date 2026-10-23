@extends('layouts.app')

@section('content')
<style>
    /* Menyembunyikan Top Banner Google Translate */
    .goog-te-banner-frame.skiptranslate, 
    iframe.goog-te-banner-frame,
    .goog-te-gadget-icon, 
    .goog-te-menu-value span:nth-child(3) {
        display: none !important;
    }
    body {
        top: 0px !important;
        margin: 0;
        padding: 0;
        background-color: #020617 !important; /* Solid Dark Slate-950 */
        position: relative;
        min-height: 100vh;
    }

    /* Container Box Translate Floating Top-Right */
    .floating-translate-container {
        position: fixed;
        top: 15px;
        right: 15px;
        z-index: 99999;
    }

    .lang-select-box {
        background-color: #0f172a !important;
        color: #ffffff !important;
        border: 1px solid #334155 !important;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 0.85rem;
        outline: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }

    /* Container Tengah */
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }

    /* Kad Clean Dark Slate Enterprise */
    .card-neon-box {
        position: relative;
        width: 100%;
        max-width: 440px;
        border-radius: 16px;
        background-color: #0f172a; /* Slate-900 */
        border: 1px solid #1e293b; /* Slate-800 */
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        box-sizing: border-box;
    }

    /* Inner Kad Hitam/Slate */
    .card-inner-content {
        position: relative;
        z-index: 1;
        border-radius: 16px;
        padding: 32px 30px;
        text-align: center;
        color: #ffffff;
        box-sizing: border-box;
    }

    /* Icon Headset Bulat */
    .icon-headset-circle {
        width: 64px;
        height: 64px;
        background-color: #4f46e5; /* Indigo-600 */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px auto;
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
    }

    /* SVG Headset Icon */
    .icon-headset-circle svg {
        width: 32px;
        height: 32px;
        fill: #ffffff;
    }

    /* Tajuk Main */
    .title-resolv {
        font-size: 1.85rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .glow-404 {
        color: #f43f5e;
    }

    /* Badge Subtitle */
    .badge-helpdesk {
        background-color: rgba(30, 41, 59, 0.85);
        color: #818cf8;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        padding: 5px 14px;
        border-radius: 50px;
        border: 1px solid rgba(99, 102, 241, 0.3);
        display: inline-block;
        margin-bottom: 12px;
        text-transform: uppercase;
    }

    .sub-text {
        color: #94a3b8;
        font-size: 0.88rem;
        margin-bottom: 24px;
    }

    /* Input Field Custom */
    .input-group-custom {
        display: flex;
        align-items: center;
        background-color: #020617;
        border: 1px solid #334155;
        border-radius: 10px;
        padding: 0 14px;
        margin-bottom: 18px;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .input-group-custom:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 10px rgba(99, 102, 241, 0.3);
    }

    .input-group-custom svg {
        width: 18px;
        height: 18px;
        fill: #64748b;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .input-group-custom input {
        width: 100%;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        color: #ffffff !important;
        padding: 12px 0;
        font-size: 0.92rem;
    }

    .input-group-custom input::placeholder {
        color: #475569;
    }

    .toggle-password-btn {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0 0 0 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }

    .toggle-password-btn:hover {
        color: #818cf8;
    }

    .label-title {
        display: block;
        text-align: left;
        font-size: 0.82rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 6px;
    }

    /* Butang Submit FULL WIDTH - Indigo Primary */
    .btn-submit-login {
        width: 100% !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: #4f46e5 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 13px !important;
        font-weight: 700 !important;
        font-size: 0.98rem !important;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .btn-submit-login:hover {
        background-color: #4338ca !important;
        transform: translateY(-1px);
    }

    .btn-submit-login svg {
        width: 18px;
        height: 18px;
        fill: #ffffff;
    }

    /* Register & Forgot Link */
    .register-box {
        margin-top: 24px;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .register-box a {
        color: #818cf8;
        text-decoration: none;
        font-weight: 700;
        margin-left: 4px;
    }

    .register-box a:hover {
        text-decoration: underline;
    }

    .forgot-pass-link {
        color: #818cf8;
        font-size: 0.80rem;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .forgot-pass-link:hover {
        color: #a5b4fc;
        text-decoration: underline;
    }

    @media (max-width: 576px) {
        .floating-translate-container {
            top: 10px;
            right: 10px;
        }

        .lang-select-box {
            padding: 4px 8px;
            font-size: 0.78rem;
        }

        .login-wrapper {
            padding: 60px 12px 20px 12px;
        }

        .card-inner-content {
            padding: 24px 18px;
        }

        .icon-headset-circle {
            width: 58px;
            height: 58px;
            margin-bottom: 12px;
        }

        .icon-headset-circle svg {
            width: 28px;
            height: 28px;
        }

        .title-resolv {
            font-size: 1.5rem;
        }

        .sub-text {
            font-size: 0.82rem;
            margin-bottom: 18px;
        }

        .input-group-custom input {
            padding: 10px 0;
            font-size: 0.88rem;
        }

        .btn-submit-login {
            padding: 11px !important;
            font-size: 0.9rem !important;
        }
    }
</style>

<!-- Floating Box Translate -->
<div class="floating-translate-container">
    <select id="languageSelect" class="lang-select-box notranslate" translate="no" onchange="fastTranslate(this.value)">
        <option value="" class="notranslate" translate="no">English (Original)</option>
        <option value="ms">Bahasa Melayu</option>
        <option value="zh-CN">Chinese (Simplified)</option>
        <option value="ta">Tamil</option>
        <option value="ar">Arabic</option>
        <option value="ja">Japanese</option>
        <option value="ko">Korean</option>
        <option value="fr">French</option>
        <option value="de">German</option>
        <option value="es">Spanish</option>
        <option value="ru">Russian</option>
        <option value="id">Indonesian</option>
        <option value="th">Thai</option>
        <option value="vi">Vietnamese</option>
    </select>
</div>

<div class="login-wrapper">
    <div class="card-neon-box">
        <div class="card-inner-content">
            
            <!-- 1. HEADSET ICON -->
            <div class="icon-headset-circle">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1a9 9 0 0 0-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2a7 7 0 0 1 14 0v2h-4v8h3c1.66 0 3-1.34 3-3v-7a9 9 0 0 0-9-9z"/>
                </svg>
            </div>

            <!-- 2. TAJUK -->
            <h2 class="title-resolv">RESOLV YO <span class="glow-404">404</span></h2>

            <!-- 3. BADGE -->
            <div>
                <span class="badge-helpdesk">IT SUPPORT HELPDESK</span>
            </div>

            <!-- 4. SUBTITLE -->
            <p class="sub-text">Log in to your account to continue</p>

            <!-- Status Message -->
            @if (session('status'))
                <div style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); padding: 12px; border-radius: 10px; font-size: 0.88rem; margin-bottom: 22px;">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Error Notification -->
            @if($errors->any())
                <div style="background: rgba(225, 29, 72, 0.2); color: #fda4af; border: 1px solid rgba(225, 29, 72, 0.4); padding: 12px; border-radius: 10px; font-size: 0.88rem; margin-bottom: 22px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- 5. FORM -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <input type="text" style="display:none;" aria-hidden="true">
                <input type="password" style="display:none;" aria-hidden="true">

                <!-- E-mel -->
                <div>
                    <label class="label-title" for="email">Email Address</label>
                    <div class="input-group-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="name@example.com" 
                               required 
                               autocomplete="username" 
                               readonly 
                               onfocus="this.removeAttribute('readonly'); document.getElementById('password').removeAttribute('readonly');">
                    </div>
                </div>

                <!-- Kata Laluan -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="label-title" for="password" style="margin-bottom: 0;">Password</label>
                        <a href="{{ route('password.request') }}" class="forgot-pass-link">Forgot Password?</a>
                    </div>
                    <div class="input-group-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••" 
                               required 
                               autocomplete="current-password">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('password', this)" title="Show/Hide Password">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Butang Log Masuk -->
                <button type="submit" class="btn-submit-login">
                    <span>Log In</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </form>

            <!-- 6. Register Link -->
            <div class="register-box">
                Don't have an account? <a href="{{ route('register') }}">Register Account</a>
            </div>

        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        @if(!$errors->any() && !old('email'))
            setTimeout(() => {
                if (emailInput) emailInput.value = '';
                if (passwordInput) passwordInput.value = '';
            }, 100);
        @endif
    });
</script>
@endsection