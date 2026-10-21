@extends('layouts.app')

@section('content')
<!-- Tom Select CSS for Searchable Dropdown -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<!-- LEBARKAN KOTAK BORANG: max-w-6xl -->
<div class="max-w-6xl mx-auto px-3 py-4 sm:py-6 space-y-5">

    <!-- Header Page & SLA Info Banner -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-2xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center text-indigo-400 text-2xl shadow-inner shrink-0">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black uppercase tracking-wider bg-indigo-950 text-indigo-400 border border-indigo-800/80 px-2.5 py-0.5 rounded-full">SERVICE DESK INCIDENT</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight mt-0.5">
                    Submit New Incident Ticket
                </h1>
            </div>
        </div>
        <a href="{{ route('tickets.index') }}" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-700 text-slate-100 border border-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 shrink-0">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Main Enterprise Form Card -->
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 overflow-hidden text-slate-100">
        
        <!-- Header Card Bar -->
        <div class="bg-slate-800/80 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-bold text-slate-100">
                <i class="fa-solid fa-pen-to-square text-indigo-400"></i>
                <span>Incident Specifications & Context</span>
            </div>
            <span class="text-[11px] text-slate-300 font-mono"><span class="text-rose-400">*</span> Required fields</span>
        </div>

        <!-- Form Body -->
        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6 text-xs sm:text-sm font-medium">
            @csrf

            <!-- Issue Title -->
            <div class="space-y-1.5">
                <label class="block text-slate-200 font-bold flex items-center justify-between">
                    <span>Issue Title <span class="text-rose-400">*</span></span>
                    <span class="text-[10px] text-slate-400 font-normal">Enter a short title for your issue</span>
                </label>
                <div class="relative">
                    <input type="text" name="title" required placeholder="Example: Printer paper jam on Level 3 / Wi-Fi disconnected / Cannot access work system" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                </div>
            </div>

            <!-- Select Device / Asset -->
            <div class="space-y-1.5 bg-slate-950/80 p-4 rounded-xl border border-slate-800">
                <label class="block text-slate-200 font-bold flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-laptop text-indigo-400"></i> Select Faulty Device / Asset <span class="text-slate-400 font-normal text-[11px]">(Optional)</span>
                    </span>
                    <span class="text-[10px] text-indigo-400 font-mono">ITAM Link Active</span>
                </label>
                <select id="asset_id" name="asset_id" class="w-full font-normal">
                    <option value="">Type to search device name, tag or serial number...</option>
                    @if(isset($assets) && count($assets) > 0)
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag ?? 'NO-TAG' }} - {{ $asset->name ?? $asset->asset_name }}{{ !empty($asset->brand) ? ' (' .$asset->brand . ')' : '' }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>-- No devices registered in system --</option>
                    @endif
                </select>
            </div>

            <!-- Category & Priority Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="block text-slate-200 font-bold">Category <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <select id="category_select" name="category" required class="w-full font-normal">
                            <option value="">-- Select Category --</option>
                            <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }} data-desc="Devices used for work or document printing">🖥️ Hardware</option>
                            <option value="Software" {{ old('category') == 'Software' ? 'selected' : '' }} data-desc="Applications or systems used for daily work">⚙️ Software</option>
                            <option value="Network" {{ old('category') == 'Network' ? 'selected' : '' }} data-desc="Wi-Fi, VPN, or network connection issues">🌐 Network</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }} data-desc="Issues that do not fit into the categories above">📦 Other</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-slate-200 font-bold">Urgency & Priority <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-triangle-exclamation absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        <select name="priority" required class="w-full bg-slate-950 border border-slate-700 rounded-xl pl-9 pr-4 py-2.5 text-slate-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition font-normal cursor-pointer">
                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }} class="bg-slate-900">🔵 Low - Work can continue normally</option>
                            <option value="Medium" {{ old('priority', 'Medium') == 'Medium' ? 'selected' : '' }} class="bg-slate-900">🟡 Medium - Work is affected but can continue</option>
                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }} class="bg-slate-900">🔴 High - Unable to work due to the issue</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Detailed Description -->
            <div class="space-y-1.5">
                <label class="block text-slate-200 font-bold flex items-center justify-between">
                    <span>Detailed Problem Description</span>
                </label>
                <textarea name="description" rows="4" placeholder="Tell us what happened / any error message shown" 
                          class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 font-normal resize-y transition">{{ old('description') }}</textarea>
            </div>

            <!-- Attachment Box -->
            <div class="space-y-2">
                <label class="block text-slate-200 font-bold flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-paperclip text-indigo-400"></i> Attachment / Error Screenshot 
                    </span>
                    <span class="text-slate-400 font-normal text-[10px]">PNG, JPG, JPEG (Max 5MB)</span>
                </label>
                
                <div class="relative">
                    <input type="file" name="attachment" id="attachment" accept="image/*" class="hidden" onchange="previewSelectedImage(this)">
                    
                    <label for="attachment" id="dropzone-area"
                           class="w-full bg-slate-950 border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-xl p-5 flex flex-col items-center justify-center cursor-pointer transition-all duration-200 group text-center">
                        
                        <div id="upload-prompt" class="space-y-2 flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 text-indigo-400 flex items-center justify-center text-base group-hover:scale-110 transition">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-200 group-hover:text-indigo-400 transition">Click or drag image file here to attach</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Attach clear screenshots of error popups for faster technical diagnostics</p>
                            </div>
                        </div>

                        <div id="image-preview-container" class="hidden flex flex-col items-center gap-2 pt-1">
                            <img id="image-preview-element" src="#" alt="Preview" class="max-h-36 rounded-lg border border-slate-600 shadow-md object-contain">
                            <p id="file-name-display" class="text-xs font-mono font-bold text-indigo-400"></p>
                            <span class="text-[10px] bg-slate-800 border border-slate-600 text-slate-200 px-2.5 py-0.5 rounded-full hover:bg-rose-900/50 hover:text-rose-300 transition" onclick="clearAttachment(event)">
                                <i class="fa-solid fa-trash mr-1"></i> Change / Remove File
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons (Primary = Indigo) -->
            <div class="flex justify-between items-center gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('tickets.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-bold transition text-xs sm:text-sm">
                    Cancel
                </a>
                <button type="submit" class="px-7 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-black text-xs sm:text-sm shadow-lg transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                    <i class="fa-solid fa-paper-plane"></i> Submit Ticket
                </button>
            </div>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new TomSelect("#asset_id", {
            create: false,
            placeholder: "Type to search device name, tag or serial number...",
            allowEmptyOption: true,
            maxOptions: null
        });

        new TomSelect("#category_select", {
            create: false,
            placeholder: "-- Select Category --",
            allowEmptyOption: true,
            render: {
                option: function(data, escape) {
                    var desc = data.desc ? '<span class="text-slate-400 text-[11px] font-normal ml-1">: ' + escape(data.desc) + '</span>' : '';
                    return '<div><span class="font-bold text-slate-100">' + escape(data.text) + '</span>' + desc + '</div>';
                },
                item: function(data, escape) {
                    var desc = data.desc ? '<span class="text-slate-400 text-[11px] font-normal ml-1">: ' + escape(data.desc) + '</span>' : '';
                    return '<div><span class="font-bold text-slate-100">' + escape(data.text) + '</span>' + desc + '</div>';
                }
            }
        });
    });

    function previewSelectedImage(input) {
        const prompt = document.getElementById('upload-prompt');
        const container = document.getElementById('image-preview-container');
        const img = document.getElementById('image-preview-element');
        const fileName = document.getElementById('file-name-display');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                img.src = e.target.result;
                fileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                prompt.classList.add('hidden');
                container.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        }
    }

    function clearAttachment(e) {
        e.preventDefault();
        e.stopPropagation();
        const input = document.getElementById('attachment');
        const prompt = document.getElementById('upload-prompt');
        const container = document.getElementById('image-preview-container');
        
        input.value = '';
        prompt.classList.remove('hidden');
        container.classList.add('hidden');
    }
</script>

<style>
    .ts-control {
        background-color: #020617 !important;
        border-color: #334155 !important;
        border-radius: 0.75rem !important;
        padding: 0.625rem 1rem !important;
        font-size: 0.875rem !important;
        color: #f8fafc !important;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        font-size: 0.875rem !important;
        background-color: #0f172a !important;
        border-color: #334155 !important;
        color: #e2e8f0 !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
    }
    .ts-dropdown .option {
        color: #cbd5e1 !important;
        padding: 8px 12px !important;
    }
    .ts-dropdown .option.active, .ts-dropdown .option:hover {
        background-color: #1e293b !important;
        color: #818cf8 !important;
    }
</style>
@endsection