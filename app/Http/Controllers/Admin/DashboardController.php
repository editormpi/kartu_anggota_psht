<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Certificate;
use App\Models\Member;
use App\Models\PaymentHistory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_anggota'       => Member::count(),
            'anggota_aktif'       => Member::where('is_active', true)->count(),
            'total_tagihan'       => Bill::count(),
            'tagihan_belum_lunas' => Bill::where('status', '!=', 'Lunas')->count(),
            'total_pembayaran'    => PaymentHistory::sum('nominal'),
            'total_sertifikat'    => Certificate::count(),
        ];

        // Chart: jumlah anggota per status keanggotaan
        $statusData = Member::selectRaw('status_keanggotaan, count(*) as total')
            ->groupBy('status_keanggotaan')
            ->pluck('total', 'status_keanggotaan')
            ->toArray();

        // Chart: pembayaran per bulan (tahun ini)
        $pembayaranBulanan = PaymentHistory::selectRaw('MONTH(tanggal) as bulan, SUM(nominal) as total')
            ->whereYear('tanggal', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $pembayaranSeries = array_map(fn ($i) => (int) ($pembayaranBulanan[$i] ?? 0), range(1, 12));

        // Chart: tagihan per status
        $tagihanStatus = Bill::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'statusData',
            'bulanLabels',
            'pembayaranSeries',
            'tagihanStatus',
        ));
    }
}
