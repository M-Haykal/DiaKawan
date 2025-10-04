<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Avatar\Avatar;

class UserController extends Controller
{
    public function index()
    {
        $avatar = new Avatar();
        return view('user.pages.home', compact('avatar'));
    }
}
