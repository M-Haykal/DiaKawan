<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Avatar\Avatar;
use App\Models\Blog;
use App\Models\Seminar;
use App\Models\Product;

class UserController extends Controller
{
    public function index()
    {
        $avatar = new Avatar();
        return view('user.pages.home', compact('avatar'));
    }

    public function blog()
    {
        $blogs = Blog::latest()->simplePaginate(6);
        return view('user.pages.blog', compact('blogs'));
    }

    public function detailBlog($id)
    {
        $blog = Blog::findOrFail($id);
        $latestBlogs = Blog::latest()->take(3)->get();
        return view('user.pages.detail_blog', compact('blog', 'latestBlogs'));
    }

    public function seminar()
    {
        $seminars = Seminar::simplePaginate(10);
        return view('user.pages.seminar', compact('seminars'));
    }

    public function product()
    {
        $products = Product::with('category', 'nutrisionProduct')->simplePaginate(6);
        return view('user.pages.product', compact('products'));
    }
}
