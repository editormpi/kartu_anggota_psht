<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Member;
use App\Models\PaymentHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = PaymentHistory::with(['member', 'bill']);

        if ($search = $request->get('search')) {
            $query->where('uraian', 'like', "%{$search}%")
                ->orWhereHas('member', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
        }

        if ($memberId = $request->get('member_id')) {
            $query->where('member_id', $memberId);
        }

        $payments = $query->orderByDesc('tanggal')->paginate(20)->withQueryString();
        $members  = Member::orderBy('full_name')->get(['id', 'full_name']);

        return view('admin.payments.index', compact('payments', 'members'));
    }

    public function create(): View
    {
        return view('admin.payments.create', [
            'members' => Member::orderBy('full_name')->get(['id', 'full_name']),
            'bills'   => Bill::with('member')->orderByDesc('tahun')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id'  => 'required|exists:members,id',
            'bill_id'    => 'nullable|exists:bills,id',
            'tanggal'    => 'required|date',
            'uraian'     => 'required|string|max:255',
            'nominal'    => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        PaymentHistory::create($data);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Riwayat pembayaran berhasil ditambahkan.');
    }

    public function show(PaymentHistory $payment): RedirectResponse
    {
        return redirect()->route('admin.payments.edit', $payment);
    }

    public function edit(PaymentHistory $payment): View
    {
        return view('admin.payments.edit', [
            'payment' => $payment->load(['member', 'bill']),
            'members' => Member::orderBy('full_name')->get(['id', 'full_name']),
            'bills'   => Bill::with('member')->orderByDesc('tahun')->get(),
        ]);
    }

    public function update(Request $request, PaymentHistory $payment): RedirectResponse
    {
        $data = $request->validate([
            'member_id'  => 'required|exists:members,id',
            'bill_id'    => 'nullable|exists:bills,id',
            'tanggal'    => 'required|date',
            'uraian'     => 'required|string|max:255',
            'nominal'    => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $payment->update($data);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Riwayat pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentHistory $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Riwayat pembayaran berhasil dihapus.');
    }
}
