<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\BillStatus;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillController extends Controller
{
    public function index(Request $request): View
    {
        $query = Bill::with('member');

        if ($search = $request->get('search')) {
            $query->where('uraian', 'like', "%{$search}%")
                ->orWhereHas('member', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($tahun = $request->get('tahun')) {
            $query->where('tahun', $tahun);
        }

        $bills   = $query->orderByDesc('tahun')->orderBy('id')->paginate(20)->withQueryString();
        $statuses = BillStatus::cases();
        $tahunList = Bill::selectRaw('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');

        return view('admin.bills.index', compact('bills', 'statuses', 'tahunList'));
    }

    public function create(): View
    {
        return view('admin.bills.create', [
            'members'  => Member::orderBy('full_name')->get(['id', 'full_name']),
            'statuses' => BillStatus::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'tahun'     => 'required|integer|min:2000|max:2100',
            'uraian'    => 'required|string|max:255',
            'nominal'   => 'required|integer|min:0',
            'dibayar'   => 'required|integer|min:0',
            'status'    => 'required|in:Lunas,Belum Lunas,Sebagian',
        ]);

        Bill::create($data);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function show(Bill $bill): RedirectResponse
    {
        return redirect()->route('admin.bills.edit', $bill);
    }

    public function edit(Bill $bill): View
    {
        return view('admin.bills.edit', [
            'bill'     => $bill->load('member'),
            'members'  => Member::orderBy('full_name')->get(['id', 'full_name']),
            'statuses' => BillStatus::cases(),
        ]);
    }

    public function update(Request $request, Bill $bill): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'tahun'     => 'required|integer|min:2000|max:2100',
            'uraian'    => 'required|string|max:255',
            'nominal'   => 'required|integer|min:0',
            'dibayar'   => 'required|integer|min:0',
            'status'    => 'required|in:Lunas,Belum Lunas,Sebagian',
        ]);

        $bill->update($data);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Bill $bill): RedirectResponse
    {
        $bill->delete();

        return redirect()->route('admin.bills.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
