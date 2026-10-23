@extends('layouts.app')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<style>
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
        overflow-x: hidden;
    }

    .top-header-bar {
        position: fixed;
        top: 15px;
        left: 15px;
        right: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 99999;
        pointer-events: none;
    }

    .top-header-bar * {
        pointer-events: auto;
    }

    .btn-alert-guide {
        background-color: rgba(234, 179, 8, 0.15) !important;
        color: #facc15 !important;
        border: 1px solid rgba(234, 179, 8, 0.4) !important;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-alert-guide:hover {
        background-color: rgba(234, 179, 8, 0.25) !important;
        border-color: #facc15 !important;
        transform: translateY(-1px);
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
        width: auto !important;
        max-width: 160px;
        flex-shrink: 0;
    }

    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px 40px 20px;
        box-sizing: border-box;
        transition: padding-left 0.15s ease;
    }

    /* Kad Slate-900 Clean Enterprise */
    .card-neon-box {
        position: relative;
        width: 100%;
        max-width: 680px !important;
        border-radius: 16px;
        background-color: #0f172a; /* Slate-900 */
        border: 1px solid #1e293b; /* Slate-800 */
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        box-sizing: border-box;
    }

    .card-inner-content {
        position: relative;
        z-index: 1;
        border-radius: 16px;
        padding: 40px 45px;
        text-align: center;
        color: #ffffff;
        box-sizing: border-box;
    }

    .icon-user-circle {
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

    .icon-user-circle svg {
        width: 32px;
        height: 32px;
        fill: #ffffff;
    }

    .title-register {
        font-size: 1.85rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 6px;
        letter-spacing: 0.3px;
    }

    .sub-text {
        color: #94a3b8;
        font-size: 0.92rem;
        margin-bottom: 28px;
    }

    .form-grid-two {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-group-item {
        margin-bottom: 16px;
        text-align: left;
    }

    .label-title {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 6px;
    }

    .input-group-custom {
        display: flex;
        align-items: center;
        background-color: #020617;
        border: 1px solid #334155;
        border-radius: 10px;
        padding: 0 16px;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .input-group-custom:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 12px rgba(99, 102, 241, 0.3);
    }

    .input-group-custom svg {
        width: 20px;
        height: 20px;
        fill: #64748b;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .input-group-custom input, .input-group-custom select {
        width: 100%;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        color: #ffffff !important;
        padding: 12px 0;
        font-size: 0.92rem;
    }

    .input-group-custom select option {
        background-color: #0f172a;
        color: #ffffff;
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

    /* Butang Submit - Indigo Primary */
    .btn-submit-register {
        width: 100% !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #4f46e5 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 14px !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .btn-submit-register:hover {
        background-color: #4338ca !important;
        transform: translateY(-1px);
    }

    .btn-submit-register svg {
        width: 18px;
        height: 18px;
        fill: #ffffff;
    }

    .login-box {
        margin-top: 22px;
        font-size: 0.88rem;
        color: #94a3b8;
    }

    .login-box a {
        color: #818cf8;
        text-decoration: none;
        font-weight: 700;
        margin-left: 4px;
    }

    .login-box a:hover {
        text-decoration: underline;
    }

    .guide-side-panel {
        position: fixed;
        top: 60px;
        left: 0;
        transform: translateX(-105%);
        width: 420px;
        min-width: 260px;
        max-width: 75vw;
        height: calc(100vh - 80px);
        background: rgba(15, 23, 42, 0.98);
        border: 1px solid rgba(234, 179, 8, 0.4);
        border-left: none;
        border-radius: 0 16px 16px 0;
        z-index: 99998;
        padding: 24px 20px;
        color: #e2e8f0;
        text-align: left;
        box-shadow: 10px 0 35px rgba(0, 0, 0, 0.85);
        box-sizing: border-box;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .guide-side-panel.open {
        transform: translateX(0);
    }

    .resizer-bar {
        position: absolute;
        top: 0;
        right: 0;
        width: 10px;
        height: 100%;
        cursor: col-resize;
        background: rgba(99, 102, 241, 0.2);
        border-right: 2px solid #6366f1;
        z-index: 99;
        transition: background 0.2s;
    }

    .resizer-bar:hover, .resizer-bar.is-dragging {
        background: #6366f1 !important;
        box-shadow: 0 0 12px #6366f1;
    }

    .guide-side-panel h3 {
        color: #facc15;
        font-size: 1.15rem;
        margin-top: 0;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .guide-section-item {
        margin-bottom: 16px;
        background: rgba(255, 255, 255, 0.03);
        padding: 12px 14px;
        border-radius: 12px;
        border-left: 3px solid #6366f1;
    }

    .guide-title {
        font-weight: 700;
        color: #818cf8;
        font-size: 0.9rem;
        margin-bottom: 6px;
    }

    .guide-text {
        font-size: 0.83rem;
        color: #cbd5e1;
        line-height: 1.5;
        margin: 4px 0;
        word-break: break-word;
    }

    body.is-resizing {
        user-select: none !important;
        cursor: col-resize !important;
    }

    @media (max-width: 768px) {
        .card-inner-content {
            padding: 32px 24px;
        }

        .form-grid-two {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .form-group-full {
            grid-column: span 1;
        }

        .guide-side-panel {
            min-width: 100vw;
            max-width: 100vw;
            border-radius: 0;
            top: 55px;
            height: calc(100vh - 55px);
        }

        .resizer-bar {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .top-header-bar {
            top: 10px;
            left: 10px;
            right: 10px;
        }

        .btn-alert-guide {
            padding: 5px 10px;
            font-size: 0.75rem;
        }

        .lang-select-box {
            padding: 4px 8px;
            font-size: 0.78rem;
            max-width: 120px;
        }

        .register-wrapper {
            padding: 70px 12px 20px 12px;
        }

        .card-inner-content {
            padding: 24px 16px;
        }

        .icon-user-circle {
            width: 58px;
            height: 58px;
            margin-bottom: 12px;
        }

        .icon-user-circle svg {
            width: 28px;
            height: 28px;
        }

        .title-register {
            font-size: 1.5rem;
        }

        .sub-text {
            font-size: 0.84rem;
            margin-bottom: 20px;
        }

        .input-group-custom input, 
        .input-group-custom select {
            padding: 10px 0;
            font-size: 0.88rem;
        }

        .btn-submit-register {
            padding: 12px !important;
            font-size: 0.92rem !important;
        }
    }
</style>

<!-- Top Floating Bar (Left: Alert Guide | Right: Language Translate) -->
<div class="top-header-bar">
    <button type="button" class="btn-alert-guide" onclick="toggleSideGuide()">
        <span>⚠️ Important Registration Information</span>
    </button>

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

<!-- Resizable Side Panel Registration Guidelines -->
<div id="sideGuidePanel" class="guide-side-panel">
    <!-- VS Code Style Resizer Handle -->
    <div id="resizerHandle" class="resizer-bar"></div>

    <h3>
        <span>📋 Registration Guidelines</span>
        <span style="cursor:pointer; font-size: 1.1rem; color: #facc15;" onclick="toggleSideGuide()">✕</span>
    </h3>
    <hr style="border-color: rgba(255,255,255,0.1); margin-bottom: 16px;">

    <!-- 1. Full Name -->
    <div class="guide-section-item">
        <div class="guide-title">1. Full Name</div>
        <div class="guide-text"><b>• Guideline:</b> Enter your full name as per NRIC / official company records.</div>
        <div class="guide-text"><b>• Reason:</b> Helps the IT team and system verify account identity and asset ownership (IT Asset Management).</div>
        <div class="guide-text"><b>• Example:</b> Thaksin A/L Bun Chuan or Ahmad Razali bin Ismail</div>
    </div>

    <!-- 2. Official Email -->
    <div class="guide-section-item">
        <div class="guide-title">2. Official Email</div>
        <div class="guide-text"><b>• Guideline:</b> Please enter an active and accessible email address.</div>
        <div class="guide-text"><b>• Example:</b> tam@gmail.com or tam@company.com</div>
        <div class="guide-text"><b>• Reason:</b> The system will send ticket notifications, status updates, and password reset links to this registered email address.</div>
    </div>

    <!-- 3. Phone Number -->
    <div class="guide-section-item">
        <div class="guide-title">3. Phone Number / WhatsApp</div>
        <div class="guide-text"><b>• Guideline:</b> Enter an active phone number that is reachable or linked to WhatsApp.</div>
        <div class="guide-text"><b>• Reason:</b> IT Officers / Technicians will make immediate calls or send messages if an IT issue requires critical on-site action.</div>
        <div class="guide-text"><b>• Format:</b> 0123456789 (Without - or spaces)</div>
    </div>

    <!-- 4. Role -->
    <div class="guide-section-item">
        <div class="guide-title">4. Role</div>
        <div class="guide-text"><b>• Selection Guidelines:</b></div>
        <div class="guide-text" style="padding-left: 10px;">- <b>Staff:</b> For employees submitting tickets or reporting IT issues.</div>
        <div class="guide-text" style="padding-left: 10px;">- <b>Technician:</b> For IT Department staff responsible for managing and resolving tickets.</div>
        <div class="guide-text" style="margin-top: 6px;"><b>⚠️ Important:</b> All users outside the IT Department must select Staff.</div>
        <div class="guide-text"><b>🔍 Admin Review:</b> Incorrect role selections will be reviewed and revoked by System Admins.</div>
    </div>

    <!-- 5. Password -->
    <div class="guide-section-item">
        <div class="guide-title">5. Password</div>
        <div class="guide-text"><b>• Guideline:</b> Create a password of at least 8 characters containing a combination of letters and numbers.</div>
        <div class="guide-text"><b>• Security:</b> Do not use simple passwords (such as 123456 or password). Your password is encrypted and cannot be read by any administrator.</div>
    </div>
</div>

<div class="register-wrapper" id="registerWrapper">
    <div class="card-neon-box">
        <div class="card-inner-content">
            
            <div class="icon-user-circle">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>

            <h2 class="title-register">Register New Account</h2>
            <p class="sub-text">Join our IT Helpdesk portal today</p>

            @if($errors->any())
                <div style="background: rgba(225, 29, 72, 0.2); color: #fda4af; border: 1px solid rgba(225, 29, 72, 0.4); padding: 10px 14px; border-radius: 10px; font-size: 0.82rem; margin-bottom: 18px; text-align: left;">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" autocomplete="off">
                @csrf

                <div class="form-grid-two">
                    <div class="form-group-item">
                        <label class="label-title">Full Name</label>
                        <div class="input-group-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Thaksin" required autofocus autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group-item">
                        <label class="label-title">Official Email</label>
                        <div class="input-group-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group-item">
                        <label class="label-title">Phone Number (WhatsApp)</label>
                        <div class="input-group-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0123456789" required autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group-item">
                        <label class="label-title">Role</label>
                        <div class="input-group-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                            <select name="role" required>
                                <option value="staff" selected>Staff</option>
                                <option value="technician">Technician</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-item form-group-full">
                        <label class="label-title">Password</label>
                        <div class="input-group-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                            </svg>
                            <input type="password" id="reg_password" name="password" placeholder="••••••••" required autocomplete="new-password">
                            <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('reg_password', this)" title="Show/Hide Password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group-full">
                        <button type="submit" class="btn-submit-register">
                            <span>Register Account</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>

            <div class="login-box">
                Already have an account? <a href="{{ route('login') }}">Log In</a>
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

    function toggleSideGuide() {
        const panel = document.getElementById('sideGuidePanel');
        const wrapper = document.getElementById('registerWrapper');
        
        panel.classList.toggle('open');
        
        if (panel.classList.contains('open')) {
            if (window.innerWidth > 1024) {
                wrapper.style.paddingLeft = (panel.offsetWidth + 20) + 'px';
            }
        } else {
            wrapper.style.paddingLeft = '20px';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const panel = document.getElementById('sideGuidePanel');
        const resizer = document.getElementById('resizerHandle');
        const wrapper = document.getElementById('registerWrapper');
        let isResizing = false;

        resizer.addEventListener('mousedown', function(e) {
            e.preventDefault();
            isResizing = true;
            document.body.classList.add('is-resizing');
            resizer.classList.add('is-dragging');
        });

        window.addEventListener('mousemove', function(e) {
            if (!isResizing) return;

            let pointerX = e.clientX;
            const minW = 260;
            const maxW = window.innerWidth * 0.75;

            if (pointerX >= minW && pointerX <= maxW) {
                panel.style.width = pointerX + 'px';
                if (panel.classList.contains('open') && window.innerWidth > 1024) {
                    wrapper.style.paddingLeft = (pointerX + 20) + 'px';
                }
            }
        });

        window.addEventListener('mouseup', function() {
            if (isResizing) {
                isResizing = false;
                document.body.classList.remove('is-resizing');
                resizer.classList.remove('is-dragging');
            }
        });
    });
</script>
@endsection