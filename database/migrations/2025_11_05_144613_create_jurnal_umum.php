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
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kas_id')->constrained('kas')->onDelete('cascade');
            $table->date('tanggal');
            $table->foreignId('kodeakun_id')->constrained('kode_akun')->onDelete('restrict');
            $table->text('keterangan')->nullable();
            $table->decimal('debit', 15, 0)->default(0);
            $table->decimal('kredit', 15, 0)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_umum');
    }
};
