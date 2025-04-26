<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryRequest;
use App\DataTables\CategoriesDataTable;

class CategoryController extends Controller
{
    public function index(CategoriesDataTable $dataTable)
    {
        abort_if(!Auth::user()->can('create_category'), 403);
        return $dataTable->render('admin.categories.index');
    }

    public function store(CategoryRequest $request)
    {
        abort_if(!Auth::user()->can('create_category'), 403, 'You do not have permission to create category');
        try {

            $attributes = $request->validated();
            $attributes['user_id'] = Auth::user()->id;
            $attributes['slug'] = str_replace(' ', '', $request->name);
            Category::create($attributes);
            return response(['message' => 'Category created successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function show(Category $category)
    {
        try {
            return response()->json($category);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function update(CategoryRequest $request, Category $category)
    {
        abort_if(!Auth::user()->can('edit_category'), 403, 'You do have not the permission to update category');
        try {
            $attributes = $request->validated();
            $attributes['slug'] = str_replace(' ', '', $request->name);
            $category->update($attributes);
            return response()->json(['message' => 'Category updated successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        abort_if(!Auth::user()->can('delete_category'), 403, 'You do not have permission to delete category data');
        try {

            $category = Category::withTrashed()->find($id);
            if ($category->trashed()) {
                $category->forceDelete();
                return response()->json(['message' => 'Category Deleted Permenantly']);
            }
            Category::find($id)->delete();
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function statusUpdate(Request $request)
    {
        abort_if(!Auth::user()->can('edit_category'), 403, 'You do have not the permission to update status');
        try {

            $category = Category::find($request->id);
            $category->status = $request->status;
            $category->save();
            return response()->json(['message' => 'Category status updated successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        abort_if(!Auth::user()->can('delete_category'), 403, 'You do not have permission to delete category data');
        try {

            Category::whereIn('id',  $request->ids)->delete();
            return response()->json(['message' => 'Categories deleted successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        abort_if(!Auth::user()->can('edit_category'), 403, 'You do have not the permission to update status');
        try {

            $ids = $request->ids;
            Category::whereIn('id', $ids)->update(['status' => $request->status]);
            return response()->json(['message' => 'Categories status updated successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function restoreCategory(Request $request)
    {
        abort_if(!Auth::user()->can('delete_category'), 403, 'You do not have permission to restore category data');
        try {

            $category = Category::onlyTrashed()->find($request->id);
            $category->restore();
            return response()->json(['message' => 'Category Restored Successfully']);
        } catch (Exception $e) {
            return response($e->getMessage());
        }
    }

    public function bulkRestore(Request $request)
    {
        abort_if(!Auth::user()->can('delete_category'), 403, 'You do not have permission to restore category data');
        try {
            $ids = $request->ids;
            Category::onlyTrashed()->whereIn('id', $ids)->restore();
            return response()->json(['message' => 'Category restored successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
