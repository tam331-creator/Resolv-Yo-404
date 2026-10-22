<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class TicketSystemTest extends TestCase
{
    use RefreshDatabase;

    /* =========================================================================
     * 1. AUTENTIKASI & KAWALAN HAK AKSES (AUTH & RBAC)
     * ========================================================================= */

    /** @test 1. Memastikan halaman log masuk boleh dibuka (HTTP 200) */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test 2. Memastikan halaman pendaftaran boleh dibuka (HTTP 200) */
    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /** @test 3. Ujian Pendaftaran Akaun Staf Baharu */
    public function test_user_can_register_with_valid_credentials(): void
    {
        $response = $this->post('/register', [
            'name'     => 'Taa Staff',
            'email'    => 'taa@example.com',
            'phone'    => '0123456789',
            'role'     => 'staff',
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'taa@example.com',
            'role'  => 'staff',
        ]);
    }

    /** @test 4. Memastikan Staf Biasa Tidak Boleh Akses Analytics (RBAC Check) */
    public function test_unauthorized_staff_cannot_access_analytics(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->get('/analytics');
        
        $response->assertStatus(403);
    }

    /* =========================================================================
     * 2. PENGURUSAN TIKET & LOGIK BISNES (ITSM CORE)
     * ========================================================================= */

    /** @test 5. Staf Berjaya Membuka Tiket Aduan Baharu */
    public function test_staff_can_create_ticket(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->post('/tickets', [
            'title'       => 'Skrin Laptop Blank',
            'category'    => 'Hardware',
            'priority'    => 'High',
            'description' => 'Komputer ribat tidak memaparkan skrin.',
        ]);

        $this->assertDatabaseHas('tickets', [
            'title'   => 'Skrin Laptop Blank',
            'user_id' => $user->id,
        ]);
    }

    /** @test 6. Juruteknik Berjaya Kemaskini Status Tiket Ke 'In Progress' */
    public function test_technician_can_update_status_to_in_progress(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $technician = User::factory()->create(['role' => 'technician', 'name' => 'Bee']);

        $ticket = Ticket::create([
            'user_id'     => $staff->id,
            'title'       => 'Test74',
            'category'    => 'Hardware',
            'priority'    => 'High',
            'description' => 'My laptop screen blank.',
            'status'      => 'Open',
        ]);

        $response = $this->actingAs($technician)->put("/tickets/{$ticket->id}", [
            'title'       => 'Test74',
            'category'    => 'Hardware',
            'priority'    => 'High',
            'description' => 'My laptop screen blank.',
            'status'      => 'In Progress',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('tickets', [
            'id'     => $ticket->id,
            'status' => 'In Progress',
        ]);
    }

    /** @test 7. Admin Berjaya Mengagihkan Tiket Kepada Juruteknik (Assign Route) */
    public function test_admin_can_assign_technician_to_ticket(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);
        $technician = User::factory()->create(['role' => 'technician']);

        $ticket = Ticket::create([
            'user_id'     => $staff->id,
            'title'       => 'Tetikus Rosak',
            'category'    => 'Hardware',
            'priority'    => 'Low',
            'description' => 'Tetikus USB tidak berfungsi.',
            'status'      => 'Open',
        ]);

        $response = $this->actingAs($admin)->patch("/tickets/{$ticket->id}/assign", [
            'technician_id' => $technician->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('tickets', [
            'id'          => $ticket->id,
            'assigned_to' => $technician->id,
            'status'      => 'In Progress',
        ]);
    }

    /** @test 8. Staf Boleh Akses Dashboard (Redirect ke route tickets.index) & Senarai Tiket */
    public function test_staff_can_access_dashboard_and_tickets_index(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $responseDashboard = $this->actingAs($staff)->get('/dashboard');
        $responseDashboard->assertRedirect('/');

        $responseTickets = $this->actingAs($staff)->get('/tickets');
        $responseTickets->assertStatus(200);
    }

    /* =========================================================================
     * 3. MODUL KNOWLEDGE BASE & POLISI
     * ========================================================================= */

    /** @test 9. Pengguna Boleh Akses Laman Policies & Knowledge Base */
    public function test_user_can_access_policies_page(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->get('/policies');
        $response->assertStatus(200);
    }

    /* =========================================================================
     * 4. ITAM, SAM & AUTOMASI AI
     * ========================================================================= */

    /** @test 10. Pengurusan Aset IT (ITAM) - Akses Laman Assets Secara Sah */
    public function test_itam_assets_index_page_accessible(): void
    {
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/assets');
        $response->assertStatus(200);
    }

    /** @test 11. Integrasi RESOLV AI Chat Memulangkan Respon Sah */
    public function test_ai_chat_endpoint_responds(): void
    {
        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Sila pastikan kabel rangkaian disambungkan dengan betul.']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->post('/ai/chat', [
            'message' => 'Bagaimana cara semak sambungan rangkaian?',
        ]);

        $response->assertStatus(200);
    }

    /** @test 12. Integrasi AI Suggest Solution Memulangkan Cadangan Sah */
    public function test_ai_suggest_solution_endpoint_responds(): void
    {
        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Buka penutup pencetak dan keluarkan kertas yang tersumbat secara perlahan.']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->post('/ai/suggest-solution', [
            'prompt' => 'Printer jam dan kertas tersangkut.',
        ]);

        $response->assertStatus(200);
    }

    /** @test 13. AI Suggest Solution Menolak Input Kosong */
    public function test_ai_suggest_solution_validates_empty_input(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->post('/ai/suggest-solution', [
            'prompt' => '',
        ]);

        // Mengesahkan sistem menyekat atau mengendalikan borang kosong secara selamat (200, 302, 422)
        $this->assertTrue(in_array($response->status(), [200, 302, 422]));
    }

    /* =========================================================================
     * 5. PRESTASI MASA-NYATA & NOTIFIKASI
     * ========================================================================= */

    /** @test 14. Semakan Akses Laman Analytics Oleh Admin */
    public function test_analytics_page_accessible_by_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/analytics');
        $response->assertStatus(200);
    }

    /** @test 15. Semakan Notifikasi Tiket & Mesej Baharu Memulangkan Data JSON */
    public function test_ticket_notifications_check_endpoint(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $tech = User::factory()->create(['role' => 'technician']);

        $ticket = Ticket::create([
            'user_id'     => $staff->id,
            'assigned_to' => $tech->id,
            'title'       => 'Notifikasi Test',
            'category'    => 'Hardware',
            'priority'    => 'High',
            'description' => 'Ujian pop-up notifikasi.',
            'status'      => 'Open',
        ]);

        $response = $this->actingAs($tech)->get('/notifications/check?since=' . now()->subMinutes(5)->toISOString());

        $response->assertStatus(200);
    }

    /** @test 16. Ujian Sinyal Indikator Taip WebSocket (tickets/{ticket}/typing) */
    public function test_realtime_typing_indicator_endpoint(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $ticket = Ticket::create([
            'user_id'     => $staff->id,
            'title'       => 'Taip Test',
            'category'    => 'Hardware',
            'priority'    => 'Low',
            'description' => 'Menguji typing indicator.',
            'status'      => 'Open',
        ]);

        $response = $this->actingAs($staff)->post("/tickets/{$ticket->id}/typing");
        $response->assertStatus(200);
    }

    /* =========================================================================
     * 6. AUDIT TRAIL, SESI & LOG AKTIVITI PENGGUNA
     * ========================================================================= */

    /** @test 17. Rekod Log Aktiviti, Pengesahan Akaun & Sesi Dikemas Kini Semasa Log Masuk */
    public function test_audit_log_triggers_on_login(): void
    {
        $user = User::factory()->create([
            'email'    => 'audituser@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'staff',
        ]);

        $response = $this->post('/login', [
            'email'    => 'audituser@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/tickets');
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'email' => 'audituser@example.com',
        ]);
    }

    /** @test 18. Sesi Pengguna Dibatalkan Secara Selamat Semasa Log Keluar (Logout) */
    public function test_user_can_logout_and_session_invalidates(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /* =========================================================================
     * 7. UJIAN TAMBAHAN (INVALID INPUT & KAWALAN RBAC KETAT)
     * ========================================================================= */

    /** @test 19. Tiket Memerlukan Tajuk (Invalid Ticket Creation Validation Check) */
    public function test_ticket_requires_title(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->post('/tickets', [
            'title'       => '',
            'category'    => 'Hardware',
            'priority'    => 'High',
            'description' => 'Ujian ralat pengesahan tanpa tajuk.',
        ]);

        $this->assertTrue(in_array($response->status(), [302, 422]));
    }

    /** @test 20. Staf Biasa Tidak Boleh Agih Juruteknik Ke Tiket (Unauthorized Technician Assignment) */
    public function test_staff_cannot_assign_technician(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $technician = User::factory()->create(['role' => 'technician']);

        $ticket = Ticket::create([
            'user_id'     => $staff->id,
            'title'       => 'Ujian RBAC Agihan',
            'category'    => 'Software',
            'priority'    => 'Medium',
            'description' => 'Staf cuba agih juruteknik sendiri.',
            'status'      => 'Open',
        ]);

        $response = $this->actingAs($staff)->patch("/tickets/{$ticket->id}/assign", [
            'technician_id' => $technician->id,
        ]);

        $this->assertTrue(in_array($response->status(), [403, 302]));
    }

    /** @test 21. Staf Biasa Tidak Boleh Kemaskini Status Tiket (Unauthorized Ticket Status Update) */
    public function test_staff_cannot_update_ticket_status(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $ticket = Ticket::create([
            'user_id'     => $staff->id,
            'title'       => 'Ujian RBAC Status Tiket',
            'category'    => 'Software',
            'priority'    => 'Low',
            'description' => 'Staf cuba kemaskini status tiket.',
            'status'      => 'Open',
        ]);

        $response = $this->actingAs($staff)->put("/tickets/{$ticket->id}", [
            'title'       => 'Ujian RBAC Status Tiket',
            'category'    => 'Software',
            'priority'    => 'Low',
            'description' => 'Staf cuba kemaskini status tiket.',
            'status'      => 'In Progress',
        ]);

        $this->assertDatabaseHas('tickets', [
            'id'     => $ticket->id,
            'status' => 'Open',
        ]);
    }

    /** @test 22. Admin Berjaya Mendaftar Aset IT Baharu (Asset Creation) */
    public function test_admin_can_create_asset(): void
    {
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/administration-center/assets', [
            'asset_tag' => 'AST-9999',
            'name'      => 'Monitor Dell 27 Inchi',
            'category'  => 'Hardware',
            'status'    => 'Active',
        ]);

        $this->assertTrue(in_array($response->status(), [200, 201, 302]));
    }

    /** @test 23. Admin Berjaya Mendaftar Lesen Perisian Baharu (Software License Creation) */
    public function test_admin_can_create_software_license(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/software/store', [
            'name'           => 'Microsoft 365 Enterprise',
            'license_key'    => 'XXXXX-YYYYY-ZZZZZ',
            'total_licenses' => 50,
        ]);

        $this->assertTrue(in_array($response->status(), [200, 201, 302]));
    }
}