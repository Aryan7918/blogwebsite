<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        abort_if(!Auth::user()->can('create_comment'), 403);
        try {
            $attributes = $request->all();
            $attributes['user_id'] = Auth::user()->id;
            Comment::create($attributes);
            return redirect()->back()->with('success', 'Comment created successfully');
        } catch (Exception $e) {
            return response(['message' => 'Error occur '], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attributes = $request->validate([
            'id' => 'required',
            'content' => 'required',
        ]);
        try {
            $comment = Comment::find($id);
            $comment->update($attributes);
            return response()->json(['message', 'Comment updated successfully', 'content' => $request->content]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(!Auth::user()->can('delete_comment'), 403);
        try {
            Comment::destroy($id);
            return response()->json(['message', 'Comment deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message', $e->getMessage()]);
        }
    }
}
