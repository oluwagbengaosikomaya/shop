<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->sort,   fn($q) => $q->orderBy($request->sort, 'asc'))
            ->when(!$request->sort,  fn($q) => $q->latest())
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $uploaded = cloudinary()->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'shop/products']
            );
            $data['image'] = $uploaded['secure_url'];
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteOldImage($product->image);
            $uploaded = cloudinary()->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'shop/products']
            );
            $data['image'] = $uploaded['secure_url'];
        } elseif ($request->boolean('remove_image')) {
            $this->deleteOldImage($product->image);
            $data['image'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->deleteOldImage($product->image);
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    private function deleteOldImage(?string $imagePath): void
    {
        if (!$imagePath || !str_contains($imagePath, 'res.cloudinary.com')) {
            return; // Don't delete seeded/static assets
        }

        // Extract public_id from the URL and destroy it
        preg_match('/shop\/products\/[^.]+/', $imagePath, $matches);
        if ($matches) {
            cloudinary()->uploadApi()->destroy($matches[0]);
        }
    }
}
