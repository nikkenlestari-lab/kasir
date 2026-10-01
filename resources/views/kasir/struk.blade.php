<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $transaction->transaction_number }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            width: 320px;
            margin: 20px auto;
            font-size: 14px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-name {
            font-weight: bold;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
        }

        .thanks {
            margin-top: 20px;
            text-align: center;
        }

        @media print {
            body {
                margin: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="center">
        <h2>TOKO RETAIL</h2>
        <p>Struk Pembayaran</p>
    </div>

    <div class="line"></div>

    <div>
        <div class="row">
            <span>No. Transaksi</span>
            <span>{{ $transaction->transaction_number }}</span>
        </div>

        <div class="row">
            <span>Kasir</span>
            <span>{{ $transaction->cashier }}</span>
        </div>

        <div class="row">
            <span>Tanggal</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="line"></div>

    @foreach ($transaction->details as $item)

        <div class="item">
            <div class="item-name">
                {{ $item->product_name }}
            </div>

            <div class="row">
                <span>
                    {{ $item->qty }} x
                    Rp {{ number_format($item->price, 0, ',', '.') }}
                </span>

                <span>
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </span>
            </div>
        </div>

    @endforeach

    <div class="line"></div>

    <div class="row">
        <span>Subtotal</span>
        <span>
            Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
        </span>
    </div>

    <div class="row">
        <span>Diskon</span>
        <span>
            Rp {{ number_format($transaction->discount, 0, ',', '.') }}
        </span>
    </div>

    <div class="row">
        <span>Pajak</span>
        <span>
            Rp {{ number_format($transaction->tax, 0, ',', '.') }}
        </span>
    </div>

    <div class="row">
        <span>Biaya Lain</span>
        <span>
            Rp {{ number_format($transaction->fee, 0, ',', '.') }}
        </span>
    </div>

    <div class="line"></div>

    <div class="row total">
        <span>TOTAL</span>
        <span>
            Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
        </span>
    </div>

    <div class="row">
        <span>Bayar</span>
        <span>
            Rp {{ number_format($transaction->payment, 0, ',', '.') }}
        </span>
    </div>

    <div class="row">
        <span>Kembali</span>
        <span>
            Rp {{ number_format($transaction->change, 0, ',', '.') }}
        </span>
    </div>

    <div class="row">
        <span>Metode</span>
        <span>{{ $transaction->payment_method }}</span>
    </div>

    <div class="thanks">
        <p>Terima kasih telah berbelanja!</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>
</html>