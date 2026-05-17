<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\Billing\PaymentHistoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentHistoryController extends Controller
{
    public function __construct(private readonly PaymentHistoryService $history) {}

    public function index(Request $request): View
    {
        $member = $request->user('member');

        return view('portal.payment-history', [
            'member' => $member,
            'payments' => $this->history->listForMember($member),
        ]);
    }
}
