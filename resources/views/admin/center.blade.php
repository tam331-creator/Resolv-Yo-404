@extends('layouts.app')

@section('content')
<!-- LEBARKAN KOTAK BORANG ADMINISTRATION CENTER -->
<div class="w-full max-w-[1600px] mx-auto space-y-4 px-4 py-4">

    <!-- Header Page -->
    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 flex justify-between items-center shadow-md">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-100 flex items-center gap-2.5">
                <i class="fa-solid fa-sliders text-indigo-400"></i> Administration Center
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">One-stop administrative portal to manage users, hardware assets, and software licenses.</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 text-xs px-4 py-2.5 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-sm"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 3-Tab Navigation Buttons -->
    <div class="flex border-b border-slate-800 gap-4 text-xs font-bold">
        <button onclick="switchTab('user')" id="tabUserBtn" class="pb-2.5 px-2 text-indigo-400 border-b-2 border-indigo-500 flex items-center gap-1.5 transition">
            <i class="fa-solid fa-users"></i> Tab 1: User Management (UM)
        </button>
        <button onclick="switchTab('asset')" id="tabAssetBtn" class="pb-2.5 px-2 text-slate-400 hover:text-slate-200 border-b-2 border-transparent flex items-center gap-1.5 transition">
            <i class="fa-solid fa-laptop text-indigo-400"></i> Tab 2: Asset Management (AM)
        </button>
        <button onclick="switchTab('software')" id="tabSoftwareBtn" class="pb-2.5 px-2 text-slate-400 hover:text-slate-200 border-b-2 border-transparent flex items-center gap-1.5 transition">
            <i class="fa-solid fa-cubes text-indigo-400"></i> Tab 3: Software Management (SM)
        </button>
    </div>

    <!-- TAB 1: USER MANAGEMENT (UM) -->
    <div id="tabUserContent" class="space-y-4">
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-xl shadow-xl space-y-3">
            <div class="flex justify-between items-center border-b border-slate-800 pb-2.5">
                <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-indigo-400"></i> Create New User
                </h3>
                <button type="button" onclick="addUserRow()" class="bg-indigo-600/30 hover:bg-indigo-600 text-indigo-200 hover:text-white border border-indigo-500/50 text-[11px] font-bold py-1 px-2.5 rounded-lg transition flex items-center gap-1">
                    <i class="fa-solid fa-plus"></i> Add More Entry (Expand)
                </button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off" class="space-y-4">
                @csrf
                <div id="userRowsContainer" class="space-y-4 divide-y divide-slate-800">
                    <div class="user-row-item pt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs font-medium text-slate-200 relative">
                        <div class="md:col-span-2 lg:col-span-3 flex justify-between items-center bg-slate-950 px-3 py-1 rounded-lg border border-slate-800">
                            <span class="font-bold text-indigo-400 text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-user"></i> User Entry #1
                            </span>
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Full Name *</label>
                            <input type="text" name="users[0][name]" required autocomplete="off" placeholder="e.g. Ahmad Razali" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Email Address *</label>
                            <input type="email" name="users[0][email]" required autocomplete="off" placeholder="e.g. ahmad@company.com" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Phone Number</label>
                            <input type="text" name="users[0][phone]" autocomplete="off" placeholder="e.g. 0123456789" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                        <div class="relative">
                            <label class="block mb-1 text-slate-300">Department *</label>
                            <div class="relative">
                                <input type="text" id="userDeptInput_0" name="users[0][department]" required placeholder="Type or select department..." autocomplete="off" 
                                       onfocus="showCustomDropdown('userDeptMenu_0')" onkeyup="filterCustomOptions('userDeptInput_0', 'userDeptMenu_0')"
                                       class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <div id="userDeptMenu_0" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                @foreach($departments as $dept)
                                    <div onclick="selectCustomOption('userDeptInput_0', 'userDeptMenu_0', '{{ $dept }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                        {{ $dept }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Role *</label>
                            <select name="users[0][role]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <option value="staff">Staff (End-User)</option>
                                <option value="technician">Technician</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Status *</label>
                            <select name="users[0][status]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block mb-1 text-slate-300">Initial Password *</label>
                            <input type="password" name="users[0][password]" required autocomplete="new-password" placeholder="Minimum 6 characters" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-slate-800">
                    <button type="button" onclick="addUserRow()" class="bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-300 border border-indigo-500/40 text-xs font-bold py-1.5 px-3 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa-solid fa-plus"></i> Add Another User
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2 px-5 rounded-lg shadow transition flex items-center gap-1.5">
                        <i class="fa-solid fa-check-double"></i> Save All Users
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: ASSET MANAGEMENT (AM) -->
    <div id="tabAssetContent" class="hidden space-y-4">
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-xl shadow-xl space-y-3">
            <div class="flex justify-between items-center border-b border-slate-800 pb-2.5">
                <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-laptop-medical text-indigo-400"></i> Register New Device / Asset
                </h3>
                <button type="button" onclick="addAssetRow()" class="bg-indigo-600/30 hover:bg-indigo-600 text-indigo-200 hover:text-white border border-indigo-500/50 text-[11px] font-bold py-1 px-2.5 rounded-lg transition flex items-center gap-1">
                    <i class="fa-solid fa-plus"></i> Add More Entry (Expand)
                </button>
            </div>

            <form action="{{ route('admin.assets.store') }}" method="POST" autocomplete="off" class="space-y-4">
                @csrf
                <div id="assetRowsContainer" class="space-y-4 divide-y divide-slate-800">
                    <div class="asset-row-item pt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs font-medium text-slate-200 relative">
                        <div class="md:col-span-2 lg:col-span-3 flex justify-between items-center bg-slate-950 px-3 py-1 rounded-lg border border-slate-800">
                            <span class="font-bold text-indigo-400 text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-laptop"></i> Asset Entry #1
                            </span>
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Asset Tag *</label>
                            <input type="text" name="assets[0][asset_tag]" required autocomplete="off" placeholder="e.g. TKS_LAP_001" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Asset Name *</label>
                            <input type="text" name="assets[0][name]" required autocomplete="off" placeholder="e.g. DELL Latitude 3420" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                        <div class="relative">
                            <label class="block mb-1 text-slate-300">Category / Type *</label>
                            <div class="relative">
                                <input type="text" id="assetCatSearch_0" name="assets[0][category]" required placeholder="Type category or select..." autocomplete="off"
                                       onfocus="showCustomDropdown('assetCatMenu_0')" onkeyup="filterCustomOptions('assetCatSearch_0', 'assetCatMenu_0')"
                                       class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <div id="assetCatMenu_0" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                @php $presetCategories = ['Laptop', 'Desktop', 'Printer', 'Monitor', 'Software', 'Network Device']; @endphp
                                @foreach($presetCategories as $cat)
                                    <div onclick="selectCustomOption('assetCatSearch_0', 'assetCatMenu_0', '{{ $cat }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                        {{ $cat }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Brand</label>
                            <input type="text" name="assets[0][brand]" autocomplete="off" placeholder="e.g. Dell / HP / Lenovo" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Model</label>
                            <input type="text" name="assets[0][model]" autocomplete="off" placeholder="e.g. Latitude 3420" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Serial Number</label>
                            <input type="text" name="assets[0][serial_number]" autocomplete="off" placeholder="e.g. SN-88291-XX" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                        <div class="relative">
                            <label class="block mb-1 text-slate-300">Assigned User</label>
                            <div class="relative">
                                <input type="text" id="assetUserSearch_0" name="assets[0][assigned_user]" placeholder="Type user name or select..." autocomplete="off"
                                       onfocus="showCustomDropdown('assetUserMenu_0')" onkeyup="filterCustomOptions('assetUserSearch_0', 'assetUserMenu_0')"
                                       class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <div id="assetUserMenu_0" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                <div onclick="selectCustomOption('assetUserSearch_0', 'assetUserMenu_0', 'Unassigned')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-400 italic text-xs transition">
                                    -- Unassigned --
                                </div>
                                @foreach($users as $usr)
                                    <div onclick="selectCustomOption('assetUserSearch_0', 'assetUserMenu_0', '{{ $usr->name }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition flex justify-between items-center">
                                        <span>{{ $usr->name }}</span>
                                        <span class="text-[9px] uppercase text-slate-400 font-bold bg-slate-950 px-1 py-0.5 rounded border border-slate-800">{{ $usr->role }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block mb-1 text-slate-300">Department / Location</label>
                            <div class="relative">
                                <input type="text" id="assetLocSearch_0" name="assets[0][department]" placeholder="Type location or select..." autocomplete="off"
                                       onfocus="showCustomDropdown('assetLocMenu_0')" onkeyup="filterCustomOptions('assetLocSearch_0', 'assetLocMenu_0')"
                                       class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <div id="assetLocMenu_0" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                @foreach($departments as $loc)
                                    <div onclick="selectCustomOption('assetLocSearch_0', 'assetLocMenu_0', '{{ $loc }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                        {{ $loc }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block mb-1 text-slate-300">Status *</label>
                            <div class="relative">
                                <input type="text" id="assetStatusSearch_0" name="assets[0][status]" required placeholder="Type status or select..." autocomplete="off"
                                       onfocus="showCustomDropdown('assetStatusMenu_0')" onkeyup="filterCustomOptions('assetStatusSearch_0', 'assetStatusMenu_0')"
                                       class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <div id="assetStatusMenu_0" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                @php $presetStatuses = ['Active', 'Maintenance', 'Retired', 'Spare']; @endphp
                                @foreach($presetStatuses as $st)
                                    <div onclick="selectCustomOption('assetStatusSearch_0', 'assetStatusMenu_0', '{{ $st }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                        {{ $st }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Purchase Date</label>
                            <input type="date" name="assets[0][purchase_date]" onclick="this.showPicker()" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 cursor-pointer text-xs">
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-300">Warranty Expiry</label>
                            <input type="date" name="assets[0][warranty_expiry]" onclick="this.showPicker()" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 cursor-pointer text-xs">
                        </div>
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block mb-1 text-slate-300">Remarks</label>
                            <textarea name="assets[0][remarks]" rows="2" placeholder="Additional details or notes about this device..." class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-slate-800">
                    <button type="button" onclick="addAssetRow()" class="bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-300 border border-indigo-500/40 text-xs font-bold py-1.5 px-3 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa-solid fa-plus"></i> Add Another Asset
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2 px-5 rounded-lg shadow transition flex items-center gap-1.5">
                        <i class="fa-solid fa-cubes"></i> Save All Assets
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 3: SOFTWARE MANAGEMENT (SM) -->
    <div id="tabSoftwareContent" class="hidden space-y-4">

        <!-- 1. WIDGET KALKULATOR PEMBAZIRAN KOS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="bg-slate-900 border border-slate-800 p-3.5 rounded-xl flex items-center justify-between shadow-lg">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Software Investment</p>
                    <h3 class="text-lg font-extrabold text-slate-100 mt-0.5">RM {{ number_format($totalSoftwareCost ?? 0, 2) }}</h3>
                </div>
                <div class="w-9 h-9 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                    <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-3.5 rounded-xl flex items-center justify-between shadow-lg">
                <div>
                    <p class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">Estimated Cost Leakage (Unassigned)</p>
                    <h3 class="text-lg font-extrabold text-rose-400 mt-0.5">RM {{ number_format($totalCostLeakage ?? 0, 2) }}</h3>
                </div>
                <div class="w-9 h-9 rounded-lg bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-3.5 rounded-xl flex items-center justify-between shadow-lg">
                <div>
                    <p class="text-[10px] font-bold text-amber-400 uppercase tracking-wider">Expiring Within 60 Days</p>
                    <h3 class="text-lg font-extrabold text-amber-400 mt-0.5">{{ $expiringSoonCount ?? 0 }} Licenses</h3>
                </div>
                <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                    <i class="fa-solid fa-clock text-sm"></i>
                </div>
            </div>
        </div>

        <!-- 2. JADUAL PAPARAN REKOD SOFTWARE + SIDE DRAWER CONTAINER -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

            <div id="swTableContainer" class="lg:col-span-12 transition-all duration-300 bg-slate-900 border border-slate-800 p-4 rounded-xl shadow-xl space-y-3">
                <div class="flex justify-between items-center border-b border-slate-800 pb-2.5">
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-indigo-400"></i> Registered Software Inventory
                    </h3>
                    <a href="{{ route('admin.software.export') }}" class="bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-300 border border-indigo-500/40 text-xs font-bold py-1.5 px-3 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa-solid fa-file-csv"></i> Export to CSV / Excel
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-950 text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-800">
                                <th class="p-2.5">Software Tag</th>
                                <th class="p-2.5">Software Name</th>
                                <th class="p-2.5">Category</th>
                                <th class="p-2.5">Vendor</th>
                                <th class="p-2.5">Seats (P/A/Avail)</th>
                                <th class="p-2.5">Utilization %</th>
                                <th class="p-2.5">Status</th>
                                <th class="p-2.5">Expiration Date</th>
                                <th class="p-2.5 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-slate-300 font-medium">
                            @forelse($softwareList as $sw)
                                @php
                                    $purchased = (int)$sw->total_seats;
                                    $assigned = (int)$sw->assigned_seats;
                                    $available = (int)$sw->available_seats;
                                    $utilization = $purchased > 0 ? round(($assigned / $purchased) * 100, 1) : 0;

                                    $isExpiringSoon = false;
                                    if ($sw->expiration_date) {
                                        $expDate = \Carbon\Carbon::parse($sw->expiration_date);
                                        $now = \Carbon\Carbon::now();
                                        if ($expDate->greaterThanOrEqualTo($now) && $now->diffInDays($expDate, false) <= 60) {
                                            $isExpiringSoon = true;
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-slate-800/40 cursor-pointer transition" onclick="openSoftwareDrawer({{ json_encode($sw) }})">
                                    <td class="p-2.5 font-mono text-indigo-400 font-bold">{{ $sw->software_tag }}</td>
                                    <td class="p-2.5 font-bold text-slate-100">{{ $sw->software_name }}</td>
                                    <td class="p-2.5 text-slate-400">{{ $sw->category }}</td>
                                    <td class="p-2.5">{{ $sw->vendor }}</td>
                                    <td class="p-2.5 font-mono text-xs">
                                        <span class="text-slate-200">{{ $purchased }}</span> / 
                                        <span class="text-indigo-400">{{ $assigned }}</span> / 
                                        <span class="text-emerald-400">{{ $available }}</span>
                                    </td>
                                    <td class="p-2.5 font-bold">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-12 bg-slate-950 rounded-full h-1.5 border border-slate-800 overflow-hidden">
                                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ min(100, $utilization) }}%"></div>
                                            </div>
                                            <span class="font-mono text-[11px] text-indigo-400">{{ $utilization }}%</span>
                                        </div>
                                    </td>
                                    <td class="p-2.5">
                                        <div class="flex items-center gap-1">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                                {{ $sw->status == 'Active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : '' }}
                                                {{ $sw->status == 'Expired' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/30' : '' }}
                                                {{ $sw->status == 'Pending Renewal' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/30' : '' }}
                                                {{ $sw->status == 'Suspended' ? 'bg-orange-500/10 text-orange-400 border border-orange-500/30' : '' }}
                                                {{ $sw->status == 'Retired' ? 'bg-slate-500/10 text-slate-400 border border-slate-500/30' : '' }}">
                                                {{ $sw->status }}
                                            </span>

                                            @if($isExpiringSoon && $sw->status == 'Active')
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 animate-pulse">
                                                    ⚠️ Expiring Soon
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-2.5 font-mono text-slate-300">{{ $sw->expiration_date ?? 'N/A' }}</td>
                                    <td class="p-2.5 text-center" onclick="event.stopPropagation()">
                                        <button onclick="openSoftwareDrawer({{ json_encode($sw) }})" class="bg-indigo-500/20 hover:bg-indigo-500/40 text-indigo-300 border border-indigo-500/50 text-xs font-semibold px-2.5 py-1 rounded transition inline-flex items-center gap-1 shadow-sm">
                                            <i class="fa-solid fa-eye text-indigo-400"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-4 text-center text-slate-500 italic">No software licenses registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Side Details Panel Drawer SAM -->
            <div id="swSideDrawer" class="hidden lg:col-span-4 bg-slate-900 border border-slate-800 rounded-xl p-4 space-y-4 sticky top-16 shadow-xl">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-cubes text-indigo-400 text-base"></i> Software Details
                    </h3>
                    <div class="flex items-center gap-2">
                        <span id="swDrawerStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold"></span>
                        <button onclick="closeSoftwareDrawer()" class="text-slate-400 hover:text-white text-lg font-bold transition">&times;</button>
                    </div>
                </div>

                <div class="bg-slate-950 p-3 rounded-lg border border-slate-800 space-y-1">
                    <h2 id="swDrawerName" class="text-base font-extrabold text-white tracking-wide"></h2>
                    <p id="swDrawerTag" class="text-xs font-mono font-bold text-indigo-400"></p>
                </div>

                <div class="text-xs space-y-2.5 text-slate-200 font-medium">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Category:</span>
                        <span id="swDrawerCategory" class="font-bold text-slate-100"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Vendor / Publisher:</span>
                        <span id="swDrawerVendor" class="font-bold text-slate-100"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Version / Edition:</span>
                        <span id="swDrawerVersion" class="font-bold text-slate-100"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">License Model:</span>
                        <span id="swDrawerModel" class="font-bold text-slate-100"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Seats (Purchased/Assigned):</span>
                        <span id="swDrawerSeats" class="font-mono text-slate-200 font-bold"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Product / License Key:</span>
                        <span id="swDrawerLicenseKey" class="font-mono text-indigo-300 font-bold"></span>
                    </div>

                    <div class="flex justify-between bg-slate-950 p-2.5 rounded-lg border border-slate-800 items-center my-1 shadow-inner">
                        <span class="text-slate-400 text-[11px] uppercase tracking-wider font-bold">Total Cost (RM):</span>
                        <span id="swDrawerCost" class="text-xs sm:text-sm font-extrabold text-indigo-400"></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Billing Cycle:</span>
                        <span id="swDrawerBilling" class="font-bold text-slate-200"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Invoice / PO Ref:</span>
                        <span id="swDrawerInvoice" class="font-mono text-slate-200 font-bold"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Expiration / Renewal:</span>
                        <span id="swDrawerExpiration" class="font-bold text-amber-300 font-mono"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                        <span class="text-slate-400">Department Scope:</span>
                        <span id="swDrawerDepartment" class="font-bold text-slate-200 text-xs"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Primary Assigned User:</span>
                        <span id="swDrawerUser" class="font-bold text-indigo-300 text-xs"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Admin Portal URL:</span>
                        <a id="swDrawerPortalUrl" href="#" target="_blank" class="text-indigo-400 hover:underline flex items-center gap-1 font-mono text-[11px]">
                            <span>Portal Link</span> <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                        </a>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-slate-400">Remarks / Notes:</span>
                        <span id="swDrawerRemarks" class="text-slate-300 italic text-right max-w-[60%]"></span>
                    </div>
                </div>

                <div class="space-y-2 pt-3 border-t border-slate-800">
                    <button type="button" onclick="openEditSoftwareModal()" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2 rounded-lg transition shadow flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Software License
                    </button>

                    <form id="swDrawerDeleteForm" action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this software license?');" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold py-2 rounded-lg transition shadow flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-trash"></i> Delete Software License
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- 3. FORM REGISTER SOFTWARE -->
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-xl shadow-xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-800 pb-2.5">
                <div>
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-cubes text-indigo-400"></i> Register New Software License
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Enterprise SAM tracking for licenses, subscriptions, expiration, and cost optimization.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="addSoftwareRow()" class="bg-indigo-600/30 hover:bg-indigo-600 text-indigo-200 hover:text-white border border-indigo-500/50 text-[11px] font-bold py-1 px-2.5 rounded-lg transition flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add More Entry (Expand)
                    </button>
                </div>
            </div>

            <form action="{{ route('admin.software.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off" class="space-y-4">
                @csrf

                <div id="softwareRowsContainer" class="space-y-6 divide-y divide-slate-800">
                    <div id="softwareRow_0" class="software-row-item pt-2 space-y-4">
                        <div class="flex justify-between items-center bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800">
                            <span class="font-bold text-indigo-400 text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-cubes"></i> Software Entry #1
                            </span>
                        </div>

                        <div class="bg-slate-950/60 p-3.5 rounded-lg border border-slate-800 space-y-3">
                            <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-800 pb-2">
                                <i class="fa-solid fa-circle-info"></i> 1. Software General Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-xs font-medium text-slate-200">
                                <div class="lg:col-span-2">
                                    <label class="block mb-1 text-slate-300">Software Name *</label>
                                    <input type="text" name="software[0][software_name]" required autocomplete="off" placeholder="e.g. Adobe Creative Cloud, Microsoft 365, AutoCAD" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Software Category *</label>
                                    <select name="software[0][category]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                        <option value="SaaS / Cloud Subscription">SaaS / Cloud Subscription</option>
                                        <option value="Desktop Application">Desktop Application</option>
                                        <option value="Development Tool">Development Tool</option>
                                        <option value="Security Software">Security Software</option>
                                        <option value="Productivity Suite">Productivity Suite</option>
                                        <option value="Database Software">Database Software</option>
                                        <option value="Operating System">Operating System</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Vendor / Publisher *</label>
                                    <input type="text" name="software[0][vendor]" required autocomplete="off" placeholder="e.g. Adobe, Microsoft, Autodesk, JetBrains" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Version / Edition</label>
                                    <input type="text" name="software[0][version]" autocomplete="off" placeholder="e.g. 2024 Enterprise v16.2" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-950/60 p-3.5 rounded-lg border border-slate-800 space-y-3">
                            <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-800 pb-2">
                                <i class="fa-solid fa-key"></i> 2. License Allocation & Usage Tracking
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-xs font-medium text-slate-200">
                                <div>
                                    <label class="block mb-1 text-slate-300">License Model *</label>
                                    <select name="software[0][license_type]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                        <option value="Per User / Named User">Per User / Named User</option>
                                        <option value="Per Device / Machine">Per Device / Machine</option>
                                        <option value="Concurrent / Floating">Concurrent / Floating</option>
                                        <option value="Enterprise Agreement (EA)">Enterprise Agreement (EA)</option>
                                        <option value="Open Source / Free">Open Source / Free</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Purchased Seats *</label>
                                    <input type="number" id="purchased_seats_0" name="software[0][total_seats]" min="1" required value="50" oninput="calculateSeats(0)" placeholder="e.g. 50" class="w-full bg-slate-950 text-slate-100 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Assigned Seats *</label>
                                    <input type="number" id="assigned_seats_0" name="software[0][assigned_seats]" min="0" required value="0" oninput="calculateSeats(0)" placeholder="e.g. 42" class="w-full bg-slate-950 text-slate-100 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Available Seats</label>
                                    <input type="number" id="available_seats_0" name="software[0][available_seats]" readonly value="50" class="w-full bg-slate-900/80 text-indigo-400 font-bold rounded-lg p-2 border border-slate-800 outline-none text-xs cursor-not-allowed">
                                </div>

                                <div class="lg:col-span-2">
                                    <label class="block mb-1 text-slate-300">License Key / Product Key</label>
                                    <input type="text" name="software[0][license_key]" autocomplete="off" placeholder="e.g. XXXX-XXXX-XXXX-XXXX" class="w-full bg-slate-950 text-indigo-300 font-mono rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                </div>

                                <div class="lg:col-span-2">
                                    <label class="block mb-1 text-slate-300">Admin Portal Management URL</label>
                                    <div class="relative flex items-center">
                                        <input type="url" id="admin_portal_0" name="software[0][admin_portal_url]" autocomplete="off" placeholder="https://admin.microsoft.com or https://admin.adobe.com" class="w-full bg-slate-950 text-slate-100 font-mono rounded-lg p-2 pr-9 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                        <button type="button" onclick="openPortalUrl('admin_portal_0')" title="Open Admin Portal in new tab" class="absolute right-2 text-slate-400 hover:text-indigo-400 transition">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-950/60 p-3.5 rounded-lg border border-slate-800 space-y-3">
                            <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-800 pb-2">
                                <i class="fa-solid fa-calculator"></i> 3. Subscription Cycle & Financial Tracking
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-xs font-medium text-slate-200">
                                <div>
                                    <label class="block mb-1 text-slate-300">Purchase Date *</label>
                                    <input type="date" name="software[0][purchase_date]" required onclick="this.showPicker()" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 cursor-pointer text-xs">
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Expiration / Renewal Date *</label>
                                    <input type="date" name="software[0][expiration_date]" required onclick="this.showPicker()" class="w-full bg-slate-950 text-amber-300 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 cursor-pointer text-xs">
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Billing Frequency *</label>
                                    <select name="software[0][billing_cycle]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                        <option value="Annually">Annually (Tahunan)</option>
                                        <option value="Monthly">Monthly (Bulanan)</option>
                                        <option value="Perpetual / One-Time">Perpetual / One-Time (Kekal)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Total License Cost (RM)</label>
                                    <input type="number" step="0.01" name="software[0][cost]" placeholder="e.g. 12000.00" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                </div>

                                <div class="lg:col-span-2">
                                    <label class="block mb-1 text-slate-300">PO Number / Invoice Reference</label>
                                    <input type="text" name="software[0][invoice_number]" autocomplete="off" placeholder="e.g. PO-2026-9921 / INV-ADOBE-8812" class="w-full bg-slate-950 text-slate-100 font-mono rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                </div>

                                <div class="lg:col-span-2 flex items-center pt-5">
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-300 font-medium">
                                        <input type="checkbox" name="software[0][enable_email_alert]" value="1" checked class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-indigo-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                                        <span>Enable Email Alert <span class="text-indigo-400 font-bold">(Notify 60 days before expiration)</span></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-950/60 p-3.5 rounded-lg border border-slate-800 space-y-3">
                            <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-800 pb-2">
                                <i class="fa-solid fa-shield-halved"></i> 4. Scope Assignment, Attachments & Status
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs font-medium text-slate-200">
                                <div class="relative">
                                    <label class="block mb-1 text-slate-300">Department Scope</label>
                                    <div class="relative">
                                        <input type="text" id="swDeptSearch_0" name="software[0][department]" placeholder="Type department or select..." autocomplete="off"
                                               onfocus="showCustomDropdown('swDeptMenu_0')" onkeyup="filterCustomOptions('swDeptSearch_0', 'swDeptMenu_0')"
                                               class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                        <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                                    </div>
                                    <div id="swDeptMenu_0" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                        <div onclick="selectCustomOption('swDeptSearch_0', 'swDeptMenu_0', 'All Departments')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                            All Departments (Semua Jabatan)
                                        </div>
                                        @foreach($departments as $loc)
                                            <div onclick="selectCustomOption('swDeptSearch_0', 'swDeptMenu_0', '{{ $loc }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                                {{ $loc }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="relative">
                                    <label class="block mb-1 text-slate-300">Assigned Primary User</label>
                                    <div class="relative">
                                        <input type="text" id="swUserSearch_0" name="software[0][assigned_user]" placeholder="Type user name or select..." autocomplete="off"
                                               onfocus="showCustomDropdown('swUserMenu_0')" onkeyup="filterCustomOptions('swUserSearch_0', 'swUserMenu_0')"
                                               class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                        <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                                    </div>
                                    <div id="swUserMenu_0" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                        <div onclick="selectCustomOption('swUserSearch_0', 'swUserMenu_0', '-')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-400 italic text-xs transition">
                                            - (Unassigned / Multi-User)
                                        </div>
                                        @foreach($users as $usr)
                                            <div onclick="selectCustomOption('swUserSearch_0', 'swUserMenu_0', '{{ $usr->name }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition flex justify-between items-center">
                                                <span>{{ $usr->name }}</span>
                                                <span class="text-[9px] uppercase text-slate-400 font-bold bg-slate-950 px-1 py-0.5 rounded border border-slate-800">{{ $usr->role }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-1 text-slate-300">Software Status *</label>
                                    <select name="software[0][status]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                        <option value="Active">🟢 Active</option>
                                        <option value="Expired">🔴 Expired</option>
                                        <option value="Pending Renewal">🟡 Pending Renewal</option>
                                        <option value="Suspended">🟠 Suspended</option>
                                        <option value="Retired">⚪ Retired</option>
                                    </select>
                                </div>

                                <div class="lg:col-span-3">
                                    <label class="block mb-1 text-slate-300">License Document / Attachment (PDF, Invoice, SLA, PO)</label>
                                    <input type="file" name="software[0][attachment]" accept=".pdf,.doc,.docx,.png,.jpg" class="w-full bg-slate-950 text-slate-300 rounded-lg p-1.5 border border-slate-700 outline-none focus:border-indigo-500 text-xs file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/20 file:text-indigo-300 hover:file:bg-indigo-600/40">
                                </div>

                                <div class="md:col-span-2 lg:col-span-3">
                                    <label class="block mb-1 text-slate-300">Remarks / SLA Notes</label>
                                    <textarea name="software[0][remarks]" rows="2" placeholder="Notes on license allocation, budget code, or SLA notes..." class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-slate-800">
                    <button type="button" onclick="addSoftwareRow()" class="bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-300 border border-indigo-500/40 text-xs font-bold py-1.5 px-3 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa-solid fa-plus"></i> Add Another Software License
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2 px-5 rounded-lg shadow transition flex items-center gap-1.5">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Save All Software Licenses
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<!-- MODAL EDIT SOFTWARE LICENSE -->
<div id="editSoftwareModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="flex justify-between items-center bg-slate-950 px-4.5 py-3 border-b border-slate-800">
            <h3 class="font-bold text-white text-sm flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-indigo-400"></i> Edit Software License Specifications
            </h3>
            <button onclick="closeEditSoftwareModal()" class="text-slate-400 hover:text-white text-xl font-bold transition">&times;</button>
        </div>

        <form id="editSoftwareModalForm" method="POST" enctype="multipart/form-data" class="p-4 overflow-y-auto space-y-3 text-xs font-medium text-slate-200">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1 text-slate-300">Software Name *</label>
                    <input type="text" id="edit_sw_name" name="software_name" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Category *</label>
                    <select id="edit_sw_category" name="category" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <option value="SaaS / Cloud Subscription">SaaS / Cloud Subscription</option>
                        <option value="Desktop Application">Desktop Application</option>
                        <option value="Development Tool">Development Tool</option>
                        <option value="Security Software">Security Software</option>
                        <option value="Productivity Suite">Productivity Suite</option>
                        <option value="Database Software">Database Software</option>
                        <option value="Operating System">Operating System</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Vendor / Publisher *</label>
                    <input type="text" id="edit_sw_vendor" name="vendor" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Version / Edition</label>
                    <input type="text" id="edit_sw_version" name="version" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">License Model *</label>
                    <select id="edit_sw_license_type" name="license_type" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <option value="Per User / Named User">Per User / Named User</option>
                        <option value="Per Device / Machine">Per Device / Machine</option>
                        <option value="Concurrent / Floating">Concurrent / Floating</option>
                        <option value="Enterprise Agreement (EA)">Enterprise Agreement (EA)</option>
                        <option value="Open Source / Free">Open Source / Free</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Purchased Seats *</label>
                    <input type="number" id="edit_sw_total_seats" name="total_seats" min="1" required class="w-full bg-slate-950 text-slate-100 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Assigned Seats *</label>
                    <input type="number" id="edit_sw_assigned_seats" name="assigned_seats" min="0" required class="w-full bg-slate-950 text-slate-100 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">License Key / Product Key</label>
                    <input type="text" id="edit_sw_license_key" name="license_key" class="w-full bg-slate-950 text-indigo-300 font-mono rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-1 text-slate-300">Admin Portal Management URL</label>
                    <input type="url" id="edit_sw_admin_portal_url" name="admin_portal_url" class="w-full bg-slate-950 text-slate-100 font-mono rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Purchase Date *</label>
                    <input type="date" id="edit_sw_purchase_date" name="purchase_date" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Expiration Date *</label>
                    <input type="date" id="edit_sw_expiration_date" name="expiration_date" required class="w-full bg-slate-950 text-amber-300 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Billing Frequency *</label>
                    <select id="edit_sw_billing_cycle" name="billing_cycle" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <option value="Annually">Annually (Tahunan)</option>
                        <option value="Monthly">Monthly (Bulanan)</option>
                        <option value="Perpetual / One-Time">Perpetual / One-Time (Kekal)</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Total License Cost (RM)</label>
                    <input type="number" step="0.01" id="edit_sw_cost" name="cost" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">PO Number / Invoice Reference</label>
                    <input type="text" id="edit_sw_invoice_number" name="invoice_number" class="w-full bg-slate-950 text-slate-100 font-mono rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Department Scope</label>
                    <input type="text" id="edit_sw_department" name="department" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Assigned Primary User</label>
                    <input type="text" id="edit_sw_assigned_user" name="assigned_user" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Status *</label>
                    <select id="edit_sw_status" name="status" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <option value="Active">🟢 Active</option>
                        <option value="Expired">🔴 Expired</option>
                        <option value="Pending Renewal">🟡 Pending Renewal</option>
                        <option value="Suspended">🟠 Suspended</option>
                        <option value="Retired">⚪ Retired</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-1 text-slate-300">Remarks / SLA Notes</label>
                    <textarea id="edit_sw_remarks" name="remarks" rows="2" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs"></textarea>
                </div>
            </div>

            <div class="bg-slate-950 px-4 py-2.5 border-t border-slate-800 flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeEditSoftwareModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold px-4 py-2 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2 rounded-lg shadow transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let userRowIndex = 1;
    let assetRowIndex = 1;
    let softwareRowIndex = 1;
    let currentSoftwareObject = null;

    function switchTab(tab) {
        const userBtn = document.getElementById('tabUserBtn');
        const assetBtn = document.getElementById('tabAssetBtn');
        const softwareBtn = document.getElementById('tabSoftwareBtn');

        const userContent = document.getElementById('tabUserContent');
        const assetContent = document.getElementById('tabAssetContent');
        const softwareContent = document.getElementById('tabSoftwareContent');

        const activeTabClass = 'pb-2.5 px-2 text-indigo-400 border-b-2 border-indigo-500 flex items-center gap-1.5 transition';
        const inactiveTabClass = 'pb-2.5 px-2 text-slate-400 hover:text-slate-200 border-b-2 border-transparent flex items-center gap-1.5 transition';

        if (tab === 'user') {
            userBtn.className = activeTabClass;
            assetBtn.className = inactiveTabClass;
            softwareBtn.className = inactiveTabClass;

            userContent.classList.remove('hidden');
            assetContent.classList.add('hidden');
            softwareContent.classList.add('hidden');
        } else if (tab === 'asset') {
            assetBtn.className = activeTabClass;
            userBtn.className = inactiveTabClass;
            softwareBtn.className = inactiveTabClass;

            assetContent.classList.remove('hidden');
            userContent.classList.add('hidden');
            softwareContent.classList.add('hidden');
        } else if (tab === 'software') {
            softwareBtn.className = activeTabClass;
            userBtn.className = inactiveTabClass;
            assetBtn.className = inactiveTabClass;

            softwareContent.classList.remove('hidden');
            userContent.classList.add('hidden');
            assetContent.classList.add('hidden');
        }
    }

    function showCustomDropdown(menuId) {
        document.querySelectorAll('[id*="Menu"]').forEach(m => m.classList.add('hidden'));
        const menu = document.getElementById(menuId);
        if (menu) menu.classList.remove('hidden');
    }

    function selectCustomOption(inputId, menuId, val) {
        document.getElementById(inputId).value = val;
        document.getElementById(menuId).classList.add('hidden');
    }

    function filterCustomOptions(inputId, menuId) {
        const filter = document.getElementById(inputId).value.toLowerCase();
        const menu = document.getElementById(menuId);
        menu.classList.remove('hidden');
        
        const options = menu.querySelectorAll('.custom-option');
        options.forEach(opt => {
            const txt = opt.innerText.toLowerCase();
            if (txt.includes(filter)) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
    }

    function calculateSeats(index) {
        const purchased = parseInt(document.getElementById(`purchased_seats_${index}`).value) || 0;
        const assigned = parseInt(document.getElementById(`assigned_seats_${index}`).value) || 0;
        const availableInput = document.getElementById(`available_seats_${index}`);
        
        const available = purchased - assigned;
        availableInput.value = available >= 0 ? available : 0;
    }

    function openPortalUrl(inputId) {
        const url = document.getElementById(inputId).value;
        if (url) {
            window.open(url, '_blank');
        } else {
            alert('Sila masukkan Admin Portal URL yang sah terlebih dahulu.');
        }
    }

    function openSoftwareDrawer(sw) {
        currentSoftwareObject = sw;
        const drawer = document.getElementById('swSideDrawer');
        const tableContainer = document.getElementById('swTableContainer');

        tableContainer.classList.remove('lg:col-span-12');
        tableContainer.classList.add('lg:col-span-8');
        drawer.classList.remove('hidden');

        document.getElementById('swDrawerName').innerText = sw.software_name || '-';
        document.getElementById('swDrawerTag').innerText = sw.software_tag || '-';
        document.getElementById('swDrawerCategory').innerText = sw.category || '-';
        document.getElementById('swDrawerVendor').innerText = sw.vendor || '-';
        document.getElementById('swDrawerVersion').innerText = sw.version || '-';
        document.getElementById('swDrawerModel').innerText = sw.license_type || '-';
        document.getElementById('swDrawerSeats').innerText = `${sw.total_seats || 0} Purchased / ${sw.assigned_seats || 0} Assigned / ${sw.available_seats || 0} Available`;
        document.getElementById('swDrawerLicenseKey').innerText = sw.license_key || 'N/A';
        document.getElementById('swDrawerCost').innerText = sw.cost ? `RM ${parseFloat(sw.cost).toFixed(2)}` : 'RM 0.00';
        document.getElementById('swDrawerBilling').innerText = sw.billing_cycle || '-';
        document.getElementById('swDrawerInvoice').innerText = sw.invoice_number || '-';
        document.getElementById('swDrawerExpiration').innerText = sw.expiration_date || 'N/A';
        document.getElementById('swDrawerDepartment').innerText = sw.department || 'All Departments';
        document.getElementById('swDrawerUser').innerText = sw.assigned_user || '-';
        document.getElementById('swDrawerRemarks').innerText = sw.remarks || '-';

        const portalBtn = document.getElementById('swDrawerPortalUrl');
        if (sw.admin_portal_url) {
            portalBtn.href = sw.admin_portal_url;
            portalBtn.classList.remove('hidden');
        } else {
            portalBtn.classList.add('hidden');
        }

        const statusBadge = document.getElementById('swDrawerStatusBadge');
        const currentStatus = sw.status || 'Active';
        statusBadge.innerText = currentStatus;
        statusBadge.className = `px-2.5 py-0.5 rounded-full text-xs font-bold ${
            currentStatus === 'Active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/50' : 
            (currentStatus === 'Expired' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/50' : 'bg-amber-500/20 text-amber-300 border border-amber-500/50')
        }`;

        let deleteForm = document.getElementById('swDrawerDeleteForm');
        if(deleteForm) {
            deleteForm.action = `{{ url('/admin/software') }}/${sw.id}`;
        }
    }

    function closeSoftwareDrawer() {
        const drawer = document.getElementById('swSideDrawer');
        const tableContainer = document.getElementById('swTableContainer');

        drawer.classList.add('hidden');
        tableContainer.classList.remove('lg:col-span-8');
        tableContainer.classList.add('lg:col-span-12');
    }

    function openEditSoftwareModal() {
        if (!currentSoftwareObject) return;
        const sw = currentSoftwareObject;

        document.getElementById('edit_sw_name').value = sw.software_name || '';
        document.getElementById('edit_sw_category').value = sw.category || '';
        document.getElementById('edit_sw_vendor').value = sw.vendor || '';
        document.getElementById('edit_sw_version').value = sw.version || '';
        document.getElementById('edit_sw_license_type').value = sw.license_type || '';
        document.getElementById('edit_sw_total_seats').value = sw.total_seats || 1;
        document.getElementById('edit_sw_assigned_seats').value = sw.assigned_seats || 0;
        document.getElementById('edit_sw_license_key').value = sw.license_key || '';
        document.getElementById('edit_sw_admin_portal_url').value = sw.admin_portal_url || '';
        document.getElementById('edit_sw_purchase_date').value = sw.purchase_date || '';
        document.getElementById('edit_sw_expiration_date').value = sw.expiration_date || '';
        document.getElementById('edit_sw_billing_cycle').value = sw.billing_cycle || 'Annually';
        document.getElementById('edit_sw_cost').value = sw.cost || 0.00;
        document.getElementById('edit_sw_invoice_number').value = sw.invoice_number || '';
        document.getElementById('edit_sw_department').value = sw.department || '';
        document.getElementById('edit_sw_assigned_user').value = sw.assigned_user || '';
        document.getElementById('edit_sw_status').value = sw.status || 'Active';
        document.getElementById('edit_sw_remarks').value = sw.remarks || '';

        const form = document.getElementById('editSoftwareModalForm');
        form.action = `{{ url('/admin/software') }}/${sw.id}`;

        document.getElementById('editSoftwareModal').classList.remove('hidden');
    }

    function closeEditSoftwareModal() {
        document.getElementById('editSoftwareModal').classList.add('hidden');
    }

    function addUserRow() {
        const container = document.getElementById('userRowsContainer');
        const currentCount = container.querySelectorAll('.user-row-item').length;
        const idx = userRowIndex++;
        const displayNum = currentCount + 1;

        const html = `
            <div id="userRow_${idx}" class="user-row-item pt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs font-medium text-slate-200 relative">
                <div class="md:col-span-2 lg:col-span-3 flex justify-between items-center bg-slate-950 px-3 py-1 rounded-lg border border-slate-800">
                    <span class="font-bold text-indigo-400 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-user"></i> User Entry #${displayNum}
                    </span>
                    <button type="button" onclick="removeRow('userRow_${idx}')" class="text-rose-400 hover:text-rose-300 text-[11px] font-bold transition flex items-center gap-1">
                        <i class="fa-solid fa-trash-can"></i> Remove Entry
                    </button>
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Full Name *</label>
                    <input type="text" name="users[${idx}][name]" required autocomplete="off" placeholder="e.g. Ahmad Razali" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Email Address *</label>
                    <input type="email" name="users[${idx}][email]" required autocomplete="off" placeholder="e.g. ahmad@company.com" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Phone Number</label>
                    <input type="text" name="users[${idx}][phone]" autocomplete="off" placeholder="e.g. 0123456789" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>

                <div class="relative">
                    <label class="block mb-1 text-slate-300">Department *</label>
                    <div class="relative">
                        <input type="text" id="userDeptInput_${idx}" name="users[${idx}][department]" required placeholder="Type or select department..." autocomplete="off" 
                               onfocus="showCustomDropdown('userDeptMenu_${idx}')" onkeyup="filterCustomOptions('userDeptInput_${idx}', 'userDeptMenu_${idx}')"
                               class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                    </div>
                    <div id="userDeptMenu_${idx}" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                        @foreach($departments as $dept)
                            <div onclick="selectCustomOption('userDeptInput_${idx}', 'userDeptMenu_${idx}', '{{ $dept }}')" 
                                 class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                {{ $dept }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Role *</label>
                    <select name="users[${idx}][role]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <option value="staff">Staff (End-User)</option>
                        <option value="technician">Technician</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Status *</label>
                    <select name="users[${idx}][status]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block mb-1 text-slate-300">Initial Password *</label>
                    <input type="password" name="users[${idx}][password]" required autocomplete="new-password" placeholder="Minimum 6 characters" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addAssetRow() {
        const container = document.getElementById('assetRowsContainer');
        const currentCount = container.querySelectorAll('.asset-row-item').length;
        const idx = assetRowIndex++;
        const displayNum = currentCount + 1;

        const html = `
            <div id="assetRow_${idx}" class="asset-row-item pt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs font-medium text-slate-200 relative">
                <div class="md:col-span-2 lg:col-span-3 flex justify-between items-center bg-slate-950 px-3 py-1 rounded-lg border border-slate-800">
                    <span class="font-bold text-indigo-400 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-laptop"></i> Asset Entry #${displayNum}
                    </span>
                    <button type="button" onclick="removeRow('assetRow_${idx}')" class="text-rose-400 hover:text-rose-300 text-[11px] font-bold transition flex items-center gap-1">
                        <i class="fa-solid fa-trash-can"></i> Remove Entry
                    </button>
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Asset Tag *</label>
                    <input type="text" name="assets[${idx}][asset_tag]" required autocomplete="off" placeholder="e.g. TKS_LAP_001" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Asset Name *</label>
                    <input type="text" name="assets[${idx}][name]" required autocomplete="off" placeholder="e.g. DELL Latitude 3420" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>

                <div class="relative">
                    <label class="block mb-1 text-slate-300">Category / Type *</label>
                    <div class="relative">
                        <input type="text" id="assetCatSearch_${idx}" name="assets[${idx}][category]" required placeholder="Type category or select..." autocomplete="off"
                               onfocus="showCustomDropdown('assetCatMenu_${idx}')" onkeyup="filterCustomOptions('assetCatSearch_${idx}', 'assetCatMenu_${idx}')"
                               class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                    </div>
                    <div id="assetCatMenu_${idx}" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                        @php $presetCategories = ['Laptop', 'Desktop', 'Printer', 'Monitor', 'Software', 'Network Device']; @endphp
                        @foreach($presetCategories as $cat)
                            <div onclick="selectCustomOption('assetCatSearch_${idx}', 'assetCatMenu_${idx}', '{{ $cat }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                {{ $cat }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Brand</label>
                    <input type="text" name="assets[${idx}][brand]" autocomplete="off" placeholder="e.g. Dell / HP / Lenovo" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Model</label>
                    <input type="text" name="assets[${idx}][model]" autocomplete="off" placeholder="e.g. Latitude 3420" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Serial Number</label>
                    <input type="text" name="assets[${idx}][serial_number]" autocomplete="off" placeholder="e.g. SN-88291-XX" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                </div>

                <div class="relative">
                    <label class="block mb-1 text-slate-300">Assigned User</label>
                    <div class="relative">
                        <input type="text" id="assetUserSearch_${idx}" name="assets[${idx}][assigned_user]" placeholder="Type user name or select..." autocomplete="off"
                               onfocus="showCustomDropdown('assetUserMenu_${idx}')" onkeyup="filterCustomOptions('assetUserSearch_${idx}', 'assetUserMenu_${idx}')"
                               class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                    </div>
                    <div id="assetUserMenu_${idx}" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                        <div onclick="selectCustomOption('assetUserSearch_${idx}', 'assetUserMenu_${idx}', 'Unassigned')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-400 italic text-xs transition">
                            -- Unassigned --
                        </div>
                        @foreach($users as $usr)
                            <div onclick="selectCustomOption('assetUserSearch_${idx}', 'assetUserMenu_${idx}', '{{ $usr->name }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition flex justify-between items-center">
                                <span>{{ $usr->name }}</span>
                                <span class="text-[9px] uppercase text-slate-400 font-bold bg-slate-950 px-1 py-0.5 rounded border border-slate-800">{{ $usr->role }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative">
                    <label class="block mb-1 text-slate-300">Department / Location</label>
                    <div class="relative">
                        <input type="text" id="assetLocSearch_${idx}" name="assets[${idx}][department]" placeholder="Type location or select..." autocomplete="off"
                               onfocus="showCustomDropdown('assetLocMenu_${idx}')" onkeyup="filterCustomOptions('assetLocSearch_${idx}', 'assetLocMenu_${idx}')"
                               class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                    </div>
                    <div id="assetLocMenu_${idx}" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                        @foreach($departments as $loc)
                            <div onclick="selectCustomOption('assetLocSearch_${idx}', 'assetLocMenu_${idx}', '{{ $loc }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                {{ $loc }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative">
                    <label class="block mb-1 text-slate-300">Status *</label>
                    <div class="relative">
                        <input type="text" id="assetStatusSearch_${idx}" name="assets[${idx}][status]" required placeholder="Type status or select..." autocomplete="off"
                               onfocus="showCustomDropdown('assetStatusMenu_${idx}')" onkeyup="filterCustomOptions('assetStatusSearch_${idx}', 'assetStatusMenu_${idx}')"
                               class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                    </div>
                    <div id="assetStatusMenu_${idx}" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                        @php $presetStatuses = ['Active', 'Maintenance', 'Retired', 'Spare']; @endphp
                        @foreach($presetStatuses as $st)
                            <div onclick="selectCustomOption('assetStatusSearch_${idx}', 'assetStatusMenu_${idx}', '{{ $st }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                {{ $st }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Purchase Date</label>
                    <input type="date" name="assets[${idx}][purchase_date]" onclick="this.showPicker()" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 cursor-pointer text-xs">
                </div>

                <div>
                    <label class="block mb-1 text-slate-300">Warranty Expiry</label>
                    <input type="date" name="assets[${idx}][warranty_expiry]" onclick="this.showPicker()" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 cursor-pointer text-xs">
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block mb-1 text-slate-300">Remarks</label>
                    <textarea name="assets[${idx}][remarks]" rows="2" placeholder="Additional details or notes about this device..." class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addSoftwareRow() {
        const container = document.getElementById('softwareRowsContainer');
        const currentCount = container.querySelectorAll('.software-row-item').length;
        const idx = softwareRowIndex++;
        const displayNum = currentCount + 1;

        const html = `
            <div id="softwareRow_${idx}" class="software-row-item pt-4 border-t border-slate-800 space-y-4">
                <div class="flex justify-between items-center bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800">
                    <span class="font-bold text-indigo-400 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-cubes"></i> Software Entry #${displayNum}
                    </span>
                    <button type="button" onclick="removeRow('softwareRow_${idx}')" class="text-rose-400 hover:text-rose-300 text-[11px] font-bold transition flex items-center gap-1">
                        <i class="fa-solid fa-trash-can"></i> Remove Entry
                    </button>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-lg border border-slate-800 space-y-3">
                    <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-800 pb-2">
                        <i class="fa-solid fa-circle-info"></i> 1. Software General Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-xs font-medium text-slate-200">
                        <div class="lg:col-span-2">
                            <label class="block mb-1 text-slate-300">Software Name *</label>
                            <input type="text" name="software[${idx}][software_name]" required autocomplete="off" placeholder="e.g. Adobe Creative Cloud, Microsoft 365, AutoCAD" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Software Category *</label>
                            <select name="software[${idx}][category]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <option value="SaaS / Cloud Subscription">SaaS / Cloud Subscription</option>
                                <option value="Desktop Application">Desktop Application</option>
                                <option value="Development Tool">Development Tool</option>
                                <option value="Security Software">Security Software</option>
                                <option value="Productivity Suite">Productivity Suite</option>
                                <option value="Database Software">Database Software</option>
                                <option value="Operating System">Operating System</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Vendor / Publisher *</label>
                            <input type="text" name="software[${idx}][vendor]" required autocomplete="off" placeholder="e.g. Adobe, Microsoft, Autodesk, JetBrains" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Version / Edition</label>
                            <input type="text" name="software[${idx}][version]" autocomplete="off" placeholder="e.g. 2024 Enterprise v16.2" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-lg border border-slate-800 space-y-3">
                    <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-800 pb-2">
                        <i class="fa-solid fa-key"></i> 2. License Allocation & Usage Tracking
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-xs font-medium text-slate-200">
                        <div>
                            <label class="block mb-1 text-slate-300">License Model *</label>
                            <select name="software[${idx}][license_type]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <option value="Per User / Named User">Per User / Named User</option>
                                <option value="Per Device / Machine">Per Device / Machine</option>
                                <option value="Concurrent / Floating">Concurrent / Floating</option>
                                <option value="Enterprise Agreement (EA)">Enterprise Agreement (EA)</option>
                                <option value="Open Source / Free">Open Source / Free</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Purchased Seats *</label>
                            <input type="number" id="purchased_seats_${idx}" name="software[${idx}][total_seats]" min="1" required value="50" oninput="calculateSeats(${idx})" placeholder="e.g. 50" class="w-full bg-slate-950 text-slate-100 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Assigned Seats *</label>
                            <input type="number" id="assigned_seats_${idx}" name="software[${idx}][assigned_seats]" min="0" required value="0" oninput="calculateSeats(${idx})" placeholder="e.g. 42" class="w-full bg-slate-950 text-slate-100 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Available Seats</label>
                            <input type="number" id="available_seats_${idx}" name="software[${idx}][available_seats]" readonly value="50" class="w-full bg-slate-900/80 text-indigo-400 font-bold rounded-lg p-2 border border-slate-800 outline-none text-xs cursor-not-allowed">
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block mb-1 text-slate-300">License Key / Product Key</label>
                            <input type="text" name="software[${idx}][license_key]" autocomplete="off" placeholder="e.g. XXXX-XXXX-XXXX-XXXX" class="w-full bg-slate-950 text-indigo-300 font-mono rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block mb-1 text-slate-300">Admin Portal Management URL</label>
                            <div class="relative flex items-center">
                                <input type="url" id="admin_portal_${idx}" name="software[${idx}][admin_portal_url]" autocomplete="off" placeholder="https://admin.microsoft.com or https://admin.adobe.com" class="w-full bg-slate-950 text-slate-100 font-mono rounded-lg p-2 pr-9 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <button type="button" onclick="openPortalUrl('admin_portal_${idx}')" title="Open Admin Portal in new tab" class="absolute right-2 text-slate-400 hover:text-indigo-400 transition">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-lg border border-slate-800 space-y-3">
                    <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-800 pb-2">
                        <i class="fa-solid fa-calculator"></i> 3. Subscription Cycle & Financial Tracking
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-xs font-medium text-slate-200">
                        <div>
                            <label class="block mb-1 text-slate-300">Purchase Date *</label>
                            <input type="date" name="software[${idx}][purchase_date]" required onclick="this.showPicker()" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 cursor-pointer text-xs">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Expiration / Renewal Date *</label>
                            <input type="date" name="software[${idx}][expiration_date]" required onclick="this.showPicker()" class="w-full bg-slate-950 text-amber-300 font-bold rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 cursor-pointer text-xs">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Billing Frequency *</label>
                            <select name="software[${idx}][billing_cycle]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <option value="Annually">Annually (Tahunan)</option>
                                <option value="Monthly">Monthly (Bulanan)</option>
                                <option value="Perpetual / One-Time">Perpetual / One-Time (Kekal)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Total License Cost (RM)</label>
                            <input type="number" step="0.01" name="software[${idx}][cost]" placeholder="e.g. 12000.00" class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block mb-1 text-slate-300">PO Number / Invoice Reference</label>
                            <input type="text" name="software[${idx}][invoice_number]" autocomplete="off" placeholder="e.g. PO-2026-9921 / INV-ADOBE-8812" class="w-full bg-slate-950 text-slate-100 font-mono rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                        </div>

                        <div class="lg:col-span-2 flex items-center pt-5">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-300 font-medium">
                                <input type="checkbox" name="software[${idx}][enable_email_alert]" value="1" checked class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-indigo-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                                <span>Enable Email Alert <span class="text-indigo-400 font-bold">(Notify 60 days before expiration)</span></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-lg border border-slate-800 space-y-3">
                    <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-800 pb-2">
                        <i class="fa-solid fa-shield-halved"></i> 4. Scope Assignment, Attachments & Status
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs font-medium text-slate-200">
                        <div class="relative">
                            <label class="block mb-1 text-slate-300">Department Scope</label>
                            <div class="relative">
                                <input type="text" id="swDeptSearch_${idx}" name="software[${idx}][department]" placeholder="Type department or select..." autocomplete="off"
                                       onfocus="showCustomDropdown('swDeptMenu_${idx}')" onkeyup="filterCustomOptions('swDeptSearch_${idx}', 'swDeptMenu_${idx}')"
                                       class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <div id="swDeptMenu_${idx}" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                <div onclick="selectCustomOption('swDeptSearch_${idx}', 'swDeptMenu_${idx}', 'All Departments')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                    All Departments (Semua Jabatan)
                                </div>
                                @foreach($departments as $loc)
                                    <div onclick="selectCustomOption('swDeptSearch_${idx}', 'swDeptMenu_${idx}', '{{ $loc }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition">
                                        {{ $loc }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block mb-1 text-slate-300">Assigned Primary User</label>
                            <div class="relative">
                                <input type="text" id="swUserSearch_${idx}" name="software[${idx}][assigned_user]" placeholder="Type user name or select..." autocomplete="off"
                                       onfocus="showCustomDropdown('swUserMenu_${idx}')" onkeyup="filterCustomOptions('swUserSearch_${idx}', 'swUserMenu_${idx}')"
                                       class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 pr-7 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-3 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <div id="swUserMenu_${idx}" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded-lg shadow-xl divide-y divide-slate-800">
                                <div onclick="selectCustomOption('swUserSearch_${idx}', 'swUserMenu_${idx}', '-')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-400 italic text-xs transition">
                                    - (Unassigned / Multi-User)
                                </div>
                                @foreach($users as $usr)
                                    <div onclick="selectCustomOption('swUserSearch_${idx}', 'swUserMenu_${idx}', '{{ $usr->name }}')" class="custom-option px-2.5 py-1.5 cursor-pointer hover:bg-indigo-600/30 hover:text-indigo-200 text-slate-300 text-xs transition flex justify-between items-center">
                                        <span>{{ $usr->name }}</span>
                                        <span class="text-[9px] uppercase text-slate-400 font-bold bg-slate-950 px-1 py-0.5 rounded border border-slate-800">{{ $usr->role }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-300">Software Status *</label>
                            <select name="software[${idx}][status]" required class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs">
                                <option value="Active">🟢 Active</option>
                                <option value="Expired">🔴 Expired</option>
                                <option value="Pending Renewal">🟡 Pending Renewal</option>
                                <option value="Suspended">🟠 Suspended</option>
                                <option value="Retired">⚪ Retired</option>
                            </select>
                        </div>

                        <div class="lg:col-span-3">
                            <label class="block mb-1 text-slate-300">License Document / Attachment (PDF, Invoice, SLA, PO)</label>
                            <input type="file" name="software[${idx}][attachment]" accept=".pdf,.doc,.docx,.png,.jpg" class="w-full bg-slate-950 text-slate-300 rounded-lg p-1.5 border border-slate-700 outline-none focus:border-indigo-500 text-xs file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/20 file:text-indigo-300 hover:file:bg-indigo-600/40">
                        </div>

                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block mb-1 text-slate-300">Remarks / SLA Notes</label>
                            <textarea name="software[${idx}][remarks]" rows="2" placeholder="Notes on license allocation, budget code, or SLA notes..." class="w-full bg-slate-950 text-slate-100 rounded-lg p-2 border border-slate-700 outline-none focus:border-indigo-500 text-xs"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function removeRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) row.remove();
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id*="Menu"]').forEach(m => m.classList.add('hidden'));
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab === 'software') {
            switchTab('software');
        } else if (tab === 'asset') {
            switchTab('asset');
        } else {
            switchTab('user');
        }
    });
</script>
@endsection