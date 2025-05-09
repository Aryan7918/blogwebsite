<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RegisterController;

// ->middleware('auth');

Route::get('/widgets', function () {
    return view('widgets');
});


Route::get('login', [SessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('login', [SessionController::class, 'login'])->middleware('guest');
Route::get('register', [RegisterController::class, 'create'])->middleware('guest');
Route::post('register', [RegisterController::class, 'store'])->middleware('guest');

// Route::middleware(['middleware' => ['role:admin'] )->group(function () {
Route::middleware('auth')->group(function () {
    Route::delete('logout', [SessionController::class, 'destroy'])->name('logout');

    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->withTrashed();
    Route::resource('users', UserController::class)->except('show');
    Route::resource('categories', CategoryController::class)->except('edit');
    Route::resource('posts', PostController::class);

    Route::post('categories/statusUpdate', [CategoryController::class, 'statusUpdate'])->name('categoryStatusUpdate');
    Route::post('users/statusUpdate', [UserController::class, 'statusUpdate'])->name('userStatusUpdate');
    Route::post('posts/statusUpdate', [PostController::class, 'statusUpdate'])->name('postStatusUpdate');

    // Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class)->except(['store', 'create', 'update', 'show', 'destroy']);
    Route::get('roles/addPermission/{id}', [RoleController::class, 'addPermissionToRole']);
    // Route::post('roles/addPermission/{id}', [RoleController::class, 'updatePermissionToRole']);
    Route::post('roles/givePermission', [RoleController::class, 'givePermissionToRole'])->name('givePermission');
    Route::post('users/assignRole', [UserController::class, 'assignRoleToUser'])->name('assignRoleToUser');
    Route::get('modules', [UserController::class, 'showUserPermission'])->name('modules.index');
    Route::post('users/givePermissionToUser', [UserController::class, 'givePermissionToUser'])->name('givePermissionToUser');

    //Bulk Delete
    Route::POST('categories/bulkdelete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkdelete');
    Route::POST('categories/bulkupdatestatus', [CategoryController::class, 'bulkUpdateStatus'])->name('categories.bulkUpdateStatus');

    Route::post('users/restoreUser', [UserController::class, 'restoreUser'])->name('restoreUser');
    Route::post('posts/restorePost', [PostController::class, 'restorePost'])->name('restorePost');
    Route::post('categories/restoreCategory', [CategoryController::class, 'restoreCategory'])->name('restoreCategory');

    Route::POST('users/bulkdelete', [UserController::class, 'bulkDelete'])->name('users.bulkdelete');
    Route::POST('users/bulkupdatestatus', [UserController::class, 'bulkUpdateStatus'])->name('users.bulkUpdateStatus');
    Route::POST('posts/bulkdelete', [PostController::class, 'bulkDelete'])->name('posts.bulkdelete');
    Route::POST('posts/bulkupdatestatus', [PostController::class, 'bulkUpdateStatus'])->name('posts.bulkUpdateStatus');

    Route::POST('users/bulkrestore', [UserController::class, 'bulkRestore'])->name('users.bulkrestore');
    Route::POST('categories/bulkrestore', [CategoryController::class, 'bulkRestore'])->name('categories.bulkrestore');
    Route::POST('posts/bulkrestore', [PostController::class, 'bulkRestore'])->name('posts.bulkrestore');
    Route::resource('comment', CommentController::class);
    Route::delete('comment/{id}', [CommentController::class, 'destroy']);
    Route::post('post/like', [LikeController::class, 'like'])->name('likePost');
    Route::post('post/liked', [LikeController::class, 'liked'])->name('liked');
});

Route::get('/', [PostController::class, 'allPost']);
Route::get('posts/{post:slug}', [PostController::class, 'show']);
Route::get('category/{category:slug}', [PostController::class, 'categoriesPost'])->name('category-posts');
