@extends('layouts.app')

@section('content')
<style>
    body {
        top: 0px !important;
        margin: 0;
        padding: 0;
        background-color: #000000 !important;
        position: relative;
        min-height: 100vh;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-image: url('{{ asset("bg1.jpg") }}');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        z-index: -1;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }

    .card-neon-box {
        position: relative;
        width: 100%;
        max-width: 440px;
        border-radius: 22px;
        padding: 2.5px;
        overflow: hidden;
        background: transparent;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8);
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
            #00f2fe 0deg 90deg,
            #00ff66 90deg 180deg,
            #fef57b 180deg 270deg,
            #ff0055 270deg 360deg
        );
        animation: spin 4s linear infinite;
        z-index: 0;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .card-inner-content {
        position: relative;
        z-index: 1;
        background-color: #030712 !important;
        border-radius: 20px;
        padding: 32px 30px;
        text-align: center;
        color: #ffffff;
        box-sizing: border-box;
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
    }

    .input-group-custom {
        display: flex;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 0 14px;
        margin-bottom: 18px;
        transition: all 0.2s;
    }

    .input-group-custom:focus-within {
        border-color: #0284c7;
        box-shadow: 0 0 10px rgba(2, 132, 199, 0.35);
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
        background-color: #0d59bf !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 13px !important;
        font-weight: 700 !important;
        font-size: 0.98rem !important;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.2s ease;
    }

    .btn-submit-login:hover {
        background-color: #0b4ca3 !important;
    }
</style>

<div class="login-wrapper">
    <div class="card-neon-box">
        <div class="card-inner-content">
            <h2 class="title-resolv">Reset Password</h2>
            <p class="sub-text">Enter your new password below.</p>

            @if($errors->any())
                <div style="background: rgba(220, 53, 69, 0.2); color: #ff8182; border: 1px solid rgba(220, 53, 69, 0.4); padding: 12px; border-radius: 12px; font-size: 0.88rem; margin-bottom: 22px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="label-title" for="email">Email Address</label>
                    <div class="input-group-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly>
                    </div>
                </div>

                <div>
                    <label class="label-title" for="password">New Password</label>
                    <div class="input-group-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        <input type="password" id="password" name="password" placeholder="••••••••" required autofocus>
                    </div>
                </div>

                <div>
                    <label class="label-title" for="password_confirmation">Confirm Password</label>
                    <div class="input-group-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit-login">
                    <span>Reset Password</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection