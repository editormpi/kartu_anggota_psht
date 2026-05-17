<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Member;
use App\Services\Certificate\CertificatePdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        $query = Certificate::with('member');

        if ($search = $request->get('search')) {
            $query->where('nomor', 'like', "%{$search}%")
                ->orWhere('jenis', 'like', "%{$search}%")
                ->orWhereHas('member', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
        }

        $certificates = $query->orderByDesc('tanggal')->paginate(20)->withQueryString();

        return view('admin.certificates.index', compact('certificates'));
    }

    public function create(): View
    {
        return view('admin.certificates.create', [
            'members' => Member::orderBy('full_name')->get(['id', 'full_name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'jenis'     => 'required|string|max:255',
            'nomor'     => 'required|string|max:255|unique:certificates,nomor',
            'tanggal'   => 'required|date',
        ]);

        Certificate::create($data);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    public function show(Certificate $certificate): RedirectResponse
    {
        return redirect()->route('admin.certificates.edit', $certificate);
    }

    public function edit(Certificate $certificate): View
    {
        return view('admin.certificates.edit', [
            'certificate' => $certificate->load('member'),
            'members'     => Member::orderBy('full_name')->get(['id', 'full_name']),
        ]);
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'jenis'     => 'required|string|max:255',
            'nomor'     => 'required|string|max:255|unique:certificates,nomor,'.$certificate->id,
            'tanggal'   => 'required|date',
        ]);

        $certificate->update($data);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    public function generatePdf(Certificate $certificate): RedirectResponse
    {
        try {
            $path = app(CertificatePdfService::class)->generate($certificate);

            return back()->with('success', "PDF berhasil dibuat: {$path}");
        } catch (Throwable $e) {
            return back()->with('error', "Gagal membuat PDF: {$e->getMessage()}");
        }
    }
}
