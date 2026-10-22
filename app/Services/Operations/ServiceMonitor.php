<?php

namespace App\Services\Operations;

use App\Models\OperationServiceCheck;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;

class ServiceMonitor
{
    public const SERVICES = ['gemini' => 'Gemini', 'email' => 'Email', 'payment' => 'Pakasir', 'storage' => 'Storage'];

    public function check(string $service, ?int $actor): OperationServiceCheck
    {
        abort_unless(isset(self::SERVICES[$service]), 422);
        $start = hrtime(true);
        try {
            [$status, $summary] = match ($service) {
                'gemini' => $this->gemini(),
                'email' => $this->email(),
                'payment' => $this->payment(),
                'storage' => $this->storage(),
            };
        } catch (\Throwable) {
            $status = 'degraded';
            $summary = 'Connection or verification failed. Review the provider configuration and try again.';
        }

        return OperationServiceCheck::create([
            'service' => $service, 'status' => $status, 'summary' => $summary,
            'duration_ms' => (int) round((hrtime(true) - $start) / 1e6), 'checked_by' => $actor,
        ]);
    }

    private function gemini(): array
    {
        if (! config('services.gemini.key')) {
            return ['unconfigured', 'Gemini API key is not configured.'];
        }
        $response = Http::acceptJson()->withHeaders(['x-goog-api-key' => config('services.gemini.key')])
            ->connectTimeout(4)->timeout(8)->withoutRedirecting()
            ->get('https://generativelanguage.googleapis.com/v1beta/models/'.rawurlencode(config('services.gemini.model')));

        return $response->successful() && is_string($response->json('name'))
            ? ['healthy', 'Model metadata is reachable with the configured key. Generation was not tested.']
            : ['degraded', __('Model metadata check failed (HTTP :status).', ['status' => $response->status()])];
    }

    private function email(): array
    {
        if (in_array(config('mail.default'), ['log', 'array', null], true)) {
            return ['unconfigured', 'SMTP is not configured as the active mailer. No email was sent.'];
        }
        if (config('mail.default') !== 'smtp') {
            return ['unchecked', 'The active mail transport does not support an SMTP connection check.'];
        }
        if (! config('mail.mailers.smtp.host')) {
            return ['unconfigured', 'SMTP is not configured as the active mailer. No email was sent.'];
        }
        $transport = Mail::mailer()->getSymfonyTransport();
        if (! $transport instanceof SmtpTransport) {
            return ['unchecked', 'The active mail transport does not support an SMTP connection check.'];
        }
        try {
            $transport->getStream()->setTimeout(6);
            $transport->start();

            return ['healthy', 'SMTP connection and configured authentication succeeded. Delivery was not tested.'];
        } finally {
            $transport->stop();
        }
    }

    private function payment(): array
    {
        if (! config('pakasir.api_key') || ! config('pakasir.slug')) {
            return ['unconfigured', 'Pakasir project or API key is not configured.'];
        }
        $payment = Payment::whereNotNull('pakasir_txn_id')->where('pakasir_is_sandbox', (bool) config('pakasir.sandbox'))->latest('id')->first();
        if (! $payment) {
            return ['unchecked', 'No V2 transaction exists in this environment for a read-only status check.'];
        }
        $response = Http::acceptJson()->withHeaders(['X-Api-Key' => config('pakasir.api_key')])->connectTimeout(4)->timeout(8)->withoutRedirecting()
            ->get('https://app.pakasir.com/api/v2/transaction-status/'.rawurlencode(config('pakasir.slug')).'/'.rawurlencode($payment->pakasir_txn_id));
        $data = $response->json();

        return $response->successful() && is_array($data) && ($data['txn_id'] ?? null) === $payment->pakasir_txn_id && in_array($data['status'] ?? null, ['pending', 'completed', 'canceled'], true)
            ? ['healthy', 'The read-only transaction status endpoint responded. No payment was created or modified.']
            : ['degraded', __('Transaction status verification failed (HTTP :status).', ['status' => $response->status()])];
    }

    private function storage(): array
    {
        $disk = Storage::disk(config('filesystems.default'));
        $path = 'operations-probes/'.Str::uuid().'.txt';
        $value = Str::random(32);
        try {
            if (! $disk->put($path, $value) || $disk->get($path) !== $value) {
                throw new \RuntimeException;
            }
            if (! $disk->delete($path)) {
                throw new \RuntimeException;
            }

            return ['healthy', 'A temporary probe file was written, read and removed on the default disk.'];
        } finally {
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }
}
