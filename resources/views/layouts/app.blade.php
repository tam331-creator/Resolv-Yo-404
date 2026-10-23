<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RESOLV YO 404 - IT Support Helpdesk</title>
    
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .goog-te-banner-frame.skiptranslate, 
        iframe.goog-te-banner-frame,
        .goog-te-gadget-icon, 
        .goog-te-menu-value span:nth-child(3),
        .VIpgJd-y60fv-t42lh-M223bf-H231b,
        #goog-gt-tt,
        .goog-te-balloon-frame,
        div.skiptranslate {
            display: none !important;
        }
        body {
            top: 0px !important;
            position: static !important;
            background-color: #020617 !important; /* Solid Dark Slate-950 (Tanpa Sirkuit/Gambar) */
            color: #e2e8f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .lang-select-box {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
            border: 1px solid #334155 !important;
            border-radius: 8px;
            padding: 5px 8px;
            font-size: 0.75rem;
            outline: none;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
        }
        .lang-select-box:hover {
            border-color: #6366f1 !important;
            color: #ffffff !important;
        }

        @keyframes logoPulse {
            0%, 100% {
                transform: scale(1);
                filter: drop-shadow(0 0 4px rgba(34, 197, 94, 0.6));
            }
            50% {
                transform: scale(1.06);
                filter: drop-shadow(0 0 12px rgba(34, 197, 94, 0.9)) drop-shadow(0 0 18px rgba(56, 189, 248, 0.8));
            }
        }

        .animated-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            animation: logoPulse 2.5s infinite ease-in-out;
        }

        @keyframes dashPulse {
            0%, 100% {
                opacity: 1;
                transform: scaleX(1);
                filter: drop-shadow(0 0 8px currentColor);
            }
            50% {
                opacity: 0.2;
                transform: scaleX(0.7);
                filter: drop-shadow(0 0 1px currentColor);
            }
        }

        .pulse-dash {
            display: inline-block;
            margin: 0 2px;
            animation: dashPulse 1.2s infinite ease-in-out;
        }

        .glow-text-green { text-shadow: 0 0 10px rgba(34, 197, 94, 0.8); }
        .glow-text-blue { text-shadow: 0 0 10px rgba(56, 189, 248, 0.8); }
        .glow-text-red { text-shadow: 0 0 10px rgba(239, 68, 68, 0.8); }

        /* Custom Card Styling Untuk Navigation Item Sidebar */
        .sidebar-card-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: #cbd5e1;
            font-size: 0.825rem;
            font-weight: 600;
            border-radius: 0.75rem;
            background-color: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(51, 65, 85, 0.7);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar-card-item:hover {
            transform: translateX(4px);
            background-color: rgba(30, 41, 59, 0.9);
            color: #ffffff;
            border-color: rgba(148, 163, 184, 0.5);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        }

        /* Style Khas bila Active */
        .nav-sky.active { border-color: #38bdf8; background: linear-gradient(90deg, rgba(56,189,248,0.2) 0%, rgba(15,23,42,0.8) 100%); color: #38bdf8; }
        .nav-amber.active { border-color: #fbbf24; background: linear-gradient(90deg, rgba(251,191,36,0.2) 0%, rgba(15,23,42,0.8) 100%); color: #fbbf24; }
        .nav-rose.active { border-color: #f43f5e; background: linear-gradient(90deg, rgba(244,63,94,0.2) 0%, rgba(15,23,42,0.8) 100%); color: #f43f5e; }
        .nav-purple.active { border-color: #c084fc; background: linear-gradient(90deg, rgba(192,132,252,0.2) 0%, rgba(15,23,42,0.8) 100%); color: #c084fc; }
        .nav-indigo.active { border-color: #818cf8; background: linear-gradient(90deg, rgba(129,140,248,0.2) 0%, rgba(15,23,42,0.8) 100%); color: #818cf8; }
        .nav-emerald.active { border-color: #34d399; background: linear-gradient(90deg, rgba(52,211,153,0.2) 0%, rgba(15,23,42,0.8) 100%); color: #34d399; }
        .nav-cyan.active { border-color: #22d3ee; background: linear-gradient(90deg, rgba(34,211,238,0.2) 0%, rgba(15,23,42,0.8) 100%); color: #22d3ee; }
        .nav-slate.active { border-color: #94a3b8; background: linear-gradient(90deg, rgba(148,163,184,0.2) 0%, rgba(15,23,42,0.8) 100%); color: #ffffff; }

        /* FLOATING CHATBOT BUTTON EXACT STYLE */
        .chatbot-floating-btn {
            background-color: #5558ff !important; /* Warna persis seperti gambar SS */
            box-shadow: 0 0 25px rgba(85, 88, 255, 0.5) !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
    window.onlineUsersList = new Set();
    </script>

</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex flex-col">

@if(request()->routeIs('login', 'register'))
    <main class="w-full min-h-screen">
        @yield('content')
    </main>
@else
    @auth
    <div class="flex flex-1 min-h-screen">
        
        <!-- SIDEBAR NAVIGASI KIRI (STICKY & TAK IKUT SCROLL PAGE) -->
        <aside id="main-sidebar" class="w-64 bg-slate-950/95 backdrop-blur-md border-r border-slate-800/80 flex flex-col justify-between shrink-0 transition-all duration-300 z-40 fixed lg:sticky lg:top-0 lg:h-screen inset-y-0 left-0 -translate-x-full lg:translate-x-0">
            <div class="flex flex-col h-full min-h-0 overflow-hidden">
                <!-- Header Sidebar: Logo -->
                <div class="p-4 border-b border-slate-800/80 flex items-center justify-between shrink-0">
                    <a href="{{ route('tickets.index') }}" class="animated-logo select-none notranslate" translate="no">
                        <i class="fa-solid fa-headset text-green-400 text-xl"></i>
                        <span class="font-bold text-lg tracking-wide flex items-center notranslate" translate="no">
                            <span class="text-green-400 glow-text-green notranslate" translate="no">RESOLV</span>
                            <span class="pulse-dash text-green-400 glow-text-green notranslate" translate="no">–</span>
                            <span class="text-sky-400 glow-text-blue notranslate" translate="no">YO</span>
                            <span class="pulse-dash text-sky-400 glow-text-blue notranslate" style="animation-delay: 0.4s;" translate="no">–</span>
                            <span class="text-red-500 glow-text-red notranslate" translate="no">404</span>
                        </span>
                    </a>
                    <button onclick="toggleSidebarMenu()" type="button" class="lg:hidden text-slate-400 hover:text-white">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Senarai Menu Utama -->
                <nav class="p-3 space-y-3.5 overflow-y-auto flex-1">
                    @php
                        $userRole = Auth::user()->role ?? 'staff';
                    @endphp

                    <!-- 1. DASHBOARD -->
                    <a href="{{ route('tickets.index') }}" 
                       class="sidebar-card-item nav-sky {{ request()->routeIs('tickets.index') && !request()->has('manage') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-sky-500/10 border border-sky-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-gauge text-sky-400 text-xs"></i>
                        </div>
                        <span>Dashboard</span>
                    </a>

                    <!-- 2. CREATE TICKET -->
                    <a href="{{ route('tickets.create') }}" 
                       class="sidebar-card-item nav-amber {{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-plus text-amber-400 text-xs"></i>
                        </div>
                        <span>Create Ticket</span>
                    </a>

                    <!-- 3. MANAGE TICKETS (ADMIN & TECHNICIAN) -->
                    @if(in_array($userRole, ['admin', 'technician']))
                    <a href="{{ route('tickets.index', ['manage' => 1]) }}" 
                       class="sidebar-card-item nav-rose {{ request()->has('manage') || request()->routeIs('tickets.edit') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-rose-500/10 border border-rose-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-list-check text-rose-400 text-xs"></i>
                        </div>
                        <span>Manage Tickets</span>
                    </a>
                    @endif

                    <!-- 3. MY TICKETS (STAFF SAHAJA) -->
                    @if($userRole === 'staff')
                    <a href="{{ route('tickets.index', ['manage' => 1]) }}" 
                       class="sidebar-card-item nav-rose {{ request()->has('manage') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-rose-500/10 border border-rose-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-ticket text-rose-400 text-xs"></i>
                        </div>
                        <span>My Tickets</span>
                    </a>
                    @endif

                    <!-- 4. IT ASSET MANAGEMENT (ADMIN & TECHNICIAN) -->
                    @if(in_array($userRole, ['admin', 'technician']))
                    <a href="{{ Route::has('assets.index') ? route('assets.index') : '#' }}" 
                       class="sidebar-card-item nav-purple {{ request()->routeIs('assets.index') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-purple-500/10 border border-purple-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-laptop-code text-purple-400 text-xs"></i>
                        </div>
                        <span>IT Asset Management</span>
                    </a>
                    @endif

                    <!-- 5. ADMINISTRATION CENTER (ADMIN SAHAJA) -->
                    @if($userRole === 'admin')
                    <a href="{{ Route::has('admin.center') ? route('admin.center') : '#' }}" 
                       class="sidebar-card-item nav-indigo {{ request()->routeIs('admin.center') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-sliders text-indigo-400 text-xs"></i>
                        </div>
                        <span>Administration Center</span>
                    </a>
                    @endif

                    <!-- 6. ANALYTICS (ADMIN SAHAJA) -->
                    @if($userRole === 'admin')
                    <a href="{{ Route::has('analytics.index') ? route('analytics.index') : '#' }}" 
                       class="sidebar-card-item nav-emerald {{ request()->routeIs('analytics.index') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-chart-pie text-emerald-400 text-xs"></i>
                        </div>
                        <span>Analytics</span>
                    </a>
                    @endif

                    <!-- 7. POLICIES & KNOWLEDGE BASE -->
                    <a href="{{ Route::has('policies.index') ? route('policies.index') : '#' }}" 
                       class="sidebar-card-item nav-cyan {{ request()->routeIs('policies.index') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-book-bookmark text-cyan-400 text-xs"></i>
                        </div>
                        <span>Policies & Knowledge</span>
                    </a>

                    <!-- 8. PROFILE & SETTING -->
                    <a href="{{ Route::has('settings.index') ? route('settings.index') : '#' }}" 
                       class="sidebar-card-item nav-slate {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                        <div class="w-7 h-7 rounded-lg bg-slate-500/10 border border-slate-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-user-gear text-slate-400 text-xs"></i>
                        </div>
                        <span>Profile & Setting</span>
                    </a>
                </nav>

                <!-- Bottom Section -->
                <div class="p-3 border-t border-slate-800/80 bg-slate-900/40 space-y-2.5 shrink-0">
                    <div class="bg-slate-900 border border-slate-800 p-2.5 rounded-xl space-y-2 shadow-lg">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-circle-user text-indigo-400 text-base"></i>
                                </div>
                                <div class="truncate">
                                    <p class="font-bold text-slate-200 text-xs truncate leading-tight">{{ Auth::user()->name }}</p>
                                    <span class="inline-block bg-slate-800 text-indigo-400 border border-slate-700/80 px-1.5 py-0.2 rounded uppercase text-[8px] font-extrabold notranslate mt-0.5" translate="no">
                                        {{ Auth::user()->role ?? 'STAFF' }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ Route::has('logout') ? route('logout') : url('/logout') }}" onclick="handleLogout(event)" title="Logout" class="border border-rose-600/60 bg-rose-950/40 text-rose-400 hover:bg-rose-600 hover:text-white w-8 h-8 rounded-lg transition duration-200 flex items-center justify-center shrink-0 cursor-pointer">
                                <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            </a>
                        </div>

                        <div>
                            <select id="languageSelectNav" class="lang-select-box notranslate" translate="no" onchange="fastTranslate(this.value)">
                                <option value="" class="notranslate" translate="no">🌐 English (Original)</option>
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
                    </div>

                    <div class="text-center pt-0.5">
                        <p class="text-[9px] text-slate-400 font-semibold tracking-wider uppercase">Track &bull; Manage &bull; Optimize</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- KANDUNGAN UTAMA -->
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-slate-950/90 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-30 px-4 py-2.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebarMenu()" type="button" class="lg:hidden text-slate-300 hover:text-white border border-slate-700/80 rounded-lg px-2.5 py-1.5 bg-slate-900/80 focus:outline-none transition">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <span class="text-xs font-bold text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-indigo-400"></i> Enterprise Helpdesk System
                    </span>
                </div>
            </header>

            <main class="flex-grow w-full px-3 sm:px-6 py-4 sm:py-6">
                @yield('content')
            </main>

            <footer class="bg-slate-950/90 border-t border-slate-800/80 py-3 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} RESOLVE YO 404 - IT Platform. All Rights Reserved.
            </footer>
        </div>
    </div>
    @endauth

    @guest
    <div class="absolute top-3 right-4 z-50">
        <select id="languageSelectNav" class="lang-select-box notranslate" translate="no" onchange="fastTranslate(this.value)">
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
    <main class="flex-grow w-full">
        @yield('content')
    </main>
    <footer class="bg-slate-950/90 border-t border-slate-800/80 py-3 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} RESOLVE YO 404 - IT Platform. All Rights Reserved.
    </footer>
    @endguest
@endif

@auth
<div class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50">
    <div id="ai-chat-window" class="hidden w-[92vw] sm:w-96 md:w-[420px] h-[550px] max-h-[80vh] bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden mb-3 flex-col text-sm transition-all duration-300">
        <div class="bg-indigo-600 text-white p-3.5 font-bold flex justify-between items-center shadow-md">
            <span class="flex items-center gap-2 text-base">
                <span class="text-xl leading-none">🤖</span>
                RESOLV AI Support
            </span>
            <button onclick="toggleChatbot()" class="text-white hover:text-slate-200 font-bold text-xl leading-none transition">&times;</button>
        </div>
        
        <div id="chat-messages" class="p-4 flex-1 overflow-y-auto space-y-3 bg-slate-950 text-slate-200 leading-relaxed text-sm">
            <div class="bg-slate-800 p-3 rounded-xl max-w-[90%] border border-slate-700">
                Hi! I am RESOLV AI. Is there any IT issue or Screenshot (SS) I can help you with?
            </div>
        </div>

        <div id="file-preview-container" class="hidden px-3 py-2 bg-slate-800 border-t border-slate-700 text-xs text-indigo-400 flex justify-between items-center gap-2">
            <div class="flex items-center gap-2 overflow-hidden">
                <img id="image-preview-thumb" class="hidden w-10 h-10 object-cover rounded border border-indigo-500/50" />
                <span id="file-preview-name" class="truncate max-w-[180px]"></span>
            </div>
            <button onclick="clearSelectedFile()" class="text-slate-400 hover:text-rose-400 font-bold px-2 py-1">&times;</button>
        </div>

        <div class="p-3 bg-slate-900 border-t border-slate-800 flex gap-2 items-center">
            <label for="chat-file" title="Attach File / Screenshot" class="bg-slate-800 hover:bg-slate-700 text-slate-300 p-2.5 rounded-xl cursor-pointer border border-slate-700 transition flex items-center justify-center shrink-0">
                <i class="fa-solid fa-paperclip text-base"></i>
            </label>
            <input type="file" id="chat-file" accept="image/*,.pdf,.doc,.docx,.txt" class="hidden" onchange="handleFileSelect(event)">

            <input type="text" id="chat-input" class="w-full bg-slate-800 text-white rounded-xl px-3 sm:px-4 py-2.5 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-indigo-500 border border-slate-700" placeholder="Type or Paste SS (Ctrl+V)..." onkeypress="handleChatKeyPress(event)" onpaste="handlePasteEvent(event)">
            
            <button onclick="sendChatMessage()" class="bg-indigo-600 text-white px-3.5 sm:px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-500 transition flex items-center justify-center shrink-0">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <!-- FLOATING CHATBOT BUTTON DENGAN EMOJI ROBOT 🤖 & WARNA BIRU/INDIGO PERSIS SS -->
    <button onclick="toggleChatbot()" class="chatbot-floating-btn text-white rounded-full shadow-2xl hover:scale-110 transition duration-300 flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16">
        <span class="text-2xl sm:text-3xl select-none leading-none">🤖</span>
    </button>
</div>
@endauth

<script>
    function toggleSidebarMenu() {
        const sidebar = document.getElementById('main-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('-translate-x-full');
        }
    }
</script>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            autoDisplay: false
        }, 'google_translate_element');
    }

    function fastTranslate(langCode) {
        var domain = window.location.hostname;
        if (!langCode) {
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + domain;
        } else {
            var cookieVal = "/en/" + langCode;
            document.cookie = "googtrans=" + cookieVal + "; path=/;";
            document.cookie = "googtrans=" + cookieVal + "; path=/; domain=" + domain;
        }
        location.reload();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var cookies = document.cookie.split(';');
        var currentLang = "";
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.indexOf("googtrans=") === 0) {
                var val = cookie.substring("googtrans=".length);
                var parts = val.split('/');
                if (parts.length >= 3) {
                    currentLang = parts[2];
                }
            }
        }
        var select2 = document.getElementById('languageSelectNav');
        if (select2) select2.value = currentLang;
    });
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
function handleLogout(event) {
    if (window.Echo) {
        try {
            window.Echo.leave('online-users');
            window.Echo.disconnect();
        } catch (e) {
            console.log('Echo disconnect error:', e);
        }
    }
}
</script>

@auth
<script>
let currentSelectedFile = null;

function toggleChatbot() {
    const win = document.getElementById('ai-chat-window');
    if (win.classList.contains('hidden')) {
        win.classList.remove('hidden');
        win.classList.add('flex');
    } else {
        win.classList.add('hidden');
        win.classList.remove('flex');
    }
}

function handleChatKeyPress(e) {
    if (e.key === 'Enter') sendChatMessage();
}

function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) processFilePreview(file);
}

