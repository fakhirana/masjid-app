<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // tambah kolom
            $table->foreignId('user_id')
                    ->after('id')
                    // ->constrained('users')
                    ->nullable()
                    ->cascadeOnDelete();

            $table->enum('status', ['active', 'inactive'])
                    ->default('active')
                    ->after('description');

            $table->unsignedInteger('joined')
                    ->default(0)
                    ->after('status');

            // hapus kolom lama
            $table->dropColumn('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);

            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status', 'joined']);
        });
    }
};
