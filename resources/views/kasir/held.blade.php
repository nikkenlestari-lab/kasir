<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transaksi Ditahan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
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

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
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

    <h1>🕒 Transaksi Ditahan</h1>

        <a href="{{ route('kasir.index') }}"
    style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px;">
        ← Kembali ke Kasir
    </a>

    @forelse ($heldTransactions as $transaction)

        <div class="card">

            <div class="row">
                <strong>No. Transaksi</strong>
                <span>{{ $transaction->transaction_number }}</span>
            </div>

            <div class="row">
                <strong>Jumlah Item</strong>
                <span>{{ count($transaction->cart) }} item</span>
            </div>

            <div class="row">
                <strong>Total</strong>
                <span>
                    Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                </span>
            </div>

            <div class="row">
                <strong>Kasir</strong>
                <span>{{ $transaction->cashier }}</span>
            </div>

            <div class="row">
                <strong>Ditahan</strong>
                <span>
                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

            <a href="{{ route('kasir.continueHeld', $transaction->id) }}" class="btn">
    Lanjutkan
</a>

        </div>

    @empty

        <div class="empty">
            <p>Belum ada transaksi yang ditahan.</p>
        </div>

    @endforelse

</div>

</body>
</html