function handlePasteEvent(e) {
    const items = (e.clipboardData || e.originalEvent.clipboardData).items;
    for (let item of items) {
        if (item.type.indexOf("image") === 0) {
            const blob = item.getAsFile();
            const screenshotFile = new File([blob], "screenshot_" + Date.now() + ".png", { type: blob.type });
            processFilePreview(screenshotFile);
            break;
        }
    }
}

function processFilePreview(file) {
    currentSelectedFile = file;
    const previewContainer = document.getElementById('file-preview-container');
    const nameLabel = document.getElementById('file-preview-name');
    const thumbImg = document.getElementById('image-preview-thumb');

    nameLabel.innerText = file.name;
    previewContainer.classList.remove('hidden');

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            thumbImg.src = e.target.result;
            thumbImg.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        thumbImg.classList.add('hidden');
        thumbImg.src = '';
    }
}

function clearSelectedFile() {
    currentSelectedFile = null;
    document.getElementById('chat-file').value = '';
    document.getElementById('file-preview-container').classList.add('hidden');
    document.getElementById('image-preview-thumb').classList.add('hidden');
    document.getElementById('image-preview-thumb').src = '';
}

function sendChatMessage() {
    const input = document.getElementById('chat-input');
    const msgBox = document.getElementById('chat-messages');
    const text = input.value.trim();
    const file = currentSelectedFile;

    if (!text && !file) return;

    let userMsgContent = text ? `<p>${text}</p>` : '';
    let fileDataUrl = null;

    if (file) {
        if (file.type.startsWith('image/')) {
            const previewThumb = document.getElementById('image-preview-thumb');
            fileDataUrl = previewThumb.src;
            userMsgContent += `<div class="mt-2"><img src="${fileDataUrl}" class="max-w-[200px] max-h-[150px] rounded-lg border border-indigo-400/30 object-cover"></div>`;
        } else {
            userMsgContent += `<div class="mt-1 text-xs text-indigo-300 font-medium"><i class="fa-solid fa-paperclip mr-1"></i>${file.name}</div>`;
        }
    }

    msgBox.innerHTML += `<div class="bg-indigo-600/30 text-indigo-200 border border-indigo-500/30 p-3 rounded-xl max-w-[85%] ml-auto text-sm">${userMsgContent}</div>`;
    
    input.value = '';
    clearSelectedFile();
    msgBox.scrollTop = msgBox.scrollHeight;

    const loadingId = 'loading-' + Date.now();
    msgBox.innerHTML += `<div id="${loadingId}" class="bg-slate-800 p-3 rounded-xl max-w-[85%] text-sm border border-slate-700 italic text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Thinking...</div>`;
    msgBox.scrollTop = msgBox.scrollHeight;

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

    const formData = new FormData();
    if (text) formData.append('prompt', text);
    if (file) formData.append('file', file);

    fetch("{{ route('ai.chat') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": token
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById(loadingId).remove();
        let reply = data.reply || data.error || 'Sorry, an error occurred.';
        reply = reply.replace(/[\*\#\_]/g, '').replace(/---/g, '');

        const formattedReply = reply
            .split('\n')
            .filter(line => line.trim() !== '')
            .map(line => `<p class="mb-2.5 last:mb-0">${line}</p>`)
            .join('');

        msgBox.innerHTML += `<div class="bg-slate-800 p-3 rounded-xl max-w-[90%] text-sm border border-slate-700 leading-relaxed">${formattedReply}</div>`;
        msgBox.scrollTop = msgBox.scrollHeight;
    })
    .catch(() => {
        document.getElementById(loadingId).remove();
        msgBox.innerHTML += `<div class="bg-rose-900/40 text-rose-300 p-3 rounded-xl max-w-[85%] text-sm border border-rose-800">Connection error to AI server.</div>`;
        msgBox.scrollTop = msgBox.scrollHeight;
    });
}
</script>
@endauth

<script>
    const shownNotificationIds = new Set();

    document.addEventListener('DOMContentLoaded', function() {
        if ("Notification" in window && Notification.permission !== "granted" && Notification.permission !== "denied") {
            Notification.requestPermission();
        }
    });

    function showBrowserNotification(title, bodyText, iconUrl = '', notiId = null) {
        const uniqueTag = notiId ? String(notiId) : (title + '_' + bodyText).replace(/\s+/g, '_');

        if (shownNotificationIds.has(uniqueTag)) {
            return;
        }
        shownNotificationIds.add(uniqueTag);

        if ("Notification" in window && Notification.permission === "granted") {
            new Notification(title, {
                body: bodyText,
                icon: iconUrl || '/favicon.ico',
                tag: uniqueTag,
                renotify: false
            });
        }
    }

    @auth
    if (window.Echo) {
        window.Echo.join('online-users')
            .here((users) => {
                users.forEach(user => window.onlineUsersList.add(user.id));
                window.dispatchEvent(new CustomEvent('online-users-updated'));
            })
            .joining((user) => {
                window.onlineUsersList.add(user.id);
                window.dispatchEvent(new CustomEvent('online-users-updated'));
            })
            .leaving((user) => {
                window.onlineUsersList.delete(user.id);
                window.dispatchEvent(new CustomEvent('online-users-updated'));
            });
    }

    let lastNotificationCheck = new Date().toISOString();

    function pollForNotifications() {
        const checkTime = lastNotificationCheck;

        fetch(`/notifications/check?since=${encodeURIComponent(checkTime)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.timestamp) {
                lastNotificationCheck = data.timestamp;
            }

            if (data.notifications && data.notifications.length > 0) {
                data.notifications.forEach(noti => {
                    showBrowserNotification(
                        noti.title,
                        noti.message,
                        '',
                        noti.id || null
                    );
                });
            }
        })
        .catch(err => console.error('Notification error:', err));
    }

    setInterval(pollForNotifications, 10000);
    @endauth
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if ("Notification" in window && Notification.permission !== "granted" && Notification.permission !== "denied") {
        Notification.requestPermission();
    }

    let lastCheckedTime = new Date().toISOString();
    const notifiedIds = new Set();

    function checkGlobalNotifications() {
        if (!("Notification" in window) || Notification.permission !== "granted") {
            return;
        }

        fetch(`/tickets/check-notifications?since=${encodeURIComponent(lastCheckedTime)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Network error");
            return response.json();
        })
        .then(data => {
            if (data.timestamp) {
                lastCheckedTime = data.timestamp;
            }

            if (data.notifications && data.notifications.length > 0) {
                data.notifications.forEach(notif => {
                    if (!notifiedIds.has(notif.id)) {
                        notifiedIds.add(notif.id);

                        const notification = new Notification(notif.title, {
                            body: notif.message,
                            icon: '/favicon.ico',
                            tag: notif.id
                        });

                        notification.onclick = function () {
                            window.focus();
                            if (notif.url) {
                                window.location.href = notif.url;
                            }
                        };
                    }
                });
            }
        })
        .catch(err => console.error("Notification polling error:", err));
    }

    setInterval(checkGlobalNotifications, 10000);
});
</script>

</body>
</html>