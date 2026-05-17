<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bill_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal');
            $table->string('uraian');
            $table->unsignedBigInteger('nominal');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->index('member_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
