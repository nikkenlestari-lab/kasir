<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .total { font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE</h2>
        <p>No: {{ $transaction->transaction_number }}</p>
        <p>Tanggal: {{ $transaction->created_at->format('d/m/Y') }}</p>
        <p>Kasir: {{ $transaction->cashier }}</p>
        <p>Metode Pembayaran: {{ $transaction->payment_method }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total">Subtotal:</td>
                <td>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Diskon:</td>
                <td>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Pajak:</td>
                <td>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Biaya:</td>
                <td>Rp {{ number_format($transaction->fee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Grand Total:</td>
                <td class="total">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Bayar:</td>
                <td>Rp {{ number_format($transaction->payment, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Kembalian:</td>
                <td>Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
