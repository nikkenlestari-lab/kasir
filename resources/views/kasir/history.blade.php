<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Transaksi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            margin-top: 10px;
        }

        .empty {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>📋 Riwayat Transaksi</h1>

        <a href="{{ route('kasir.index') }}"
    style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px;">
        ← Kembali ke Kasir
    </a>

    @forelse ($transactions as $transaction)

        <div class="card">

            <div class="row">
                <strong>No. Transaksi</strong>
                <span>{{ $transaction->transaction_number }}</span>
            </div>

            <div class="row">
                <strong>Tanggal</strong>
                <span>
                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

            <div class="row">
                <strong>Kasir</strong>
                <span>{{ $transaction->cashier }}</span>
            </div>

            <div class="row">
                <strong>Metode Pembayaran</strong>
                <span>{{ $transaction->payment_method }}</span>
            </div>

            <div class="row total">
                <strong>Total</strong>
                <span>
                    Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                </span>
            </div>

            <a
                href="{{ route('kasir.struk', $transaction->id) }}"
                class="btn"
            >
                Lihat Struk
            </a>

        </div>

    @empty

        <div class="empty">
            <p>Belum ada transaksi.</p>
        </div>

    @endforelse

</div>

</body>
</html>