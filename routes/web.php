<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;

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
Route::get('/auth/signup',[AuthController::class,'signup']);
Route::post('/auth/registration',[AuthController::class,'registration']);
Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/authenticate', [AuthController::class, 'authenticate']);
Route::get('/auth/logout', [AuthController::class, 'logout']);

Route::get('/', function () {
    return view('layout');
});

Route::get('/about', function () {
    return view('main.about');
});

Route::get('/contacts', function () {
    $data = [
        'city' => 'Moscow',
        'street' => 'Semenovskaya',
        'house' => 38,
    ];
    return view('main.contact', ['data'=>$data]);
});

Route::get('/',[MainController::class, 'index']);

Route::get('/galary/{img}', function($img){
    return view("main.galary",['img'=>$img]);
});

// Article
Route::resource('articles', ArticleController::class)->middleware('auth:sanctum');

// Comment
Route::controller(CommentController::class)->prefix('/comment')->middleware('auth:sanctum')->group(function() {
    Route::post('','store');
    Route::get('/{id}/edit', 'edit');
    Route::post('/{comment}/update','update');
    Route::get('/{comment}/delete',  'destroy');
    Route::get('/index', 'index')->name('comments.index');
    Route::get('/{comment}/accept', 'accept')->name('comment.accept');
    Route::get('/{comment}/reject', 'reject')->name('comment.reject');
});

