<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\SoftwareLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function center(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Halaman ini khusus untuk Admin sahaja.');
        }

        $users = User::latest()->get();
        $assets = Asset::where('type', '!=', 'Software')->latest()->get();
        $departments = DB::table('departments')->pluck('name');

        // Ambil senarai perisian dari jadual dedicated software_licenses
        $softwareList = SoftwareLicense::latest()->get();

        // 5. Kalkulator Pembaziran Kos (Cost Leakage Calculator) & Statistik SAM
        $totalSoftwareCost = 0;
        $totalCostLeakage = 0;
        $expiringSoonCount = 0;

        foreach ($softwareList as $sw) {
            $purchased = (int)$sw->total_seats;
            $available = (int)$sw->available_seats;
            $cost = (float)$sw->cost;

            $totalSoftwareCost += $cost;

            // Kira kos terbuang bagi lesen tidak diagih
            if ($purchased > 0 && $cost > 0) {
                $costPerSeat = $cost / $purchased;
                $totalCostLeakage += ($costPerSeat * $available);
            }

            // Semak jika luput dalam tempoh 60 hari
            if ($sw->expiration_date) {
                $expiryDate = \Carbon\Carbon::parse($sw->expiration_date);
                $now = \Carbon\Carbon::now();
                
                if ($expiryDate->greaterThanOrEqualTo($now) && $now->diffInDays($expiryDate, false) <= 60) {
                    $expiringSoonCount++;
                }
            }
        }

        return view('admin.center', compact('users', 'assets', 'departments', 'softwareList', 'totalSoftwareCost', 'totalCostLeakage', 'expiringSoonCount'));
    }

    // US: Simpan Pengguna Baharu (Menyokong Single & Multi-Row Entry)
    public function storeUser(Request $request)
    {
        $usersData = $request->input('users');

        if (is_array($usersData) && count($usersData) > 0) {
            $request->validate([
                'users.*.name'       => 'required|string|max:255',
                'users.*.email'      => 'required|string|email|max:255|unique:users,email',
                'users.*.phone'      => 'nullable|string|max:20',
                'users.*.department' => 'required|string',
                'users.*.role'       => 'required|string|in:admin,technician,staff',
                'users.*.status'     => 'required|string|in:Active,Inactive',
                'users.*.password'   => 'required|string|min:6',
            ]);

            $count = 0;
            foreach ($usersData as $u) {
                User::create([
                    'name'       => $u['name'],
                    'email'      => $u['email'],
                    'phone'      => $u['phone'] ?? null,
                    'department' => $u['department'],
                    'role'       => strtolower($u['role']),
                    'status'     => $u['status'],
                    'password'   => Hash::make($u['password']),
                ]);
                $count++;
            }

            return redirect()->route('admin.center', ['tab' => 'user'])->with('success', "{$count} new user(s) registered successfully!");
        }

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'phone'      => 'nullable|string|max:20',
            'department' => 'required|string',
            'role'       => 'required|string|in:admin,technician,staff',
            'status'     => 'required|string|in:Active,Inactive',
            'password'   => 'required|string|min:6',
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'department' => $request->department,
            'role'       => strtolower($request->role),
            'status'     => $request->status,
            'password'   => Hash::make($request->password),
        ]);

        return redirect()->route('admin.center', ['tab' => 'user'])->with('success', 'New user registered successfully!');
    }

    // AS: Simpan Aset Baharu (Menyokong Single & Multi-Row Entry)
    public function storeAsset(Request $request)
    {
        $assetsData = $request->input('assets');

        if (is_array($assetsData) && count($assetsData) > 0) {
            $request->validate([
                'assets.*.asset_tag'       => 'required|string|unique:assets,asset_tag',
                'assets.*.name'            => 'required|string|max:255',
                'assets.*.category'        => 'required|string',
                'assets.*.brand'           => 'nullable|string',
                'assets.*.model'           => 'nullable|string',
                'assets.*.serial_number'   => 'nullable|string',
                'assets.*.assigned_user'   => 'nullable|string',
                'assets.*.department'      => 'nullable|string',
                'assets.*.status'          => 'required|string',
                'assets.*.purchase_date'   => 'nullable|date',
                'assets.*.warranty_expiry' => 'nullable|date',
                'assets.*.remarks'         => 'nullable|string',
            ]);

            $count = 0;
            foreach ($assetsData as $a) {
                Asset::create([
                    'asset_tag'       => $a['asset_tag'],
                    'name'            => $a['name'],
                    'type'            => $a['category'],
                    'category'        => $a['category'],
                    'brand'           => $a['brand'] ?? null,
                    'model'           => $a['model'] ?? null,
                    'serial_number'   => $a['serial_number'] ?? null,
                    'assigned_user'   => $a['assigned_user'] ?? null,
                    'location'        => $a['department'] ?? null,
                    'status'          => $a['status'],
                    'purchase_date'   => $a['purchase_date'] ?? null,
                    'warranty_expiry' => $a['warranty_expiry'] ?? null,
                    'remarks'         => $a['remarks'] ?? null,
                ]);
                $count++;
            }

            return redirect()->route('admin.center', ['tab' => 'asset'])->with('success', "{$count} new IT Asset(s) registered successfully!");
        }

        $validated = $request->validate([
            'asset_tag'       => 'required|string|unique:assets,asset_tag',
            'name'            => 'required|string|max:255',
            'category'        => 'required|string',
            'brand'           => 'nullable|string',
            'model'           => 'nullable|string',
            'serial_number'   => 'nullable|string',
            'assigned_user'   => 'nullable|string',
            'department'      => 'nullable|string',
            'status'          => 'required|string',
            'purchase_date'   => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'remarks'         => 'nullable|string',
        ]);

        Asset::create([
            'asset_tag'       => $validated['asset_tag'],
            'name'            => $validated['name'],
            'type'            => $validated['category'],
            'category'        => $validated['category'],
            'brand'           => $validated['brand'] ?? null,
            'model'           => $validated['model'] ?? null,
            'serial_number'   => $validated['serial_number'] ?? null,
            'assigned_user'   => $validated['assigned_user'] ?? null,
            'location'        => $validated['department'] ?? null,
            'status'          => $validated['status'],
            'purchase_date'   => $validated['purchase_date'] ?? null,
            'warranty_expiry' => $validated['warranty_expiry'] ?? null,
            'remarks'         => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('admin.center', ['tab' => 'asset'])->with('success', 'New IT Asset registered successfully!');
    }

    // SW: Simpan Perisian / Lisensi Baharu Ke Dalam Jadual software_licenses
    public function storeSoftware(Request $request)
    {
        $softwareData = $request->input('software');

        if (is_array($softwareData) && count($softwareData) > 0) {
            $request->validate([
                'software.*.software_name'    => 'required|string|max:255',
                'software.*.category'         => 'required|string',
                'software.*.vendor'           => 'required|string|max:255',
                'software.*.version'          => 'nullable|string|max:100',
                'software.*.license_type'     => 'required|string',
                'software.*.total_seats'      => 'required|integer|min:1',
                'software.*.assigned_seats'   => 'nullable|integer|min:0',
                'software.*.license_key'      => 'nullable|string',
                'software.*.admin_portal_url' => 'nullable|string',
                'software.*.purchase_date'    => 'required|date',
                'software.*.expiration_date'  => 'required|date',
                'software.*.billing_cycle'    => 'required|string',
                'software.*.cost'             => 'nullable|numeric',
                'software.*.invoice_number'   => 'nullable|string|max:100',
                'software.*.department'       => 'nullable|string',
                'software.*.assigned_user'    => 'nullable|string',
                'software.*.status'           => 'required|string',
                'software.*.attachment'       => 'nullable|file|mimes:pdf,doc,docx,png,jpg|max:10240',
                'software.*.remarks'          => 'nullable|string',
            ]);

            $count = 0;
            foreach ($softwareData as $index => $s) {
                $softwareTag = 'SW_' . strtoupper(substr(md5(uniqid()), 0, 6));

                $attachmentPath = null;
                if ($request->hasFile("software.{$index}.attachment")) {
                    try {
                        $attachmentPath = $request->file("software.{$index}.attachment")->store('software_attachments', 'public');
                    } catch (\Exception $e) {
                        $attachmentPath = null;
                    }
                }

                $totalSeats = (int)($s['total_seats'] ?? 1);
                $assignedSeats = (int)($s['assigned_seats'] ?? 0);
                $availableSeats = max(0, $totalSeats - $assignedSeats);

                SoftwareLicense::create([
                    'software_tag'      => $softwareTag,
                    'software_name'     => $s['software_name'],
                    'category'          => $s['category'],
                    'vendor'            => $s['vendor'],
                    'version'           => $s['version'] ?? 'Standard Edition',
                    'license_type'      => $s['license_type'],
                    'total_seats'       => $totalSeats,
                    'assigned_seats'    => $assignedSeats,
                    'available_seats'   => $availableSeats,
                    'license_key'       => $s['license_key'] ?? null,
                    'admin_portal_url'  => $s['admin_portal_url'] ?? null,
                    'purchase_date'     => $s['purchase_date'],
                    'expiration_date'   => $s['expiration_date'],
                    'billing_cycle'     => $s['billing_cycle'],
                    'cost'              => !empty($s['cost']) ? $s['cost'] : 0.00,
                    'invoice_number'    => $s['invoice_number'] ?? null,
                    'enable_email_alert'=> isset($s['enable_email_alert']) && ($s['enable_email_alert'] == '1' || $s['enable_email_alert'] == 1),
                    'department'        => $s['department'] ?? 'All Departments',
                    'assigned_user'     => $s['assigned_user'] ?? '-',
                    'status'            => $s['status'] ?? 'Active',
                    'attachment_path'   => $attachmentPath,
                    'remarks'           => $s['remarks'] ?? null,
                ]);
                $count++;
            }

            return redirect()->route('admin.center', ['tab' => 'software'])->with('success', "{$count} new Software License(s) registered successfully!");
        }

        return redirect()->route('admin.center', ['tab' => 'software'])->with('success', 'Software License registered successfully!');
    }

    // SW: Kemaskini Perisian / Lisensi Sedia Ada
    public function updateSoftware(Request $request, $id)
    {
        $software = SoftwareLicense::findOrFail($id);

        $validated = $request->validate([
            'software_name'    => 'required|string|max:255',
            'category'         => 'required|string',
            'vendor'           => 'required|string|max:255',
            'version'          => 'nullable|string|max:100',
            'license_type'     => 'required|string',
            'total_seats'      => 'required|integer|min:1',
            'assigned_seats'   => 'nullable|integer|min:0',
            'license_key'      => 'nullable|string',
            'admin_portal_url' => 'nullable|string',
            'purchase_date'    => 'required|date',
            'expiration_date'  => 'required|date',
            'billing_cycle'    => 'required|string',
            'cost'             => 'nullable|numeric',
            'invoice_number'   => 'nullable|string|max:100',
            'department'       => 'nullable|string',
            'assigned_user'    => 'nullable|string',
            'status'           => 'required|string',
            'remarks'          => 'nullable|string',
            'attachment'       => 'nullable|file|mimes:pdf,doc,docx,png,jpg|max:10240',
        ]);

        $totalSeats = (int)($validated['total_seats'] ?? 1);
        $assignedSeats = (int)($validated['assigned_seats'] ?? 0);
        $availableSeats = max(0, $totalSeats - $assignedSeats);

        if ($request->hasFile('attachment')) {
            if ($software->attachment_path) {
                Storage::disk('public')->delete($software->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('software_attachments', 'public');
        }

        $software->update([
            'software_name'     => $validated['software_name'],
            'category'          => $validated['category'],
            'vendor'            => $validated['vendor'],
            'version'           => $validated['version'] ?? 'Standard Edition',
            'license_type'      => $validated['license_type'],
            'total_seats'       => $totalSeats,
            'assigned_seats'    => $assignedSeats,
            'available_seats'   => $availableSeats,
            'license_key'       => $validated['license_key'] ?? null,
            'admin_portal_url'  => $validated['admin_portal_url'] ?? null,
            'purchase_date'     => $validated['purchase_date'],
            'expiration_date'   => $validated['expiration_date'],
            'billing_cycle'     => $validated['billing_cycle'],
            'cost'              => !empty($validated['cost']) ? $validated['cost'] : 0.00,
            'invoice_number'    => $validated['invoice_number'] ?? null,
            'enable_email_alert'=> $request->has('enable_email_alert'),
            'department'        => $validated['department'] ?? 'All Departments',
            'assigned_user'     => $validated['assigned_user'] ?? '-',
            'status'            => $validated['status'] ?? 'Active',
            'attachment_path'   => $validated['attachment_path'] ?? $software->attachment_path,
            'remarks'           => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('admin.center', ['tab' => 'software'])->with('success', 'Software License specifications updated successfully!');
    }

    // SW: Padam Perisian / Lisensi
    public function destroySoftware($id)
    {
        $software = SoftwareLicense::findOrFail($id);
        
        if ($software->attachment_path) {
            Storage::disk('public')->delete($software->attachment_path);
        }

        $software->delete();

        return redirect()->route('admin.center', ['tab' => 'software'])->with('success', 'Software License deleted successfully!');
    }

    // 4. Export Senarai Software ke CSV / Excel
    public function exportSoftwareCsv()
    {
        $softwareList = SoftwareLicense::latest()->get();

        $response = new StreamedResponse(function () use ($softwareList) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, [
                'Software Tag',
                'Software Name',
                'Category',
                'Vendor',
                'Version',
                'License Model',
                'Purchased Seats',
                'Assigned Seats',
                'Available Seats',
                'Total Cost (RM)',
                'Department Scope',
                'Assigned User',
                'Status',
                'Purchase Date',
                'Expiration Date',
                'Remarks'
            ]);

            foreach ($softwareList as $sw) {
                fputcsv($handle, [
                    $sw->software_tag,
                    $sw->software_name,
                    $sw->category,
                    $sw->vendor,
                    $sw->version,
                    $sw->license_type,
                    $sw->total_seats,
                    $sw->assigned_seats,
                    $sw->available_seats,
                    $sw->cost,
                    $sw->department,
                    $sw->assigned_user,
                    $sw->status,
                    $sw->purchase_date,
                    $sw->expiration_date,
                    $sw->remarks,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Software_License_Audit_Report_' . date('Y-m-d') . '.csv"');

        return $response;
    }
}