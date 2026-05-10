<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handleCallback(Request $request)
    {
        $payload = $request->all();
        $expectedToken = (string) config('xendit.webhook_token');
        $receivedToken = (string) $request->header('x-callback-token', '');

        if ($expectedToken !== '' && !hash_equals($expectedToken, $receivedToken)) {
            Log::warning('Xendit webhook unauthorized token', [
                'received_token_prefix' => substr($receivedToken, 0, 6),
                'has_expected_token' => $expectedToken !== '',
            ]);
            return response()->json(['success' => false, 'message' => 'Unauthorized callback token'], 401);
        }

        // Support both payload styles:
        // 1) direct invoice payload: { external_id, status, id, ... }
        // 2) wrapped event payload: { event, data: { external_id, status, id, ... } }
        $externalId = (string) ($request->input('external_id') ?? $request->input('data.external_id') ?? '');
        $xenditInvoiceId = (string) ($request->input('id') ?? $request->input('data.id') ?? '');

        $incomingStatus = strtolower((string) ($request->input('status') ?? $request->input('data.status') ?? ''));
        if ($incomingStatus === '') {
            $event = strtolower((string) $request->input('event', ''));
            $incomingStatus = match ($event) {
                'invoice.paid' => 'paid',
                'invoice.expired' => 'expired',
                default => 'pending',
            };
        }

        if ($externalId === '' && $xenditInvoiceId !== '') {
            $transaction = Transaction::whereRaw("gateway_response->>'id' = ?", [$xenditInvoiceId])
                ->with('order')
                ->first();
        } else {
            $transaction = Transaction::where('transaction_code', $externalId)
                ->with('order')
                ->first();
        }

        if ($externalId === '') {
            Log::warning('Xendit webhook missing external_id', [
                'payload_keys' => array_keys($payload),
                'event' => $request->input('event'),
            ]);
            return response()->json(['success' => false, 'message' => 'external_id wajib ada'], 422);
        }
        if (!$transaction) {
            Log::warning('Xendit webhook transaction not found', [
                'external_id' => $externalId,
                'xendit_invoice_id' => $xenditInvoiceId,
            ]);
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        DB::transaction(function () use ($transaction, $incomingStatus, $payload) {
            $update = [
                'transaction_status' => $incomingStatus,
                'gateway_response' => $payload,
            ];

            if (in_array($incomingStatus, ['paid', 'settled'], true)) {
                $update['paid_at'] = now();
            }

            $transaction->update($update);

            if (in_array($incomingStatus, ['paid', 'settled'], true) && $transaction->order) {
                $transaction->order->update([
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);
            }
        });

        Log::info('Xendit webhook processed', [
            'transaction_id' => $transaction->id,
            'transaction_code' => $transaction->transaction_code,
            'status' => $incomingStatus,
        ]);

        return response()->json(['success' => true]);
    }
}
