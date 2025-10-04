<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NutrisionProduct;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category', 'nutrisionProduct')->simplePaginate(6);
        return view('dashboard.pages.product', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.action.product.create_product', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'video' => ['nullable', 'file', 'mimes:mp4,avi,mov', 'max:10240'],
            'images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'nutrisions' => ['nullable', 'array'],
            'nutrisions.*.name' => ['nullable', 'string', 'max:255'],
            'nutrisions.*.quantity' => ['nullable', 'integer', 'min:0'],
            'nutrisions.*.unit' => ['nullable', 'in:g,mg'],
        ]);

        $videoPath = null;
        $imagePaths = [];

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('product/videos', 'public');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('product/images', 'public');
            }
        }

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'video' => $videoPath,
            'images' => !empty($imagePaths) ? json_encode($imagePaths) : null,
        ]);

        if (!empty($validated['nutrisions'])) {
            foreach ($validated['nutrisions'] as $nutrition) {
                if (!empty($nutrition['name'])) {
                    NutrisionProduct::create([
                        'nutrision' => $nutrition['name'],
                        'content_quantity' => $nutrition['quantity'] ?? 0,
                        'unit' => $nutrition['unit'] ?? 'g',
                        'product_id' => $product->id,
                    ]);
                }
            }
        }
        return redirect()->route('products.index')->with('success', 'Produk berhasil dibuat.');
    }

    public function show($id)
    {
        $product = Product::with('nutrisionProduct')->findOrFail($id);
        $categories = Category::all();

        return view('dashboard.action.product.detail_product', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'video' => 'nullable|mimes:mp4,avi,mov|max:51200',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'nutrisions' => 'array',
            'nutrisions.*.name' => 'nullable|string',
            'nutrisions.*.quantity' => 'nullable|numeric',
            'nutrisions.*.unit' => 'nullable|string',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('video')) {
            if ($product->video && Storage::exists('public/' . $product->video)) {
                Storage::delete('public/' . $product->video);
            }
            $path = $request->file('video')->store('products/videos', 'public');
            $product->video = $path;
            $product->save();
        }

        $existingImages = json_decode($product->images, true) ?? [];

        if ($request->filled('deleted_images')) {
            $toDelete = json_decode($request->deleted_images, true);
            foreach ($toDelete as $path) {
                if (Storage::exists('public/' . $path)) {
                    Storage::delete('public/' . $path);
                }
            }

            $existingImages = array_values(array_diff($existingImages, $toDelete));
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('product/images', 'public');
                $existingImages[] = $path;
            }
        }

        $product->images = json_encode($existingImages);
        $product->save();

        if ($request->filled('deleted_images')) {
            $toDelete = json_decode($request->deleted_images, true);
            foreach ($toDelete as $path) {
                if (Storage::exists('public/' . $path)) {
                    Storage::delete('public/' . $path);
                }
            }

            $imagesArray = is_string($product->images) ? json_decode($product->images, true) : ($product->images ?? []);
            $remainingImages = array_diff($imagesArray ?? [], $toDelete);
            $product->images = json_encode(array_values($remainingImages));
            $product->save();
        }

        if ($request->has('nutrisions') && is_array($request->nutrisions)) {
            $product->nutrisionProduct()->delete();

            foreach ($request->nutrisions as $nutri) {
                $name = $nutri['name'] ?? null;
                if (empty($name))
                    continue;

                $product->nutrisionProduct()->create([
                    'nutrision' => $name,
                    'content_quantity' => $nutri['quantity'] ?? 0,
                    'unit' => $nutri['unit'] ?? 'g',
                ]);
            }
        }

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Produk berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $product = Product::with('nutrisionProduct')->findOrFail($id);

        $images = json_decode($product->images, true) ?? [];
        foreach ($images as $img) {
            if (Storage::exists('public/' . $img)) {
                Storage::delete('public/' . $img);
            }
        }

        $videos = json_decode($product->video, true) ?? [];
        foreach ($videos as $vid) {
            if (Storage::exists('public/' . $vid)) {
                Storage::delete('public/' . $vid);
            }
        }

        $product->nutrisionProduct()->delete();

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product dan semua datanya berhasil dihapus.');
    }

}
