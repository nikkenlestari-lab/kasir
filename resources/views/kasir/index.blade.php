<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Transaksi Kasir</title>
    <link rel="stylesheet" href="{{ url(asset('css/chasier.css')) }}">
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>🛒 Transaksi Kasir</h1>
            <p>Halaman Pembayaran</p>
        </div>

        <div class="transaction-info">
            <div>No. Transaksi: TRX-001</div>
            <div id="tanggal"></div>
        </div>
        </div>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('kasir.held') }}"
           style="padding: 10px 15px; background: #f59e0b; color: white; text-decoration: none; border-radius: 6px;">
            🕒 Transaksi Ditahan
        </a>

        <a href="{{ route('kasir.history') }}"
           style="padding: 10px 15px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; margin-left: 10px;">
            📋 Riwayat Transaksi
        </a>
    </div>

    <div class="main">

        <!-- BAGIAN KIRI -->
        <div class="card">

            <div class="search-box">
                <input
                    type="text"
                    id="search"
                    placeholder="Cari nama produk / kode / barcode..."
                >

                <button class="btn btn-search">
                    Cari
                </button>
            </div>

            <h2 class="cart-title">Keranjang Belanja</h2>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="cart">
                    <tr>
                        <td colspan="5" class="empty">
                            Belum ada produk di keranjang
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <!-- BAGIAN KANAN -->
        <div class="card">

            <h2 class="summary-title">
                Ringkasan Pembayaran
            </h2>

            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subtotal">Rp 0</span>
            </div>

            <div class="summary-row">
    <span>Diskon</span>
    <input
        type="number"
        id="discountInput"
        value="0"
        min="0"
        style="width: 100px; padding: 6px;"
    >
        </div>

        <div class="summary-row">
            <span>Pajak (%)</span>
            <input
                type="number"
                id="taxInput"
                value="0"
                min="0"
                style="width: 100px; padding: 6px;"
            >
        </div>

        <div class="summary-row">
            <span>Biaya Lainnya</span>
            <input
                type="number"
                id="feeInput"
                value="0"
                min="0"
                style="width: 100px; padding: 6px;"
            >
        </div>

            <div class="summary-row total">
                <span>Grand Total</span>
                <span id="grandTotal">Rp 0</span>
            </div>

            <label>
                Pembayaran
            </label>

            <input
                type="number"
                id="payment"
                class="payment-input"
                placeholder="Masukkan jumlah pembayaran"
            >

            <div class="quick-payment">
            <button type="button" onclick="setPayment(10000)">Rp 10.000</button>
            <button type="button" onclick="setPayment(20000)">Rp 20.000</button>
            <button type="button" onclick="setPayment(50000)">Rp 50.000</button>
            <button type="button" onclick="setPayment(100000)">Rp 100.000</button>
            <button type="button" onclick="setPaymentExact()">Uang Pas</button>
        </div>

            <div class="change">
                <div>Kembalian</div>
                <strong id="change">Rp 0</strong>
            </div>

            <div class="payment-status">
            <div>Status Pembayaran</div>
            <strong id="paymentStatus">-</strong>
            </div>

            <h3 style="margin-bottom: 10px;">
                Metode Pembayaran
            </h3>

            <div class="payment-method">
                <button class="active">Tunai</button>
                <button>QRIS</button>
                <button>Debit</button>
                <button>Kredit</button>
                <button>E-Wallet</button>
                <button>Transfer</button>

            </div>

            <div class="actions">
                <button class="btn btn-pay">
                    💳 Bayar & Cetak
                </button>

                <button class="btn btn-hold">
                    ⏸ Hold
                </button>

                <button class="btn btn-cancel">
                    ✖ Batal
                </button>
            </div>

        </div>

    </div>

</div>

<script>
    window.products = @json($products);
    window.heldCart = @json($heldCart ?? []);
</script>

<script src="{{ url(asset('js/chasier.js')) }}"></script>

</body>
</html>