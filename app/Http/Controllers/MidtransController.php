<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');
    }

 
public function checkout(Request $request)
{
    $data = $request->validate([
        'cart' => 'required|array|min:1',
        'cart.*.code' => 'required',
        'cart.*.name' => 'required',
        'cart.*.price' => 'required|numeric|min:0',
        'cart.*.qty' => 'required|integer|min:1',
        'discount' => 'required|numeric|min:0',
        'tax' => 'required|numeric|min:0',
        'fee' => 'required|numeric|min:0',
    ]);

    $subtotal = 0;

    foreach ($data['cart'] as $item) {
        $subtotal += $item['price'] * $item['qty'];
    }

    $grandTotal = $subtotal
        - $data['discount']
        + $data['tax']
        + $data['fee'];

    if ($grandTotal < 1) {
        return response()->json([
            'message' => 'Total pembayaran tidak valid.'
        ], 422);
    }

    $orderId = 'ORDER-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => (int) round($grandTotal),
        ],
        'customer_details' => [
            'first_name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]
    ];

    $snapToken = Snap::getSnapToken($params);

    $transaction = DB::transaction(function () use (
        $data,
        $subtotal,
        $grandTotal,
        $orderId
    ) {
        $transaction = Transaction::create([
            'transaction_number' => 'TRX-' . now()->format('YmdHisv'),
            'midtrans_order_id' => $orderId,
            'subtotal' => $subtotal,
            'discount' => $data['discount'],
            'tax' => $data['tax'],
            'fee' => $data['fee'],
            'grand_total' => $grandTotal,
            'payment' => 0,
            'change' => 0,
            'payment_method' => 'Midtrans',
            'payment_status' => 'pending',
            'cashier' => auth()->user()->name,
            'customer' => null,
        ]);

        foreach ($data['cart'] as $item) {
            $transaction->details()->create([
                'product_code' => $item['code'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'subtotal' => $item['price'] * $item['qty'],
            ]);
        }

        return $transaction;
    });

    return response()->json([
        'success' => true,
        'snap_token' => $snapToken,
        'order_id' => $orderId,
        'transaction_id' => $transaction->id,
    ]);
}


public function notification(Request $request)
{
    $serverKey = config('midtrans.serverKey');

    $orderId = $request->order_id;
    $statusCode = $request->status_code;
    $grossAmount = $request->gross_amount;
    $signatureKey = $request->signature_key;

    // Verifikasi signature dari Midtrans
    $expectedSignature = hash(
        'sha512',
        $orderId . $statusCode . $grossAmount . $serverKey
    );

    if (!hash_equals($expectedSignature, (string) $signatureKey)) {
        return response()->json([
            'message' => 'Invalid signature'
        ], 403);
    }

    $transactionStatus = $request->transaction_status;

    $transaction = Transaction::where(
        'midtrans_order_id',
        $orderId
    )->first();

    if (!$transaction) {
        return response()->json([
            'message' => 'Transaction not found'
        ], 404);
    }

    if (
        $transactionStatus == 'settlement' ||
        $transactionStatus == 'capture'
    ) {
        $transaction->payment_status = 'paid';
        $transaction->payment = $transaction->grand_total;
        $transaction->change = 0;
        $transaction->payment_method = 'Midtrans';

    } elseif ($transactionStatus == 'pending') {
        $transaction->payment_status = 'pending';

    } elseif (
        $transactionStatus == 'deny' ||
        $transactionStatus == 'expire' ||
        $transactionStatus == 'cancel'
    ) {
        $transaction->payment_status = $transactionStatus;
    }

    $transaction->save();

    return response()->json([
        'message' => 'Notification handled'
    ]);
}
} 