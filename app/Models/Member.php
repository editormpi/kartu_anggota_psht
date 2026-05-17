<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MemberStatus;
use App\Support\NikEncryptor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nik_hash
 * @property string $nik_encrypted
 * @property string $password
 * @property bool $must_change_password
 * @property bool $is_active
 * @property string $full_name
 * @property ?string $tingkat
 * @property string $status_keanggotaan
 * @property ?Carbon $tanggal_keanggotaan
 * @property ?string $photo_path
 * @property ?string $jenis_kelamin
 * @property ?string $tempat_lahir
 * @property ?Carbon $tanggal_lahir
 * @property ?Carbon $last_login_at
 */
class Member extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'nik_hash',
        'nik_encrypted',
        'password',
        'must_change_password',
        'is_active',
        'full_name',
        'tingkat',
        'status_keanggotaan',
        'tanggal_keanggotaan',
        'photo_path',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'weton',
        'agama',
        'pekerjaan',
        'alamat',
        'ranting',
        'rayon',
        'tempat_latihan',
        'hp',
        'keterangan',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'nik_encrypted',
        'nik_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'is_active' => 'boolean',
            'tanggal_keanggotaan' => 'date',
            'tanggal_lahir' => 'date',
            'last_login_at' => 'datetime',
            'status_keanggotaan' => MemberStatus::class,
        ];
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function loginAttempts(): HasMany
    {
        return $this->hasMany(LoginAttempt::class, 'nik_hash', 'nik_hash');
    }

    public function getNik(): string
    {
        return app(NikEncryptor::class)->decrypt($this->nik_encrypted);
    }

    public function getMaskedNikAttribute(): string
    {
        $nik = $this->getNik();

        return substr($nik, 0, 4).str_repeat('*', 8).substr($nik, -4);
    }
}
