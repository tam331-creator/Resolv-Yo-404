@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto my-4 sm:my-6 px-2 sm:px-4">
    <!-- Main Dark Card -->
    <div class="bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-slate-700/80 text-slate-100">
        
        <!-- Header Card (Amber) -->
        <div class="bg-amber-600 text-white px-4 sm:px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 shadow-md">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-pen-to-square text-xl text-amber-100"></i>
                <h2 class="text-base sm:text-lg font-extrabold tracking-wide">Update Ticket #{{ $ticket->id }}</h2>
            </div>
            <span class="text-xs font-mono font-bold px-3 py-1 rounded-full bg-amber-950/80 text-amber-300 border border-amber-500/40 truncate max-w-full">
                {{ $ticket->title }}
            </span>
        </div>

        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 space-y-4 font-medium">
            @csrf
            @method('PUT')

            <!-- Issue Title (Staff, Technician & Admin Boleh Edit) -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Issue Title *</label>
                <input type="text" name="title" value="{{ old('title', $ticket->title) }}" required 
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-cyan-400 transition font-normal">
            </div>

            <!-- Select Device / Asset (Boleh Edit Oleh Semua Role Termasuk Staff) -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">
                    <i class="fa-solid fa-laptop text-cyan-400 mr-1"></i> Affected Device / Asset
                </label>
                <select name="asset_id" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-cyan-400 transition font-normal cursor-pointer">
                    <option value="">-- No Device Selected --</option>
                    @if(isset($assets) && count($assets) > 0)
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ old('asset_id', $ticket->asset_id) == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag ?? 'NO-TAG' }} - {{ $asset->name ?? $asset->asset_name }}{{ !empty($asset->brand) ? ' (' . $asset->brand . ')' : '' }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Status & Priority -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Ticket Status *</label>
                    <select name="status" required {{ auth()->user()->role === 'staff' ? 'disabled' : '' }}
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-cyan-400 transition font-semibold cursor-pointer {{ auth()->user()->role === 'staff' ? 'bg-slate-950 cursor-not-allowed opacity-60' : '' }}">
                        @if(auth()->user()->role === 'admin')
                            <option value="Open" {{ old('status', $ticket->status) == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed" {{ old('status', $ticket->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                        @elseif(auth()->user()->role === 'technician')
                            <!-- Technician hanya dibenarkan pilih Open atau In Progress sahaja -->
                            <option value="Open" {{ old('status', $ticket->status) == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        @else
                            <option value="{{ $ticket->status }}" selected>{{ $ticket->status }}</option>
                        @endif
                    </select>
                    @if(auth()->user()->role === 'staff')
                        <input type="hidden" name="status" value="{{ $ticket->status }}">
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Priority *</label>
                    <select name="priority" required {{ auth()->user()->role === 'staff' ? 'disabled' : '' }} 
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-cyan-400 transition font-normal cursor-pointer {{ auth()->user()->role === 'staff' ? 'bg-slate-950 cursor-not-allowed opacity-60' : '' }}">
                        <option value="Low" {{ old('priority', $ticket->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $ticket->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $ticket->priority) == 'High' ? 'selected' : '' }}>High (Critical)</option>
                    </select>
                    @if(auth()->user()->role === 'staff')
                        <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                    @endif
                </div>
            </div>

            <!-- Category & Technician -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Category *</label>
                    <select name="category" required {{ auth()->user()->role === 'staff' ? 'disabled' : '' }}
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-cyan-400 transition font-normal cursor-pointer {{ auth()->user()->role === 'staff' ? 'bg-slate-950 cursor-not-allowed opacity-60' : '' }}">
                        <option value="Hardware" {{ old('category', $ticket->category) == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                        <option value="Software" {{ old('category', $ticket->category) == 'Software' ? 'selected' : '' }}>Software</option>
                        <option value="Network" {{ old('category', $ticket->category) == 'Network' ? 'selected' : '' }}>Network</option>
                        <option value="Other" {{ old('category', $ticket->category) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @if(auth()->user()->role === 'staff')
                        <input type="hidden" name="category" value="{{ $ticket->category }}">
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">
                        <i class="fa-solid fa-user-gear text-cyan-400 mr-1"></i> Assign To (Technician)
                    </label>
                    <select name="assigned_to" {{ (auth()->user()->role === 'technician' || auth()->user()->role === 'staff') ? 'disabled' : '' }} 
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-cyan-400 transition font-normal cursor-pointer {{ (auth()->user()->role === 'technician' || auth()->user()->role === 'staff') ? 'bg-slate-950 cursor-not-allowed opacity-60' : '' }}">
                        <option value="">-- Select Technician --</option>
                        @if(isset($technicians))
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ old('assigned_to', $ticket->assigned_to) == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @if(auth()->user()->role === 'technician' || auth()->user()->role === 'staff')
                        <input type="hidden" name="assigned_to" value="{{ $ticket->assigned_to }}">
                    @endif
                </div>
            </div>

            <!-- BAHAGIAN LAMPIRAN / ATTACHMENT (DI ATAS ISSUE DESCRIPTION) -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">
                    <i class="fa-solid fa-paperclip text-cyan-400 mr-1"></i> Attached File / Screenshot
                </label>
                
                @if(!empty($ticket->attachment))
                    @php
                        $extension = pathinfo($ticket->attachment, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $fileUrl = asset('storage/' . $ticket->attachment);
                    @endphp

                    <div class="p-3.5 bg-slate-900/90 rounded-xl border border-slate-700/80 space-y-3">
                        @if($isImage)
                            <div class="relative mx-auto max-w-lg overflow-hidden rounded-xl border border-slate-700 shadow-sm bg-slate-950">
                                <a href="{{ $fileUrl }}" target="_blank" title="Click to view full image">
                                    <img src="{{ $fileUrl }}" alt="Attachment Preview" class="w-full h-auto max-h-72 sm:max-h-96 object-contain hover:scale-105 transition duration-200">
                                </a>
                            </div>
                        @endif
                        <div class="flex items-center justify-between text-xs text-slate-300 pt-2 border-t border-slate-800">
                            <span class="truncate font-semibold max-w-[200px] sm:max-w-[300px] text-slate-200">
                                <i class="fa-regular fa-file-image mr-1 text-cyan-400"></i> {{ basename($ticket->attachment) }}
                            </span>
                            <a href="{{ $fileUrl }}" target="_blank" download class="text-cyan-400 hover:text-cyan-300 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-download"></i> Download / View
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No attachment uploaded for this ticket.</p>
                @endif
            </div>

            <!-- Detailed Description (Staff, Technician & Admin Boleh Edit) -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Issue Description</label>
                <textarea name="description" rows="3" 
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-cyan-400 transition font-normal">{{ old('description', $ticket->description) }}</textarea>
            </div>

            <!-- Back & Save Buttons -->
            <div class="flex flex-col-reverse sm:flex-row justify-between items-center gap-3 pt-4 border-t border-slate-700/80">
                <a href="{{ route('tickets.index') }}" 
                    class="w-full sm:w-auto text-center bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-600 transition">
                    Cancel
                </a>
                <button type="submit" 
                    class="w-full sm:w-auto bg-amber-600 hover:bg-amber-500 text-slate-950 text-xs font-black px-6 py-2.5 rounded-xl shadow-lg shadow-amber-950 transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>

        </form>
    </div>
</div>
@endsection