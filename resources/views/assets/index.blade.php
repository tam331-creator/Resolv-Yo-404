@extends('layouts.app')

@section('content')
<div class="max-w-[1380px] mx-auto space-y-4 px-4 py-3.5">

   <!-- Header Page ITAM -->
    <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800 flex justify-between items-center shadow-md">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-100 flex items-center gap-2.5">
                <i class="fa-solid fa-laptop-code text-cyan-400"></i> IT Asset Management
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">List of registered IT devices and asset inventory.</p>
        </div>
        
        <!-- LIMIT ACCESS: Hanya Admin/Bukan Technician boleh tambah device -->
        @if(auth()->check() && strtolower(auth()->user()->role) !== 'technician')
        <a href="{{ route('admin.center', ['tab' => 'asset']) }}" class="bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow transition flex items-center gap-1.5 whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Add New Device
        </a>
        @endif
    </div>

    <!-- Success Notification -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 text-xs px-4 py-2.5 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-sm"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 4 ITAM Summary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Total Assets -->
        <div class="bg-slate-900 border-2 border-sky-500/80 p-3.5 sm:p-4 rounded-xl shadow-md flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ASSETS</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-100 mt-0.5">{{ $totalAssets ?? count($assets) }}</h3>
                <p class="text-[11px] text-sky-400 mt-0.5 font-medium">All registered IT assets</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-base border border-sky-500/40">
                <i class="fa-solid fa-laptop"></i>
            </div>
        </div>

        <!-- Active Assets -->
        <div class="bg-slate-900 border-2 border-emerald-500/80 p-3.5 sm:p-4 rounded-xl shadow-md flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">ACTIVE ASSETS</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-emerald-400 mt-0.5">{{ $activeAssets ?? count($assets) }}</h3>
                <p class="text-[11px] text-emerald-400 mt-0.5 font-medium">In use</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-base border border-emerald-500/40">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Under Maintenance -->
        <div class="bg-slate-900 border-2 border-amber-500/80 p-3.5 sm:p-4 rounded-xl shadow-md flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">UNDER MAINTENANCE</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-amber-400 mt-0.5">{{ $maintenanceAssets ?? 0 }}</h3>
                <p class="text-[11px] text-amber-400 mt-0.5 font-medium">In repair / servicing</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-base border border-amber-500/40">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
        </div>

        <!-- Retired / Disposed -->
        <div class="bg-slate-900 border-2 border-rose-500/80 p-3.5 sm:p-4 rounded-xl shadow-md flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">RETIRED / DISPOSED</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-rose-400 mt-0.5">{{ $retiredAssets ?? 0 }}</h3>
                <p class="text-[11px] text-rose-400 mt-0.5 font-medium">Decommissioned devices</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-base border border-rose-500/40">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Control Bar dengan Browser Autocomplete Dimatikan & Custom Suggestion -->
    <form method="GET" action="{{ route('assets.index') }}" id="assetSearchForm" class="bg-slate-900 p-3 rounded-xl border border-slate-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
        
        <!-- Input Search dengan Browser Saved Info Dimatikan -->
        <div class="lg:col-span-3 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-[11px]"></i>
            <input type="text" 
                   id="customSearchInput" 
                   name="search" 
                   value="{{ request('search') }}" 
                   autocomplete="off" 
                   aria-autocomplete="none"
                   oninput="handleSearchSuggestions(this.value)" 
                   onfocus="handleSearchSuggestions(this.value)"
                   placeholder="Search tag, device, serial..." 
                   class="w-full bg-slate-950 text-slate-200 text-xs rounded-lg pl-8 pr-2.5 py-1.5 border border-slate-700 outline-none focus:border-cyan-500 transition">

            <!-- CUSTOM SUGGESTION DROPDOWN CONTAINER -->
            <div id="searchSuggestionsContainer" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-slate-900/95 border border-slate-700/90 rounded-xl shadow-2xl z-50 overflow-hidden backdrop-blur-xl max-h-60 overflow-y-auto">
                <div id="suggestionsList" class="divide-y divide-slate-800/80 text-xs">
                    <!-- Senarai cadangan aset automatik dimasukkan menerusi JS -->
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <select name="category" class="w-full bg-slate-950 text-slate-200 text-xs rounded-lg px-2.5 py-1.5 border border-slate-700 outline-none">
                <option value="All Categories">All Categories</option>
                <option value="Laptop" {{ request('category') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                <option value="Desktop" {{ request('category') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                <option value="Printer" {{ request('category') == 'Printer' ? 'selected' : '' }}>Printer</option>
                <option value="Monitor" {{ request('category') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
            </select>
        </div>

        <div class="lg:col-span-2">
            <select name="status" class="w-full bg-slate-950 text-slate-200 text-xs rounded-lg px-2.5 py-1.5 border border-slate-700 outline-none">
                <option value="All Status">All Status</option>
                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="Retired" {{ request('status') == 'Retired' ? 'selected' : '' }}>Retired</option>
            </select>
        </div>

        <div class="lg:col-span-2">
            <select name="department" class="w-full bg-slate-950 text-slate-200 text-xs rounded-lg px-2.5 py-1.5 border border-slate-700 outline-none">
                <option value="All Departments">All Departments</option>
                @if(isset($departments))
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="lg:col-span-2">
            <select name="assigned_user" class="w-full bg-slate-950 text-slate-200 text-xs rounded-lg px-2.5 py-1.5 border border-slate-700 outline-none">
                <option value="All Users">All Users</option>
                @if(isset($users))
                    @foreach($users as $usr)
                        <option value="{{ $usr }}" {{ request('assigned_user') == $usr ? 'selected' : '' }}>{{ $usr }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="lg:col-span-1">
            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold py-1.5 px-3 rounded-lg transition flex items-center justify-center gap-1">
                <i class="fa-solid fa-search"></i>
            </button>
        </div>
    </form>

    <!-- Main Container: Table & Side Details Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

        <!-- Asset List Table Container -->
        <div id="tableContainer" class="lg:col-span-12 transition-all duration-300 bg-slate-900 rounded-xl border border-slate-800 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-950 text-slate-300 uppercase text-[11px] font-bold border-b border-slate-800">
                        <tr>
                            <th class="px-4.5 py-3">Asset Tag</th>
                            <th class="px-4.5 py-3">Device Name / Model</th>
                            <th class="px-4.5 py-3">Type</th>
                            <th class="px-4.5 py-3">Serial Number</th>
                            <th class="px-4.5 py-3">Assigned To</th>
                            <th class="px-4.5 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 font-medium">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-slate-800/80 cursor-pointer transition" onclick="openAssetDrawer({{ json_encode($asset) }})">
                                <td class="px-4.5 py-3 font-bold text-cyan-400">
                                    {{ $asset->asset_tag }}
                                </td>
                                <td class="px-4.5 py-3 font-semibold text-slate-100">
                                    {{ $asset->name }}
                                </td>
                                <td class="px-4.5 py-3">
                                    <span class="bg-slate-950 text-slate-300 border border-slate-700 px-2 py-0.5 rounded text-[11px]">
                                        {{ $asset->type ?? $asset->category ?? 'Device' }}
                                    </span>
                                </td>
                                <td class="px-4.5 py-3 text-slate-400 font-mono text-xs">
                                    {{ $asset->serial_number ?? '--' }}
                                </td>
                                <td class="px-4.5 py-3 text-slate-300">
                                    <i class="fa-solid fa-user text-[11px] text-slate-500 mr-1"></i>
                                    {{ $asset->assigned_user ?? 'Unassigned' }}
                                </td>
                                <td class="px-4.5 py-3 text-center" onclick="event.stopPropagation()">
                                    <button onclick="openAssetDrawer({{ json_encode($asset) }})" class="bg-amber-500/20 hover:bg-amber-500/40 text-amber-300 border border-amber-500/50 text-xs font-semibold px-2.5 py-1 rounded transition inline-flex items-center gap-1 shadow-sm">
                                        <i class="fa-solid fa-eye text-amber-400"></i> View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4.5 py-8 text-center text-slate-500">
                                    <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-600 block"></i>
                                    No devices or assets registered yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Side Details Panel Drawer -->
        <div id="sideDrawer" class="hidden lg:col-span-4 bg-slate-900 border border-slate-800 rounded-xl p-4 space-y-4 sticky top-16 shadow-xl">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-laptop text-cyan-400 text-base"></i> Asset Details
                </h3>
                <div class="flex items-center gap-2">
                    <span id="drawerStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold"></span>
                    <button onclick="closeAssetDrawer()" class="text-slate-400 hover:text-white text-lg font-bold transition">&times;</button>
                </div>
            </div>

            <div class="bg-slate-950 p-3 rounded-lg border border-slate-800 space-y-1">
                <h2 id="drawerAssetName" class="text-base font-extrabold text-white tracking-wide"></h2>
                <p id="drawerAssetTag" class="text-xs font-mono font-bold text-cyan-400"></p>
            </div>

            <div class="text-xs space-y-2.5 text-slate-200 font-medium">
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Category / Type:</span>
                    <span id="drawerCategory" class="font-bold text-slate-100"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Brand:</span>
                    <span id="drawerBrand" class="font-bold text-slate-100"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Model:</span>
                    <span id="drawerModel" class="font-bold text-slate-100"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Serial Number:</span>
                    <span id="drawerSerial" class="font-mono text-slate-200 font-semibold"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Purchase Date:</span>
                    <span id="drawerPurchaseDate" class="font-bold text-slate-200"></span>
                </div>
                
                <div class="flex justify-between bg-slate-950 p-2.5 rounded-lg border border-slate-800 items-center my-1 shadow-inner">
                    <span class="text-slate-400 text-[11px] uppercase tracking-wider font-bold">Warranty Remaining:</span>
                    <span id="drawerWarrantyRemaining" class="text-xs sm:text-sm"></span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Warranty Status:</span>
                    <span id="drawerWarrantyBadge" class="px-2 py-0.5 rounded-full text-xs font-bold"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Warranty Expiry:</span>
                    <span id="drawerWarrantyExpiry" class="font-bold text-slate-200"></span>
                </div>

                <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                    <span class="text-slate-400">Assigned User:</span>
                    <span id="drawerAssignedUser" class="font-bold text-cyan-300 text-xs"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Department / Location:</span>
                    <span id="drawerLocation" class="font-bold text-slate-200"></span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-slate-400">Remarks:</span>
                    <span id="drawerRemarks" class="text-slate-300 italic text-right max-w-[60%]"></span>
                </div>
            </div>

            <div class="space-y-2 pt-3 border-t border-slate-800">
                
                <!-- LIMIT ACCESS: Hanya Admin/Bukan Technician boleh edit -->
                @if(auth()->check() && strtolower(auth()->user()->role) !== 'technician')
                <a id="drawerEditBtn" href="#" class="w-full bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold py-2 rounded-lg transition shadow flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Asset
                </a>
                @endif

                <button type="button" id="drawerViewHistoryBtn" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold py-2 rounded-lg transition shadow flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-clock-rotate-left"></i> View History
                </button>

                <!-- LIMIT ACCESS: Hanya Admin/Bukan Technician boleh buang aset -->
                @if(auth()->check() && strtolower(auth()->user()->role) !== 'technician')
                <form id="drawerDeleteForm" action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this asset?');" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold py-2 rounded-lg transition shadow flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-trash"></i> Delete Asset
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- POP-UP MODAL VIEW HISTORY -->
<div id="historyModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        
        <div class="flex justify-between items-center bg-slate-950 px-4.5 py-3 border-b border-slate-800">
            <div>
                <h3 class="font-bold text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-cyan-400"></i> Asset History
                </h3>
                <p id="modalAssetTagTitle" class="text-xs font-mono font-bold text-cyan-400 mt-0.5"></p>
            </div>
            <button onclick="closeHistoryModal()" class="text-slate-400 hover:text-white text-xl font-bold transition">&times;</button>
        </div>

        <div class="p-4 overflow-y-auto space-y-2.5 flex-1 text-xs text-slate-200" id="historyModalContent">
            <!-- Dynamic History Timeline Entries -->
        </div>

        <div class="bg-slate-950 px-4.5 py-2.5 border-t border-slate-800 flex justify-end">
            <button onclick="closeHistoryModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold px-4.5 py-2 rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    const allRegisteredAssets = @json($assets ?? []);
    let currentActiveAsset = null;

    // Custom Search Suggestions (Asset Tag Menonjol di Atas Baris Pertama)
    function handleSearchSuggestions(query) {
        const container = document.getElementById('searchSuggestionsContainer');
        const list = document.getElementById('suggestionsList');
        const cleanQuery = query.toLowerCase().trim();

        if (cleanQuery.length === 0) {
            container.classList.add('hidden');
            return;
        }

        const matches = allRegisteredAssets.filter(asset => {
            const tag = (asset.asset_tag || '').toLowerCase();
            const name = (asset.name || '').toLowerCase();
            const serial = (asset.serial_number || '').toLowerCase();
            return tag.includes(cleanQuery) || name.includes(cleanQuery) || serial.includes(cleanQuery);
        });

        if (matches.length > 0) {
            let html = '';
            matches.slice(0, 6).forEach(asset => {
                html += `
                    <div onclick="selectAssetSuggestion('${escapeJs(asset.asset_tag)}')" 
                         class="p-2.5 hover:bg-slate-800/90 cursor-pointer transition flex items-center justify-between group">
                        <div class="space-y-0.5">
                            <!-- Asset Tag Menonjol di Atas Baris Utama -->
                            <p class="font-extrabold text-cyan-400 text-sm font-mono group-hover:text-cyan-300 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-tag text-[10px] text-cyan-500"></i> ${escapeHtml(asset.asset_tag)}
                            </p>
                            <!-- Nama Perangkat & Serial Number Redup di Bawah -->
                            <p class="text-xs text-slate-400 group-hover:text-slate-200 transition">
                                ${escapeHtml(asset.name)} ${asset.serial_number ? '<span class="text-slate-500 font-mono text-[10px] ml-1">• SN: ' + escapeHtml(asset.serial_number) + '</span>' : ''}
                            </p>
                        </div>
                        <span class="text-[10px] bg-slate-950 text-slate-400 border border-slate-700 px-2 py-0.5 rounded font-mono group-hover:border-cyan-500/50 group-hover:text-cyan-300 transition">
                            ${escapeHtml(asset.type || asset.category || 'Asset')}
                        </span>
                    </div>
                `;
            });
            list.innerHTML = html;
            container.classList.remove('hidden');
        } else {
            list.innerHTML = `<div class="p-3 text-slate-500 italic text-center">No matching devices found</div>`;
            container.classList.remove('hidden');
        }
    }

    function selectAssetSuggestion(tag) {
        const input = document.getElementById('customSearchInput');
        input.value = tag;
        document.getElementById('searchSuggestionsContainer').classList.add('hidden');
        document.getElementById('assetSearchForm').submit();
    }

    function escapeHtml(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    
    function escapeJs(str) {
        return String(str || '').replace(/'/g, "\\'");
    }

    document.addEventListener('click', function(e) {
        const container = document.getElementById('searchSuggestionsContainer');
        const input = document.getElementById('customSearchInput');
        if (container && !container.contains(e.target) && !input.contains(e.target)) {
            container.classList.add('hidden');
        }
    });

    function openAssetDrawer(asset) {
        currentActiveAsset = asset;
        const drawer = document.getElementById('sideDrawer');
        const tableContainer = document.getElementById('tableContainer');

        tableContainer.classList.remove('lg:col-span-12');
        tableContainer.classList.add('lg:col-span-8');
        drawer.classList.remove('hidden');

        document.getElementById('drawerAssetName').innerText = asset.name || '-';
        document.getElementById('drawerAssetTag').innerText = asset.asset_tag || '-';
        document.getElementById('drawerCategory').innerText = asset.type || asset.category || '-';
        document.getElementById('drawerBrand').innerText = asset.brand || '-';
        document.getElementById('drawerModel').innerText = asset.model || '-';
        document.getElementById('drawerSerial').innerText = asset.serial_number || '-';
        document.getElementById('drawerPurchaseDate').innerText = asset.purchase_date || '-';
        
        const remainingElem = document.getElementById('drawerWarrantyRemaining');
        remainingElem.innerText = asset.warranty_remaining || 'N/A';
        remainingElem.className = asset.warranty_color_class || 'text-slate-400 font-bold';

        document.getElementById('drawerWarrantyExpiry').innerText = asset.warranty_expiry || '-';
        document.getElementById('drawerAssignedUser').innerText = asset.assigned_user || 'Unassigned';
        document.getElementById('drawerLocation').innerText = asset.location || '-';
        document.getElementById('drawerRemarks').innerText = asset.remarks || '-';

        const statusBadge = document.getElementById('drawerStatusBadge');
        const currentStatus = asset.status || 'Active';
        statusBadge.innerText = currentStatus;
        statusBadge.className = `px-2.5 py-0.5 rounded-full text-xs font-bold ${
            currentStatus === 'Active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/50' : 
            (currentStatus === 'Maintenance' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/50' : 'bg-rose-500/20 text-rose-300 border border-rose-500/50')
        }`;

        const warrantyBadge = document.getElementById('drawerWarrantyBadge');
        if (asset.is_warranty_active) {
            warrantyBadge.innerText = 'Active';
            warrantyBadge.className = 'px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/50';
        } else {
            warrantyBadge.innerText = 'Expired / None';
            warrantyBadge.className = 'px-2 py-0.5 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/50';
        }

        // Semakan if button wujud (kerana disembunyikan untuk Technician)
        let editBtn = document.getElementById('drawerEditBtn');
        if(editBtn) {
            editBtn.href = `{{ url('/assets') }}/${asset.id}/edit`;
        }

        let deleteForm = document.getElementById('drawerDeleteForm');
        if(deleteForm) {
            deleteForm.action = `{{ url('/assets') }}/${asset.id}`;
        }
        
        document.getElementById('drawerViewHistoryBtn').onclick = function() {
            openHistoryModal(asset);
        };
    }

    function closeAssetDrawer() {
        const drawer = document.getElementById('sideDrawer');
        const tableContainer = document.getElementById('tableContainer');

        drawer.classList.add('hidden');
        tableContainer.classList.remove('lg:col-span-8');
        tableContainer.classList.add('lg:col-span-12');
    }

    function openHistoryModal(asset) {
        const modal = document.getElementById('historyModal');
        const titleElem = document.getElementById('modalAssetTagTitle');
        const container = document.getElementById('historyModalContent');

        titleElem.innerText = `Asset History - ${asset.asset_tag}`;

        const logs = asset.history_logs || [];

        if (logs.length > 0) {
            let html = '<div class="space-y-2.5">';
            logs.forEach(log => {
                let titleContent = log.title;

                if (log.type === 'ticket' && log.ticket_id) {
                    let ticketUrl = `{{ url('/tickets') }}/${log.ticket_id}`;
                    titleContent = `<a href="${ticketUrl}" target="_blank" class="text-amber-400 hover:text-amber-300 underline underline-offset-2 transition flex items-center gap-1 inline-flex" title="Click to view full ticket details">
                                        ${log.title} <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                    </a>`;
                }

                html += `
                    <div class="bg-slate-950/80 p-3 rounded-lg border border-slate-800 space-y-1 transition hover:border-slate-700">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-mono font-bold text-xs">${log.date}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border ${log.badge_class}">
                                ${log.badge_text}
                            </span>
                        </div>
                        <div class="text-slate-100 font-bold text-xs flex items-center gap-1.5">
                            <span>${log.icon}</span> ${titleContent}
                        </div>
                        <div class="text-slate-300 font-mono text-xs pl-5">${log.description}</div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        } else {
            container.innerHTML = `<p class="text-xs text-slate-500 italic font-mono">No history logs or tickets recorded for this asset.</p>`;
        }

        modal.classList.remove('hidden');
    }

    function closeHistoryModal() {
        document.getElementById('historyModal').classList.add('hidden');
    }
</script>
@endsection