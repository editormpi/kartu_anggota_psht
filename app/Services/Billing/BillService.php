<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Enums\BillStatus;
use App\Models\Bill;
use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;

class BillService
{
    /** @return Collection<int, Bill> */
    public function listForMember(Member $member): Collection
    {
        return $member->bills()
            ->orderByDesc('tahun')
            ->orderBy('uraian')
            ->get();
    }

    public function recomputeStatus(Bill $bill): void
    {
        $bill->refresh();
        $status = BillStatus::fromAmounts($bill->nominal, $bill->dibayar);

        if ($bill->status !== $status) {
            $bill->forceFill(['status' => $status])->save();
        }
    }
}
