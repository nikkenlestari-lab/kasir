<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Transaksi Kasir</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f8;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: auto;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .transaction-info {
            text-align: right;
            color: #666;
            font-size: 14px;
        }

        .main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-box input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

        .btn {
            border: none;
            padding: 12px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-search {
            background: #2563eb;
            color: white;
        }

        .cart-title {
            margin-bottom: 15px;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f8f9fa;
        }

        .empty {
            text-align: center;
            padding: 50px 10px;
            color: #888;
        }

        .summary-title {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .summary-row.total {
            border-top: 2px solid #ddd;
            padding-top: 15px;
            font-size: 20px;
            font-weight: bold;
        }

        .payment-input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 18px;
            margin-top: 8px;
            margin-bottom: 15px;
        }

        .change {
            background: #ecfdf5;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .payment-method {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .payment-method button {
            padding: 12px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .payment-method button.active {
            background: #2563eb;
            color: white;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-pay {
            grid-column: span 2;
            background: #16a34a;
            color: white;
        }

        .btn-hold {
            background: #f59e0b;
            color: white;
        }

        .btn-cancel {
            background: #dc2626;
            color: white;
        }

        @media (max-width: 900px) {
            .main {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .transaction-info {
                text-align: left;
            }
        }
    </style>
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
            <div>Kasir: Admin</div>
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
    const products = [
        {
            id: 1,
            code: 'P001',
            name: 'Indomie Goreng',
            price: 3500
        },
        {
            id: 2,
            code: 'P002',
            name: 'Aqua 600ml',
            price: 4000
        },
        {
            id: 3,
            code: 'P003',
            name: 'Teh Botol Sosro',
            price: 5000
        },
        {
            id: 4,
            code: 'P004',
            name: 'Beras 5 Kg',
            price: 75000
        },
        {
            id: 5,
            code: 'P005',
            name: 'Minyak Goreng 1 Liter',
            price: 18000
        },
        {
            id: 6,
            code: 'P006',
            name: 'Gula Pasir 1 Kg',
            price: 17000
        }
    ];

    let cart = @json($heldCart ?? []);

    function rupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function searchProduct() {
        const keyword = document
            .getElementById('search')
            .value
            .toLowerCase()
            .trim();

        if (!keyword) {
            alert('Masukkan nama atau kode produk!');
            return;
        }

        const product = products.find(item =>
            item.name.toLowerCase().includes(keyword) ||
            item.code.toLowerCase().includes(keyword)
        );

        if (!product) {
            alert('Produk tidak ditemukan!');
            return;
        }

        const existingItem = cart.find(item => item.code === product.code);

        if (existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({
                id: product.id,
                code: product.code,
                name: product.name,
                price: product.price,
                qty: 1
            });
        }

        renderCart();

        document.getElementById('search').value = '';
    }

    function addToCart(product) {
        const existing = cart.find(item => item.id === product.id);

        if (existing) {
            existing.qty++;
        } else {
            cart.push({
                ...product,
                qty: 1
            });
        }

        renderCart();
    }

    function renderCart() {
        const cartElement = document.getElementById('cart');

        if (cart.length === 0) {
            cartElement.innerHTML = `
                <tr>
                    <td colspan="5" class="empty">
                        Belum ada produk di keranjang
                    </td>
                </tr>
            `;

            calculateTotal();
            return;
        }

        cartElement.innerHTML = cart.map(item => `
            <tr>
                <td>
                    <strong>${item.name}</strong><br>
                    <small>${item.code}</small>
                </td>

                <td>${rupiah(item.price)}</td>

                <td>
                    <button onclick="changeQty(${item.id}, -1)">−</button>
                    <strong>${item.qty}</strong>
                    <button onclick="changeQty(${item.id}, 1)">+</button>
                </td>

                <td>
                    ${rupiah(item.price * item.qty)}
                </td>

                <td>
                    <button onclick="removeItem(${item.id})">
                        🗑
                    </button>
                </td>
            </tr>
        `).join('');

        calculateTotal();
    }

    function changeQty(id, amount) {
        const item = cart.find(item => item.id === id);

        if (!item) return;

        item.qty += amount;

        if (item.qty <= 0) {
            cart = cart.filter(item => item.id !== id);
        }

        renderCart();
    }

    function removeItem(id) {
        cart = cart.filter(item => item.id !== id);

        renderCart();
    }

    function calculateTotal() {
        let subtotal = 0;

        cart.forEach(item => {
            subtotal += item.price * item.qty;
        });

        const discount =
            Number(document.getElementById('discountInput').value) || 0;

        const taxPercent =
            Number(document.getElementById('taxInput').value) || 0;

        const fee =
            Number(document.getElementById('feeInput').value) || 0;

        const tax = subtotal * taxPercent / 100;

        const grandTotal =
            subtotal - discount + tax + fee;

        document.getElementById('subtotal').innerText =
            rupiah(subtotal);

        document.getElementById('grandTotal').innerText =
            rupiah(grandTotal);

        calculateChange(grandTotal);
    }

    function calculateChange(grandTotal) {
        const payment =
            Number(document.getElementById('payment').value) || 0;

        const change = payment - grandTotal;
        const status = document.getElementById('paymentStatus');

        if (payment === 0) {
            document.getElementById('change').innerText = 'Rp 0';
            status.innerText = '-';
        } else if (payment < grandTotal) {
            document.getElementById('change').innerText = 'Rp 0';
            status.innerText = '⚠ Uang Kurang';
        } else {
            document.getElementById('change').innerText =
                rupiah(change);

            status.innerText = '✓ Pembayaran Cukup';
        }
    }

    document
        .querySelector('.btn-search')
        .addEventListener('click', searchProduct);

    document
        .getElementById('search')
        .addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                searchProduct();
            }
        });

    document
        .getElementById('payment')
        .addEventListener('input', function() {
            calculateTotal();
        });

    document.getElementById('tanggal').innerText =
        new Date().toLocaleDateString('id-ID');

    document
        .getElementById('discountInput')
        .addEventListener('input', calculateTotal);

    document
        .getElementById('taxInput')
        .addEventListener('input', calculateTotal);

    document
        .getElementById('feeInput')
        .addEventListener('input', calculateTotal);

    const paymentMethods =
        document.querySelectorAll('.payment-method button');

    paymentMethods.forEach(button => {
        button.addEventListener('click', function() {
            paymentMethods.forEach(btn => {
                btn.classList.remove('active');
            });

            this.classList.add('active');
        });
    });

    document
        .querySelector('.btn-pay')
        .addEventListener('click', function() {

            const payment =
                Number(document.getElementById('payment').value) || 0;

            let subtotal = 0;

            cart.forEach(item => {
                subtotal += item.price * item.qty;
            });

            const discount =
                Number(document.getElementById('discountInput').value) || 0;

            const taxPercent =
                Number(document.getElementById('taxInput').value) || 0;

            const fee =
                Number(document.getElementById('feeInput').value) || 0;

            const tax = subtotal * taxPercent / 100;

            const grandTotal =
                subtotal - discount + tax + fee;

            const change = payment - grandTotal;

            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            if (payment < grandTotal) {
                alert('Pembayaran belum cukup!');
                return;
            }

            const activeMethod =
                document.querySelector('.payment-method button.active');

            const paymentMethod =
                activeMethod ? activeMethod.innerText : 'Tunai';

            fetch('/transaksi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({
                    cart: cart,
                    subtotal: subtotal,
                    discount: discount,
                    tax: tax,
                    fee: fee,
                    grand_total: grandTotal,
                    payment: payment,
                    change: change,
                    payment_method: paymentMethod
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);

                    window.location.href =
                        '/struk/' + data.transaction_id;
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat menyimpan transaksi.');
            });
        });

    document
        .querySelector('.btn-hold')
        .addEventListener('click', function() {

            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            let subtotal = 0;

            cart.forEach(item => {
                subtotal += item.price * item.qty;
            });

            const discount =
                Number(document.getElementById('discountInput').value) || 0;

            const taxPercent =
                Number(document.getElementById('taxInput').value) || 0;

            const fee =
                Number(document.getElementById('feeInput').value) || 0;

            const tax = subtotal * taxPercent / 100;

            const grandTotal =
                subtotal - discount + tax + fee;

            fetch('/transaksi/hold', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({
                    cart: cart,
                    subtotal: subtotal,
                    discount: discount,
                    tax: tax,
                    fee: fee,
                    grand_total: grandTotal
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);

                    cart = [];
                    renderCart();

                    document.getElementById('payment').value = '';
                    document.getElementById('change').innerText = 'Rp 0';
                    document.getElementById('paymentStatus').innerText = '-';
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat menahan transaksi.');
            });
        });

    document
        .querySelector('.btn-cancel')
        .addEventListener('click', function() {

            if (cart.length === 0) {
                alert('Keranjang sudah kosong!');
                return;
            }

            const confirmCancel =
                confirm('Yakin ingin membatalkan transaksi?');

            if (confirmCancel) {
                cart = [];
                renderCart();

                document.getElementById('payment').value = '';
                document.getElementById('change').innerText = 'Rp 0';
                document.getElementById('paymentStatus').innerText = '-';

                alert('Transaksi berhasil dibatalkan!');
            }
        });

    function setPayment(amount) {
        document.getElementById('payment').value = amount;
        calculateTotal();
    }

    function setPaymentExact() {
        let subtotal = 0;

        cart.forEach(item => {
            subtotal += item.price * item.qty;
        });

        const discount =
            Number(document.getElementById('discountInput').value) || 0;

        const taxPercent =
            Number(document.getElementById('taxInput').value) || 0;

        const fee =
            Number(document.getElementById('feeInput').value) || 0;

        const tax = subtotal * taxPercent / 100;

        const grandTotal =
            subtotal - discount + tax + fee;

        document.getElementById('payment').value = grandTotal;

        calculateTotal();
    }

    renderCart();
</script>

</body>
</html>