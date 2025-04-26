<?php

namespace App\Http\Controllers;

use App\DataTables\TagsDataTable;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index(TagsDataTable $dataTable)
    {
        abort_if(!Auth::user()->can('create_Tag'), 403);
        return $dataTable->render('admin.categories.index');
    }

    public function store(TagRequest $request)
    {
        abort_if(!Auth::user()->can('create_Tag'), 403, 'You do not have permission to create Tag');
        try {

            $attributes = $request->validated();
            $attributes['user_id'] = Auth::user()->id;
            $attributes['slug'] = str_replace(' ', '', $request->name);
            Tag::create($attributes);
            return response(['message' => 'Tag created successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function show(Tag $Tag)
    {
        try {
            return response()->json($Tag);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function update(TagRequest $request, Tag $Tag)
    {
        abort_if(!Auth::user()->can('edit_Tag'), 403, 'You do have not the permission to update Tag');
        try {
            $attributes = $request->validated();
            $attributes['slug'] = str_replace(' ', '', $request->name);
            $Tag->update($attributes);
            return response()->json(['message' => 'Tag updated successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        abort_if(!Auth::user()->can('delete_Tag'), 403, 'You do not have permission to delete Tag data');
        try {

            $Tag = Tag::withTrashed()->find($id);
            if ($Tag->trashed()) {
                $Tag->forceDelete();
                return response()->json(['message' => 'Tag Deleted Permenantly']);
            }
            Tag::find($id)->delete();
            return response()->json(['message' => 'Tag deleted successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function statusUpdate(Request $request)
    {
        abort_if(!Auth::user()->can('edit_Tag'), 403, 'You do have not the permission to update status');
        try {

            $Tag = Tag::find($request->id);
            $Tag->status = $request->status;
            $Tag->save();
            return response()->json(['message' => 'Tag status updated successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        abort_if(!Auth::user()->can('delete_Tag'), 403, 'You do not have permission to delete Tag data');
        try {

            Tag::whereIn('id',  $request->ids)->delete();
            return response()->json(['message' => 'Categories deleted successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        abort_if(!Auth::user()->can('edit_Tag'), 403, 'You do have not the permission to update status');
        try {

            $ids = $request->ids;
            Tag::whereIn('id', $ids)->update(['status' => $request->status]);
            return response()->json(['message' => 'Categories status updated successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function restoreTag(Request $request)
    {
        abort_if(!Auth::user()->can('delete_Tag'), 403, 'You do not have permission to restore Tag data');
        try {

            $Tag = Tag::onlyTrashed()->find($request->id);
            $Tag->restore();
            return response()->json(['message' => 'Tag Restored Successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function bulkRestore(Request $request)
    {
        abort_if(!Auth::user()->can('delete_Tag'), 403, 'You do not have permission to restore Tag data');
        try {
            $ids = $request->ids;
            Tag::onlyTrashed()->whereIn('id', $ids)->restore();
            return response()->json(['message' => 'Tag restored successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
