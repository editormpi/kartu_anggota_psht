<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->year('tahun');
            $table->string('uraian');
            $table->unsignedBigInteger('nominal');
            $table->unsignedBigInteger('dibayar')->default(0);
            $table->bigInteger('sisa')->default(0);
            $table->enum('status', ['Lunas', 'Belum Lunas', 'Sebagian'])->default('Belum Lunas');
            $table->timestamps();
            $table->index(['member_id', 'tahun']);
        });

        if (\Illuminate\Support\Facades\DB::connection()->getDriverName() !== 'sqlite') {
            \Illuminate\Support\Facades\DB::unprepared(<<<'SQL'
                CREATE TRIGGER bills_sisa_bi BEFORE INSERT ON bills
                FOR EACH ROW SET NEW.sisa = NEW.nominal - NEW.dibayar;
            SQL);
            \Illuminate\Support\Facades\DB::unprepared(<<<'SQL'
                CREATE TRIGGER bills_sisa_bu BEFORE UPDATE ON bills
                FOR EACH ROW SET NEW.sisa = NEW.nominal - NEW.dibayar;
            SQL);
        }
    }

    public function down(): void
    {
        if (\Illuminate\Support\Facades\DB::connection()->getDriverName() !== 'sqlite') {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER IF EXISTS bills_sisa_bi');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER IF EXISTS bills_sisa_bu');
        }

        Schema::dropIfExists('bills');
    }
};
