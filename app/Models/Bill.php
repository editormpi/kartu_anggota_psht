<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BillStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $member_id
 * @property int $tahun
 * @property string $uraian
 * @property int $nominal
 * @property int $dibayar
 * @property int $sisa
 * @property BillStatus $status
 */
class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'tahun',
        'uraian',
        'nominal',
        'dibayar',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'nominal' => 'integer',
            'dibayar' => 'integer',
            'sisa' => 'integer',
            'status' => BillStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Bill $bill): void {
            $bill->sisa = (int) $bill->nominal - (int) $bill->dibayar;
        });
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
