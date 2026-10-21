@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-blue-400"></i> Edit Asset: {{ $asset->asset_tag }}
            </h1>
            <p class="text-xs text-slate-400 mt-1">Update specifications and details for {{ $asset->name }}</p>
        </div>
        <a href="{{ route('assets.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 text-xs font-semibold px-4 py-2 rounded-lg transition">
            Back to List
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl shadow-2xl">
        <form action="{{ route('assets.update', $asset->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-medium text-slate-300">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 text-slate-400">Asset Tag *</label>
                <input type="text" name="asset_tag" value="{{ old('asset_tag', $asset->asset_tag) }}" required class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-400">Asset Name *</label>
                <input type="text" name="name" value="{{ old('name', $asset->name) }}" required class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-400">Category / Type *</label>
                <input type="text" name="category" value="{{ old('category', $asset->category ?? $asset->type) }}" required class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-400">Brand</label>
                <input type="text" name="brand" value="{{ old('brand', $asset->brand) }}" class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-400">Model</label>
                <input type="text" name="model" value="{{ old('model', $asset->model) }}" class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-400">Serial Number</label>
                <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-400">Assigned User</label>
                <input type="text" name="assigned_user" value="{{ old('assigned_user', $asset->assigned_user) }}" class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-400">Location</label>
                <input type="text" name="location" value="{{ old('location', $asset->location) }}" class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-400">Status *</label>
                <select name="status" required class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">
                    <option value="Active" {{ old('status', $asset->status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Maintenance" {{ old('status', $asset->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="Retired" {{ old('status', $asset->status) == 'Retired' ? 'selected' : '' }}>Retired</option>
                </select>
            </div>

            <!-- Purchase Date: Kalendar Terus Keluar Bila Klik -->
            <div>
                <label class="block mb-1 text-slate-400">Purchase Date</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date) }}" onclick="this.showPicker()" class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500 cursor-pointer">
            </div>

            <!-- Warranty Expiry: Kalendar Terus Keluar Bila Klik -->
            <div>
                <label class="block mb-1 text-slate-400">Warranty Expiry</label>
                <input type="date" name="warranty_expiry" value="{{ old('warranty_expiry', $asset->warranty_expiry) }}" onclick="this.showPicker()" class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500 cursor-pointer">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-1 text-slate-400">Remarks</label>
                <textarea name="remarks" rows="2" class="w-full bg-slate-950 text-slate-200 rounded-lg p-2.5 border border-slate-700 outline-none focus:border-blue-500">{{ old('remarks', $asset->remarks) }}</textarea>
            </div>

            <div class="md:col-span-2 pt-2 flex justify-end gap-2">
                <a href="{{ route('assets.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-2 px-4 rounded-lg transition">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-5 rounded-lg transition">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection