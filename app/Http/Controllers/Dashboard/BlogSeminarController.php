<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Seminar;

class BlogSeminarController extends Controller
{
    public function index() {
        $blogs = Blog::simplePaginate(6);
        $seminars = Seminar::simplePaginate(6);
        return view('dashboard.pages.blog_seminar', compact('blogs', 'seminars'));
    }
}
