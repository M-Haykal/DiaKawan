<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Auth;
use App\Models\Transaction;
use App\Models\OrderItem;
use App\Models\Order;
use Midtrans\Snap;
use Midtrans\Config;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->first();

        $items = $cart ? $cart->items : collect();
        $total = $items->sum(fn($item) => $item->price * $item->quantity);

        return view('user.pages.cart', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
                'price' => $product->price,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove($itemId)
    {
        $item = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($itemId);
        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min=1']);

        $item = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($itemId);
        $item->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'payment' => 'required|in:cash,transfer,ewallet,midtrans',
        ]);

        $user = auth()->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $total = $cart->items->sum(fn($item) => $item->price * $item->quantity);

        // Buat Order
        $order = Order::create([
            'address' => $request->address,
            'phone' => $request->phone,
            'total_price' => $total,
            'note_order' => $request->note_order,
            'payment' => $request->payment,
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        // Simpan Order Items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Kosongkan keranjang
        $cart->items()->delete();

        // Jika pilih Midtrans → redirect ke halaman pembayaran
        if ($request->payment === 'midtrans') {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $itemDetails = [];
            foreach ($cart->items as $item) {
                $itemDetails[] = [
                    'id' => $item->product->id,
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . now()->format('YmdHis'),
                    'gross_amount' => (int) $total,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $request->phone,
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);

            Transaction::create([
                'order_id' => $order->id,
                'transaction_id' => $params['transaction_details']['order_id'],
                'payment_type' => 'midtrans',
                'payload' => json_encode($params),
            ]);

            return view('user.pages.payment', compact('snapToken', 'order'));
        }

        // Jika bukan Midtrans → langsung sukses
        return redirect()->route('order.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');
    }
}
