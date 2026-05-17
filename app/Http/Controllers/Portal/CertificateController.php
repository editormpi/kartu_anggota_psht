<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\Certificate\CertificatePdfService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        $member = $request->user('member');

        return view('portal.certificates.index', [
            'member' => $member,
            'certificates' => $member->certificates()->orderByDesc('tanggal')->get(),
        ]);
    }

    public function download(Request $request, Certificate $certificate, CertificatePdfService $pdfService): BinaryFileResponse
    {
        $member = $request->user('member');

        abort_if($certificate->member_id !== $member->id, 403);

        $path = $pdfService->generate($certificate);
        $filename = sprintf('Sertifikat_%s_%s.pdf', $member->full_name, $certificate->jenis);

        return response()->download($path, $filename);
    }
}
