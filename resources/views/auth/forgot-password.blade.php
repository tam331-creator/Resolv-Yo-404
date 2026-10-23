@extends('layouts.app')

@section('content')
<style>
    body {
        top: 0px !important;
        margin: 0;
        padding: 0;
        background-color: #020617 !important; /* Solid Dark Slate-950 */
        position: relative;
        min-height: 100vh;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }

    /* Kad Clean Dark Slate Enterprise Dengan Border Neon 4 Warna Berpusing */
    .card-neon-box {
        position: relative;
        width: 100%;
        max-width: 440px;
        border-radius: 18px;
        padding: 2.5px;
        overflow: hidden;
        background: transparent;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        box-sizing: border-box;
    }

    .card-neon-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(
        from 0deg,   
            #4f46e5 0%,   
            #06b6d4 30%,
            #3b82f6 60%,
            #4f46e5 100%
        );
        animation: spin-neon 4s linear infinite;
        z-index: 0;
    }

    @keyframes spin-neon {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .card-inner-content {
        position: relative;
        z-index: 1;
        border-radius: 16px;
        background-color: #0f172a !important; /* Slate-900 */
        padding: 32px 30px;
        text-align: center;
        color: #ffffff;
        box-sizing: border-box;
    }

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

    .icon-headset-circle svg {
        width: 32px;
        height: 32px;
        fill: #ffffff;
    }

    .title-resolv {
        font-size: 1.85rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 8px;
    }

    .sub-text {
        color: #94a3b8;
        font-size: 0.88rem;
        margin-bottom: 24px;
        line-height: 1.4;
    }

    .input-group-custom {
        display: flex;
        align-items: center;
        background-color: #020617;
        border: 1px solid #334155;
        border-radius: 10px;
        padding: 0 14px;
        margin-bottom: 18px;
        transition: all 0.2s;
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

    .label-title {
        display: block;
        text-align: left;
        font-size: 0.82rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 6px;
    }

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
    }

    .btn-submit-login:hover {
        background-color: #4338ca !important;
        transform: translateY(-1px);
    }

    .back-to-login {
        margin-top: 24px;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .back-to-login a {
        color: #818cf8;
        text-decoration: none;
        font-weight: 700;
    }

    .back-to-login a:hover {
        text-decoration: underline;
    }
</style>

<div class="login-wrapper">
    <div class="card-neon-box">
        <div class="card-inner-content">
            <div class="icon-headset-circle">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-9h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6-5c1.71 0 3.1 1.39 3.1 3.1v2H8.9V6c0-1.71 1.39-3.1 3.1-3.1z"/>
                </svg>
            </div>

            <h2 class="title-resolv">Forgot Password?</h2>
            <p class="sub-text">Enter your email address and we will send you a password reset link.</p>

            @if (session('status'))
                <div style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); padding: 12px; border-radius: 10px; font-size: 0.88rem; margin-bottom: 22px;">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: rgba(225, 29, 72, 0.2); color: #fda4af; border: 1px solid rgba(225, 29, 72, 0.4); padding: 12px; border-radius: 10px; font-size: 0.88rem; margin-bottom: 22px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div>
                    <label class="label-title" for="email">Email Address</label>
                    <div class="input-group-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                    </div>
                </div>

                <button type="submit" class="btn-submit-login">
                    <span>Send Reset Link</span>
                </button>
            </form>

            <div class="back-to-login">
                Remember your password? <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
</div>
@endsection