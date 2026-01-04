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
        Schema::create('warga_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('email')->unique();
            $table->string('no_kk', 20);
            $table->string('rt', 5);
            $table->string('rw', 5);
            $table->text('address');
            $table->string('mother_name');
            $table->string('father_name');

            $table->enum('marital_status', [
                'belum_kawin','kawin','cerai_hidup','cerai_mati'
            ]);

            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->references('id')->on('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga_verifications');
    }
};
