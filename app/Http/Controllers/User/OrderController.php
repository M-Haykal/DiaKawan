<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Auth;
use Midtrans\Snap;
use Midtrans\Config;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $carts = Cart::with('items.product')->where('user_id', $user->id)->first();
        return view('user.pages.order', compact('carts'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        // Buat order
        $order = $user->orders()->create([
            'address' => $request->address,
            'phone' => $request->phone,
            'total_price' => $cart->items->sum(fn($item) => $item->quantity * $item->price),
            'note_order' => $request->note_order,
            'payment' => $request->payment ?? 'cash',
            'status' => 'pending',
        ]);

        // Pindahkan item dari cart ke order_items
        foreach ($cart->items as $item) {
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Hapus cart setelah checkout
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat');
    }

    public function directOrder(Request $request, $id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string',
            'phone' => 'required|string',
            'payment' => 'required|in:cash,transfer,ewallet,midtrans',
        ]);

        $quantity = $validated['quantity'];
        $totalPrice = $product->price * $quantity;

        $order = Order::create([
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'total_price' => $totalPrice,
            'note_order' => $request->note_order,
            'payment' => $validated['payment'],
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        $order->orderItems()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        // Jika Midtrans → proses pembayaran
        if ($validated['payment'] === 'midtrans') {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . now()->format('YmdHis'),
                    'gross_amount' => (int) $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $validated['phone'],
                ],
                'item_details' => [
                    [
                        'id' => $product->id,
                        'price' => (int) $product->price,
                        'quantity' => $quantity,
                        'name' => $product->name,
                    ]
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $order->transaction()->create([
                'transaction_id' => $params['transaction_details']['order_id'],
                'payment_type' => 'midtrans',
                'payload' => json_encode($params),
            ]);

            return view('user.pages.payment', compact('snapToken', 'order'));
        }

        return redirect()->back()->with('success', 'Pesanan berhasil dibuat!');
    }

    public function success($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('user.pages.order-success', compact('order'));
    }

    public function pending($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('user.pages.order-pending', compact('order'));
    }
}
