<?php

declare(strict_types=1);

namespace App\Services\Certificate;

use App\Models\Certificate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use RuntimeException;
use Spatie\Browsershot\Browsershot;
use Throwable;

class CertificatePdfService
{
    /**
     * Render the certificate as a PDF on disk and return its absolute path.
     *
     * @throws RuntimeException when Browsershot fails (typically missing Chromium runtime).
     */
    public function generate(Certificate $certificate): string
    {
        $certificate->loadMissing('member');

        $html = View::make('portal.certificates.template', [
            'certificate' => $certificate,
            'member' => $certificate->member,
        ])->render();

        $directory = storage_path('app/certificates/'.$certificate->member_id);
        File::ensureDirectoryExists($directory);

        $path = $directory.DIRECTORY_SEPARATOR.$certificate->id.'.pdf';

        try {
            $shot = Browsershot::html($html)
                ->format('A4')
                ->landscape()
                ->showBackground()
                ->timeout(30);

            if (app()->environment('production')) {
                $shot->noSandbox();
            }

            $chromePath = env('BROWSERSHOT_CHROME_PATH');
            if (is_string($chromePath) && $chromePath !== '') {
                $shot->setChromePath($chromePath);
            }

            $shot->save($path);
        } catch (Throwable $e) {
            throw new RuntimeException(
                'Gagal membuat PDF sertifikat: '.$e->getMessage(),
                previous: $e
            );
        }

        return $path;
    }
}
