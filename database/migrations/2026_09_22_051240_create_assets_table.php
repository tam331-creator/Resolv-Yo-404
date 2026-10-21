<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->unique();
            $table->string('name');
            $table->string('category'); // Laptop, Desktop, Printer, Software, Tablet, Monitor, dll
            $table->string('brand')->nullable(); // Dell, HP, Lenovo, Apple, dll
            $table->string('model')->nullable(); // Latitude 7420, ProDesk 600, dll
            $table->string('serial_number')->nullable();
            $table->string('assigned_user')->nullable(); // Nama/ID Staff
            $table->string('location')->nullable(); // HQ - IT Room, HQ - Finance, Cloud, dll
            $table->string('status')->default('Active'); // Active, Maintenance, Retired
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->text('remarks')->nullable();
            $table->string('image')->nullable(); // Gambar peranti jika ada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
