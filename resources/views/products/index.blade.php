<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>

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
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f5f9;
        }

        .btn-edit {
            background: #f59e0b;
        }

        .btn-delete {
            background: #dc2626;
        }

        .success {
            padding: 10px;
            background: #dcfce7;
            color: #166534;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        form {
            display: inline;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>📦 Data Produk</h1>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('dashboard') }}" class="btn">
        ← Kembali ke Dashboard
    </a>

    <a href="{{ route('products.create') }}" class="btn">
        + Tambah Produk
    </a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <a
                            href="{{ route('products.edit', $product) }}"
                            class="btn btn-edit"
                        >
                            Edit
                        </a>

                        <form
                            action="{{ route('products.destroy', $product) }}"
                            method="POST"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-delete"
                                onclick="return confirm('Yakin ingin menghapus produk ini?')"
                            >
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Belum ada produk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

</body>
</html>