<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\PostDataTable;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index(PostDataTable $dataTable)
    {
        abort_if(!Auth::user()->can('view_all_post'), 403);
        return $dataTable->render('admin.posts.index');
    }

    public function create()
    {
        abort_if(!Auth::user()->can('create_post'), 403);
        try {
            $categories = Category::all();
            return view('admin.posts.create', compact('categories'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(PostRequest $request)
    {
        abort_if(!Auth::user()->can('create_post'), 403);
        try {
            $attribute = $request->validated();
            unset($attribute['category']);
            $attribute['user_id'] = Auth::user()->id;
            $attribute['slug'] = str_replace(" ", "-", $request->title);
            $post = Post::create($attribute);
            $post->category()->sync($request->category);
            return redirect()->route('posts.index')->with('success', 'Post created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(string $post)
    {
        try {
            if (request('category') || request('search')) {
                $posts = Post::with(['category', 'user'])->filter(request(['search', 'category']))->get();
                $categories = Category::all(['id', 'name', 'slug']);
                return view('front.allpost', compact('posts', 'categories'));
            }
            $post = Post::with('category', 'user', 'comment')->where('slug', $post)->first();
            $categories = Category::all(['id', 'name', 'slug']);
            $likes = $post->like()->where('is_like', 1)->count();
            $dislikes = $post->like()->where('is_like', 0)->count();
            return view('front.post', compact('post', 'categories', 'likes', 'dislikes'));
        } catch (Exception $e) {
            return redirect()->route('posts.index')->with('error', 'Post not found');
        }
    }

    public function edit(string $id)
    {
        abort_if(!Auth::user()->can('edit_post'), 403);
        try {
            $post = Post::with(['category', 'user'])->findOrFail($id);
            $categories = Category::all();
            return view('admin.posts.edit', compact('post', 'categories'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Post not found');
        }
    }

    public function update(PostRequest $request, string $id)
    {
        abort_if(!Auth::user()->can('edit_post'), 403);
        try {
            $attribute = $request->validated();
            $attribute['slug'] = str_replace(" ", "-", $request->title);
            $post = Post::findOrFail($id);
            $post->category()->sync($request->category);
            unset($attribute['category']);
            $post->update($attribute);
            return redirect()->route('posts.index')->with('success', 'Post updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        abort_if(!Auth::user()->can('delete_post'), 403, 'You do not have permission to delete user data');
        $post = Post::withTrashed()->find($id);
        if ($post->trashed()) {
            $post->category()->detach();
            $post->forceDelete();
            return response()->json(['message' => 'Post Deleted Permenantly']);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
    public function statusUpdate(Request $request)
    {
        abort_if(!Auth::user()->can('edit_post'), 403);
        $post = Post::find($request->id);
        $post->status = $request->status;
        $post->save();
        return response()->json(['message' => 'Post status updated successfully']);
    }

    public function bulkDelete(Request $request)
    {
        abort_if(!Auth::user()->can('delete_all_post'), 403);
        $ids = $request->ids;
        $posts = Post::onlyTrashed()->whereIn('id', $ids)->get();
        if ($posts->count() > 0) {
            foreach ($posts as $post) {
                $post->category()->detach();
                $post->forceDelete();
            }
            return response()->json(['message' => 'Posts deleted Permenantly']);
        }
        $post = Post::whereIn('id', $ids);
        $post->delete();
        return response()->json(['message' => 'Posts deleted successfully']);
    }

    public function bulkUpdateStatus(Request $request)
    {
        abort_if(!Auth::user()->can('edit_post'), 403);
        $ids = $request->ids;
        Post::whereIn('id', $ids)->update(['status' => $request->status]);
        return response()->json(['message' => 'Posts status updated successfully']);
    }
    public function restorePost(Request $request)
    {
        abort_if(!Auth::user()->can('delete_post'), 403);
        $post = Post::onlyTrashed()->find($request->id);
        $post->restore();
        return response()->json(['message' => 'Post Restored Successfully']);
    }
    public function bulkRestore(Request $request)
    {
        abort_if(!Auth::user()->can('delete_post'), 403);
        try {
            $ids = $request->ids;
            Post::onlyTrashed()->whereIn('id', $ids)->restore();
            return response()->json(['message' => 'Posts are restored successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to restore posts'], 500);
        }
    }

    public function postView()
    {
        $post = Post::with(['category', 'user'])->first();
        $categories = Category::all(['id', 'name', 'slug']);
        return view('front.post', compact('post', 'categories'));
    }

    public function allPost()
    {
        $posts = Post::with(['category', 'user', 'comment'])->filter(request(['search', 'category']))->get();
        $categories = Category::all(['id', 'name', 'slug']);
        return view('front.allpost', compact('posts', 'categories'));
    }

    public function categoriesPost(Category $category)
    {
        $posts = Post::with('category')->whereRelation('category', 'name', $category)->filter(request(['search', 'category']))->get();
        $categories = Category::all(['id', 'name', 'slug']);
        return view('front.allpost', compact('posts', 'categories'));
    }
}
