<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // Ambil atau buat cart baru
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $product = Product::findOrFail($request->product_id);

        // Cek apakah produk sudah ada di cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        return response()->json(['message' => 'Produk ditambahkan ke keranjang']);
    }

    // Lihat isi cart
    public function viewCart()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        return view('user.cart', compact('cart'));
    }

    // Hapus item dari cart
    public function removeItem($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item dihapus dari keranjang');
    }
}
