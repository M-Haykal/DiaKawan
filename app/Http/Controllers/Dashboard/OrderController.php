<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::simplePaginate(5);
        return view('dashboard.pages.order', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,delivered,arrived,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status order berhasil diubah menjadi ' . $request->status . '.');
    }

}
