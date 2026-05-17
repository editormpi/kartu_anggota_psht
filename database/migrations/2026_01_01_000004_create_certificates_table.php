<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->string('jenis');
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->timestamps();
            $table->index('member_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
