<?php

use Illuminate\Support\Facades\Route;
use Transave\ScolaBookstore\Http\Controllers\AddressController;
use Transave\ScolaBookstore\Http\Controllers\AuthController;
use Transave\ScolaBookstore\Http\Controllers\AuthorController;
use Transave\ScolaBookstore\Http\Controllers\OrderController;
use Transave\ScolaBookstore\Http\Controllers\PageController;
use Transave\ScolaBookstore\Http\Controllers\ResourceController;
use Transave\ScolaBookstore\Http\Controllers\UserController;
use Transave\ScolaBookstore\Http\Controllers\ArticleController;


$prefix = !empty(config('endpoints.prefix')) ? config('endpoints.prefix') : 'general';
/**
 * |
 * | General routes for Resource Controller
 * | Examples: GET:/bookstore/general/categories, GET:/bookstore/general/categories/1, POST:/bookstore/general/categories,
 * | PATCH:/bookstore/general/categories/3, DELETE:/bookstore/general/categories/2
 * |
 */

Route::prefix($prefix)->as('bookstore.')->group(function () {
    Route::get('{endpoint}', [ResourceController::class, 'index'])->name('index');
    Route::post('{endpoint}', [ResourceController::class, 'store'])->name('store');
    Route::get('{endpoint}/{id}', [ResourceController::class, 'show'])->name('show');
    Route::match(['post', 'put', 'patch'], '{endpoint}/{id}', [ResourceController::class, 'update'])->name('update');
    Route::delete('{endpoint}/{id}', [ResourceController::class, 'destroy'])->name('delete');
});

Route::as('bookstore.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('user', [AuthController::class, 'user'])->name('user');
    Route::post('resend-token', [AuthController::class, 'resendToken'])->name('resend-token');
    Route::any('logout', [AuthController::class, 'logout'])->name('logout');
    Route::put('{user}/email', [AuthController::class, 'updateEmail'])->name('update-email');
    Route::post('verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
    Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password');

    //Users Route
    Route::prefix('users')->as('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });

    // Resources Route
    Route::prefix('resources')->as('resources.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{id}', [ArticleController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('delete');

    });

    // Address Route
    Route::prefix('addresses')->as('addresses.')->group(function () {
        Route::get('/', [ AddressController::class, 'index'])->name('index');
        Route::post('/', [ AddressController::class, 'store'])->name('store');
        Route::get('/{id}', [AddressController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{id}', [AddressController::class, 'destroy'])->name('delete');

    });

    // Author Route
    Route::prefix('authors')->as('authors.')->group(function () {
        Route::get('/', [ AuthorController::class, 'index'])->name('index');
        Route::post('/', [ AuthorController::class, 'store'])->name('store');
        Route::get('/{id}', [AuthorController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [AuthorController::class, 'update'])->name('update');
        Route::delete('/{id}', [AuthorController::class, 'destroy'])->name('delete');

    });

    //Orders Route
    Route::prefix('orders')->as('orders.')->group(function (){
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [OrderController::class, 'update']); 
        Route::delete('/{id}', [OrderController::class, 'deleteOrder'])->name('delete');
    });

    //OrderItem Route
    Route::prefix('order-items')->as('order-items.')->group(function() {
        Route::delete('/{id}', [OrderController::class, 'deleteOrderItem'])->name('delete');
    });

    //Pages Route
    Route::prefix('pages')->as('pages.')->group(function() {
        Route::get('/home', [ PageController::class, 'homePage'])->name('home');
        Route::get('/resources/{id}', [ PageController::class, 'singleResource'])->name('resources.show');
        Route::get('/authors/{id}', [ PageController::class, 'singleAuthor'])->name('authors.show');
    });

});