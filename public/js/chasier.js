    const products = window.products || [];

    let cart = window.heldCart || [];

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
                        '/transaksi/' + data.transaction_id + '/struk';
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