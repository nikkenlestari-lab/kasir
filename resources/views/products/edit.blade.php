<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button, a {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            background: #2563eb;
            color: white;
        }

        a {
            background: #6b7280;
            color: white;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>✏️ Edit Produk</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST">

        @csrf
        @method('PUT')

        <label>Kode Produk</label>
        <input
            type="text"
            name="code"
            value="{{ old('code', $product->code) }}"
        >

        <label>Nama Produk</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $product->name) }}"
        >

        <label>Harga</label>
        <input
            type="number"
            name="price"
            value="{{ old('price', $product->price) }}"
        >

        <button type="submit">Update Produk</button>

        <a href="{{ route('products.index') }}">Kembali</a>

    </form>

</div>

</body>
</html>