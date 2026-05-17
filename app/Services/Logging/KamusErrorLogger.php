<?php

declare(strict_types=1);

namespace App\Services\Logging;

use App\Support\NikEncryptor;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class KamusErrorLogger
{
    public function __construct(private readonly NikEncryptor $nikEncryptor) {}

    /**
     * Log an exception with structured context to the kamus_error channel,
     * and forward to the remote endpoint when configured.
     */
    public function report(Throwable $e, ?Request $request = null): void
    {
        $payload = $this->buildPayload($e, $request);

        try {
            Log::channel('kamus_error')->error($e->getMessage(), $payload);
        } catch (Throwable $logError) {
            error_log('KamusErrorLogger local log failure: '.$logError->getMessage());
        }

        $endpoint = config('logging.kamus_error_endpoint');
        if (is_string($endpoint) && $endpoint !== '') {
            $this->forwardRemote($endpoint, $payload);
        }
    }

    /** @return array<string, mixed> */
    private function buildPayload(Throwable $e, ?Request $request): array
    {
        $user = Auth::guard('member')->user();
        $userContext = null;

        if ($user !== null && method_exists($user, 'getNik')) {
            try {
                $userContext = [
                    'member_id' => $user->getKey(),
                    'masked_nik' => $this->nikEncryptor->mask($user->getNik()),
                ];
            } catch (Throwable) {
                $userContext = ['member_id' => $user->getKey()];
            }
        }

        return [
            'timestamp' => now()->toIso8601String(),
            'app' => 'psht-jember-portal',
            'env' => app()->environment(),
            'exception_class' => $e::class,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace_id' => (string) Str::uuid(),
            'user_context' => $userContext,
            'request' => $request === null ? null : [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ],
            'stack' => $e->getTraceAsString(),
        ];
    }

    /** @param array<string, mixed> $payload */
    private function forwardRemote(string $endpoint, array $payload): void
    {
        try {
            $token = (string) config('logging.kamus_error_token');
            $headers = ['Content-Type' => 'application/json'];
            if ($token !== '') {
                $headers['Authorization'] = 'Bearer '.$token;
            }

            (new Client(['timeout' => 5]))->post($endpoint, [
                'headers' => $headers,
                'json' => $payload,
            ]);
        } catch (GuzzleException|Throwable $remoteError) {
            error_log('KamusErrorLogger remote forward failure: '.$remoteError->getMessage());
        }
    }
}
