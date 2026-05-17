<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\Billing\BillService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillController extends Controller
{
    public function __construct(private readonly BillService $bills) {}

    public function index(Request $request): View
    {
        $member = $request->user('member');

        return view('portal.bills', [
            'member' => $member,
            'bills' => $this->bills->listForMember($member),
        ]);
    }
}
