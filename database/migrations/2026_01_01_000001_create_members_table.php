<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('nik_hash', 64)->unique();
            $table->text('nik_encrypted');
            $table->string('password');
            $table->boolean('must_change_password')->default(true);
            $table->boolean('is_active')->default(false);
            $table->string('full_name');
            $table->string('tingkat')->nullable();
            $table->string('status_keanggotaan')->default('Aktif');
            $table->date('tanggal_keanggotaan')->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('weton')->nullable();
            $table->string('agama')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('ranting')->nullable();
            $table->string('rayon')->nullable();
            $table->string('tempat_latihan')->nullable();
            $table->string('hp', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
