<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class XenditService
{
    protected function secretKey(): string
    {
        $secretKey = (string) config('xendit.secret_key');
        if ($secretKey === '') {
            throw new RuntimeException('XENDIT_SECRET_KEY belum diisi.');
        }

        return $secretKey;
    }

    protected function baseUrl(): string
    {
        return rtrim((string) config('xendit.base_url', 'https://api.xendit.co'), '/');
    }

    protected function publicBaseUrl(): string
    {
        return rtrim((string) config('xendit.public_url', config('app.url', 'http://localhost:8000')), '/');
    }

    /**
     * Create a Xendit invoice and return normalized payload.
     *
     * @return array{id:string,external_id:string,status:string,invoice_url:?string,raw:array}
     */
    public function createInvoice(Order $order): array
    {
        $secretKey = $this->secretKey();
        $baseUrl = $this->baseUrl();

        $payload = [
            'external_id' => $order->order_number,
            'amount' => (int) $order->total_price,
            'description' => 'Pembayaran untuk order ' . $order->order_number,
            'currency' => 'IDR',
            'invoice_duration' => 86400,
            'success_redirect_url' => $this->publicBaseUrl() . '/invoice/' . $order->id,
            'failure_redirect_url' => $this->publicBaseUrl() . '/invoice/' . $order->id,
        ];

        if (!empty($order->user?->email)) {
            $payload['payer_email'] = $order->user->email;
        }

        $response = Http::withBasicAuth($secretKey, '')
            ->acceptJson()
            ->asJson()
            ->post($baseUrl . '/v2/invoices', $payload);

        if (!$response->successful()) {
            throw new RuntimeException('Gagal membuat invoice Xendit: ' . $response->body());
        }

        $body = $response->json();

        return [
            'id' => (string) ($body['id'] ?? ''),
            'external_id' => (string) ($body['external_id'] ?? $order->order_number),
            'status' => strtolower((string) ($body['status'] ?? 'pending')),
            'invoice_url' => $body['invoice_url'] ?? null,
            'raw' => $body,
        ];
    }

    /**
     * Fetch invoice by external_id from Xendit.
     *
     * @return array{id:string,external_id:string,status:string,invoice_url:?string,raw:array}|null
     */
    public function getInvoiceByExternalId(string $externalId): ?array
    {
        if ($externalId === '') {
            return null;
        }

        $response = Http::withBasicAuth($this->secretKey(), '')
            ->acceptJson()
            ->get($this->baseUrl() . '/v2/invoices', [
                'external_id' => $externalId,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('Gagal mengambil invoice Xendit: ' . $response->body());
        }

        $body = $response->json();
        $invoice = is_array($body) ? ($body[0] ?? null) : null;

        if (!is_array($invoice)) {
            return null;
        }

        return [
            'id' => (string) ($invoice['id'] ?? ''),
            'external_id' => (string) ($invoice['external_id'] ?? $externalId),
            'status' => strtolower((string) ($invoice['status'] ?? 'pending')),
            'invoice_url' => $invoice['invoice_url'] ?? null,
            'raw' => $invoice,
        ];
    }
}
