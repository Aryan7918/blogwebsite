<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = User::count();
        $category = Category::count();
        $post = Post::count();
        return view('admin.dashboard', compact('user', 'category', 'post'));
    }
}
