<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('software_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('software_tag')->unique();
            $table->string('software_name');
            $table->string('category');
            $table->string('vendor');
            $table->string('version')->nullable();
            $table->string('license_type');
            $table->integer('total_seats')->default(1);
            $table->integer('assigned_seats')->default(0);
            $table->integer('available_seats')->default(1);
            $table->string('license_key')->nullable();
            $table->text('admin_portal_url')->nullable();
            $table->date('purchase_date');
            $table->date('expiration_date');
            $table->string('billing_cycle');
            $table->decimal('cost', 12, 2)->default(0.00);
            $table->string('invoice_number')->nullable();
            $table->boolean('enable_email_alert')->default(true);
            $table->string('department')->nullable();
            $table->string('assigned_user')->nullable();
            $table->string('status')->default('Active');
            $table->string('attachment_path')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('software_licenses');
    }
};