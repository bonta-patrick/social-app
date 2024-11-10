<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExampleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//User related routes
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');
Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"])->middleware('guest');
Route::post('/logout', [UserController::class, "logout"])->middleware('auth');
Route::get('/manage-avatar', [UserController::class, "showManageAvatarPage"])->middleware('auth');
Route::post('/manage-avatar', [UserController::class, "storeAvatar"])->middleware('auth');

//Follow related routes
Route::post('/create-follow/{user:username}', [FollowController::class, "createFollow"])->middleware('auth');
Route::post('/remove-follow/{user:username}', [FollowController::class, "removeFollow"])->middleware('auth');

//Posts related routes
Route::get('/create-post', [PostController::class, "showCreateForm"])->middleware('auth');
Route::post('/create-post', [PostController::class, "storeNewPost"])->middleware('auth');
Route::get('/post/{post}', [PostController::class, "viewSinglePost"]);
Route::delete('/post/{post}', [PostController::class, "delete"])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, "showEditForm"])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, "updatePost"])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class, "search"]);
Route::get('/post/{post}/comments', [PostController::class, "showCommentsPage"]);
Route::post('/post/{post}/comments/create-comment', [PostController::class, "createNewComment"]);

//Comments related routes
Route::get('/comment/{comment}/replies', [CommentController::class, "showRepliesPage"]);
Route::post('/comment/{comment}/replies/create-reply', [CommentController::class, "createNewReply"]);

//Profile related routes
Route::get('/profile/{user:username}', [UserController::class, "showUserProfile"]);
Route::get('/profile/{user:username}/followers', [UserController::class, "profileFollowers"]);
Route::get('/profile/{user:username}/following', [UserController::class, "profileFollowing"]);