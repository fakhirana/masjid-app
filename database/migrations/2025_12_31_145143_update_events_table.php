<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // 1. Menghapus kolom is_active
            $table->dropColumn('is_active');

            // 2. Menambahkan kolom baru
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Untuk relasi ke pembuat/penanggung jawab
            $table->string('status')->default('active'); // active / inactive
            $table->timestamp('joined_at')->nullable(); // Tanggal mulai pakai
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('is_active')->default(1);
            $table->dropColumn(['user_id', 'status', 'joined_at']);
        });
    }
};