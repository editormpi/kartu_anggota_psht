<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Models\Bill;
use App\Models\Member;
use App\Models\PaymentHistory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PaymentHistoryService
{
    public function __construct(private readonly BillService $billService) {}

    /** @return Collection<int, PaymentHistory> */
    public function listForMember(Member $member): Collection
    {
        return $member->paymentHistories()
            ->with('bill')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Record a payment and, if linked to a bill, update its dibayar and status.
     */
    public function record(Member $member, ?Bill $bill, string $tanggal, string $uraian, int $nominal, ?string $keterangan): PaymentHistory
    {
        return DB::transaction(function () use ($member, $bill, $tanggal, $uraian, $nominal, $keterangan): PaymentHistory {
            $payment = $member->paymentHistories()->create([
                'bill_id' => $bill?->id,
                'tanggal' => $tanggal,
                'uraian' => $uraian,
                'nominal' => $nominal,
                'keterangan' => $keterangan,
            ]);

            if ($bill !== null) {
                $bill->forceFill(['dibayar' => $bill->dibayar + $nominal])->save();
                $this->billService->recomputeStatus($bill);
            }

            return $payment;
        });
    }
}
