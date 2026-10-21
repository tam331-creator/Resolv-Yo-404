<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'brand')) $table->string('brand')->nullable();
            if (!Schema::hasColumn('assets', 'model')) $table->string('model')->nullable();
            if (!Schema::hasColumn('assets', 'category')) $table->string('category')->nullable();
            if (!Schema::hasColumn('assets', 'purchase_date')) $table->date('purchase_date')->nullable();
            if (!Schema::hasColumn('assets', 'warranty_expiry')) $table->date('warranty_expiry')->nullable();
            if (!Schema::hasColumn('assets', 'remarks')) $table->text('remarks')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['brand', 'model', 'category', 'purchase_date', 'warranty_expiry', 'remarks']);
        });
    }
};