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
    Schema::table('tickets', function (Blueprint $table) {
        $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
        $table->string('category')->default('Other');
        $table->string('assigned_to')->nullable();
        $table->text('resolution_notes')->nullable();
        $table->string('attachment')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
