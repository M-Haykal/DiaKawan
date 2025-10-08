<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Avatar\Avatar;
use App\Models\Blog;
use App\Models\Seminar;
use App\Models\Product;
use App\Models\Booking;
use Auth;
use App\Models\Category;

class UserController extends Controller
{
    public function index()
    {
        $latestProducts = Product::with('category', 'nutrisionProduct')->latest()->take(6)->get();
        return view('user.pages.home', compact('latestProducts'));
    }

    public function about()
    {
        return view('user.pages.about');
    }

    public function blog(Request $request)
    {
        $query = Blog::query();

        // Search berdasarkan judul atau konten
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('content', 'LIKE', "%{$search}%");
        }

        $blogs = $query->latest()->simplePaginate(6);

        return view('user.pages.blog', compact('blogs'));
    }

    public function detailBlog($id)
    {
        $blog = Blog::findOrFail($id);
        $latestBlogs = Blog::latest()->take(3)->get();
        return view('user.pages.detail_blog', compact('blog', 'latestBlogs'));
    }

    public function seminar(Request $request)
    {
        $query = Seminar::query();

        // Search berdasarkan title atau subtitle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('subtitle', 'LIKE', "%{$search}%");
        }

        $seminars = $query->latest()->simplePaginate(6);

        return view('user.pages.seminar', compact('seminars'));
    }

    public function detailSeminar($id)
    {
        $seminar = Seminar::findOrFail($id);
        return view('user.pages.detail_seminar', compact('seminar'));
    }

    public function product(Request $request)
    {
        $query = Product::with('category', 'nutrisionProduct');
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->simplePaginate(6);
        $categories = Category::all();
        return view('user.pages.product', compact('products', 'categories'));
    }

    public function detailProduct($id)
    {
        $product = Product::with('nutrisionProduct')->findOrFail($id);
        return view('user.pages.detail_product', compact('product'));
    }

    public function konsultasi()
    {
        return view('user.pages.konsultansi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'location' => 'required|in:online,offline',
            'note' => 'nullable|string|max:500',
        ], [
            'meet_link.required_if' => 'Link meeting wajib diisi untuk konsultasi online.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
        ]);

        Booking::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'location' => $request->location,
            'note' => $request->note,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('user.home')
            ->with('success', 'Booking konsultasi berhasil! Tim kami akan menghubungi Anda.');
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->simplePaginate(5);
        return view('user.pages.order', compact('orders'));
    }
}
