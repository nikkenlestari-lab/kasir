<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    // Menampilkan daftar produk
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    // Menampilkan form tambah produk
    public function create()
    {
        return view('products.create');
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:255|unique:products,code',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImage(
                $request->file('image')
            );
        }

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Menampilkan form edit produk
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Memperbarui data produk
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Jika pengguna mengunggah gambar baru
        if ($request->hasFile('image')) {
            $oldImage = $product->image;

            // Simpan gambar baru
            $data['image'] = $this->saveImage(
                $request->file('image')
            );

            // Hapus gambar lama setelah gambar baru tersimpan
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        // Jika tidak ada gambar baru, gambar lama tetap digunakan
        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    // Menghapus produk
    public function destroy(Product $product)
    {
        // Hapus gambar produk jika ada
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus data produk
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    // Memproses dan menyimpan gambar
    private function saveImage($file)
    {
        $ext = strtolower($file->getClientOriginalExtension());

        // PNG tetap PNG, format lainnya disimpan sebagai JPG
        $outputExt = ($ext === 'png') ? 'png' : 'jpg';

        $filename = uniqid('product_') . '.' . $outputExt;
        $path = 'products/' . $filename;

        $manager = new ImageManager(new Driver());

        $image = $manager->decodePath($file->getRealPath())
            ->cover(800, 800);

        if ($outputExt === 'png') {
            $encoded = $image->encodeUsingFileExtension(
                'png',
                quality: 90
            );
        } else {
            $encoded = $image->encodeUsingFileExtension(
                'jpg',
                quality: 85
            );
        }

        Storage::disk('public')->put(
            $path,
            $encoded->toString()
        );

        return $path;
    }
}