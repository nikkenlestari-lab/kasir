<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class KasirController extends Controller
{
    public function index()
{
    $products = Product::latest()->get();

    return view('kasir.index', compact('products'));
}

    public function struk($id)
{
    $transaction = Transaction::with('details')->findOrFail($id);

    return view('kasir.struk', compact('transaction'));
}

public function hold(Request $request)
{
    $data = $request->validate([
        'cart' => 'required|array|min:1',
        'subtotal' => 'required|numeric',
        'discount' => 'required|numeric',
        'tax' => 'required|numeric',
        'fee' => 'required|numeric',
        'grand_total' => 'required|numeric',
    ]);

    $held = \App\Models\HeldTransaction::create([
        'transaction_number' => 'HOLD-' . time(),
        'cart' => $data['cart'],
        'subtotal' => $data['subtotal'],
        'discount' => $data['discount'],
        'tax' => $data['tax'],
        'fee' => $data['fee'],
        'grand_total' => $data['grand_total'],
        'cashier' => auth()->user()->name,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Transaksi berhasil ditahan!',
        'held_id' => $held->id,
    ]);
}

public function held()
{
    $heldTransactions = \App\Models\HeldTransaction::latest()->get();

    return view('kasir.held', compact('heldTransactions'));
}
   public function store(Request $request)
{
    $data = $request->validate([
        'cart' => 'required|array|min:1',
        'cart.*.code' => 'required',
        'cart.*.name' => 'required',
        'cart.*.price' => 'required|numeric',
        'cart.*.qty' => 'required|integer|min:1',
        'subtotal' => 'required|numeric',
        'discount' => 'required|numeric',
        'tax' => 'required|numeric',
        'fee' => 'required|numeric',
        'grand_total' => 'required|numeric',
        'payment' => 'required|numeric',
        'change' => 'required|numeric',
        'payment_method' => 'required',
    ]);

    $transaction = DB::transaction(function () use ($data) {

        $transaction = Transaction::create([
            'transaction_number' => 'TRX-' . now()->format('YmdHisv'),
            'subtotal' => $data['subtotal'],
            'discount' => $data['discount'],
            'tax' => $data['tax'],
            'fee' => $data['fee'],
            'grand_total' => $data['grand_total'],
            'payment' => $data['payment'],
            'change' => $data['change'],
            'payment_method' => $data['payment_method'],
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
        'message' => 'Pembayaran berhasil disimpan!',
        'transaction_id' => $transaction->id,
    ]);
}
public function continueHeld($id)
{
    $held = \App\Models\HeldTransaction::findOrFail($id);

    $cart = $held->cart;

    $held->delete();

    $products = \App\Models\Product::all();

    return view('kasir.index', [
        'products' => $products,
        'heldCart' => $cart,
    ]);
}

public function history()
{
    $transactions = Transaction::latest()->get();

    return view('kasir.history', compact('transactions'));
}

}