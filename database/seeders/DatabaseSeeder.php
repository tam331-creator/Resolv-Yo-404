<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Data Perangkat / Aset IT Dummy
        $asset1 = Asset::create([
            'asset_tag'     => 'AST-2026-001',
            'name'          => 'Laptop Dell Latitude 5420',
            'type'          => 'Laptop',
            'serial_number' => 'SN-DL-88291',
            'assigned_user' => 'Ahmad Faiz (HR)',
        ]);

        $asset2 = Asset::create([
            'asset_tag'     => 'AST-2026-002',
            'name'          => 'Printer HP LaserJet M404n',
            'type'          => 'Printer',
            'serial_number' => 'SN-HP-33012',
            'assigned_user' => 'Siti Nurhaliza (Finance)',
        ]);

        // 2. Buat Data Tiket Dummy (Terhubung dengan Aset)
        Ticket::create([
            'title'       => 'Layar Laptop Blinking / Bintik Hitam',
            'category'    => 'Hardware',
            'priority'    => 'High',
            'description' => 'Layar berkedip-kedip saat membuka aplikasi berat.',
            'asset_id'    => $asset1->id,
            'status'      => 'Open',
        ]);

        Ticket::create([
            'title'       => 'Kertas Printer Sering Tersangkut (Paper Jam)',
            'category'    => 'Hardware',
            'priority'    => 'Medium',
            'description' => 'Printer mengeluarkan suara bising dan kertas tidak tertarik dengan baik.',
            'asset_id'    => $asset2->id,
            'status'      => 'In Progress',
        ]);

        Ticket::create([
            'title'       => 'Gagal Konek ke Wi-Fi Kantor',
            'category'    => 'Network',
            'priority'    => 'Low',
            'description' => 'Sering terputus sendiri setiap 10 menit.',
            'asset_id'    => null, // Tanpa aset khusus
            'status'      => 'Closed',
        ]);
    }
}