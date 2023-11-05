<?php

use Illuminate\Support\Facades\Route;
use Transave\ScolaBookstore\Http\Controllers\AuthController;
use Transave\ScolaBookstore\Http\Controllers\BookController;
use Transave\ScolaBookstore\Http\Controllers\OrderController;
use Transave\ScolaBookstore\Http\Controllers\RestfulAPIController;
use Transave\ScolaBookstore\Http\Controllers\SearchController;
use Transave\ScolaBookstore\Http\Controllers\UserController;

$prefix = !empty(config('endpoints.prefix')) ? config('endpoints.prefix') : 'general';
/**
 * |
 * | General routes for RestFul Controller
 * | Examples: GET:/bookstore/general/categories, GET:/bookstore/general/categories/1, POST:/bookstore/general/categories,
 * | PATCH:/bookstore/general/categories/3, DELETE:/bookstore/general/categories/2
 * |
 */

Route::prefix($prefix)->as('bookstore.')->group(function () {
    Route::get('{endpoint}', [RestfulAPIController::class, 'index']);
    Route::get('{endpoint}/{id}', [RestfulAPIController::class, 'show']);
    Route::post('{endpoint}', [RestfulAPIController::class, 'store']);
    Route::match(['post', 'put', 'patch'], '{endpoint}/{id}', [RestfulAPIController::class, 'update']);
    Route::delete('{endpoint}/{id}', [RestfulAPIController::class, 'destroy']);
});

Route::prefix('general')->as('bookstore.')->group(function () {
    Route::get('categories', [SearchController::class, 'indexCategories'])->name('categories');
    Route::get('publishers', [SearchController::class, 'indexPublishers'])->name('publishers');
    Route::get('carts', [SearchController::class, 'indexCarts'])->name('carts');
    Route::get('schools', [SearchController::class, 'indexSchools'])->name('schools');
    Route::get('saves', [SearchController::class, 'indexSaves'])->name('saves');
    Route::get('orderdetails', [SearchController::class, 'indexOrderDetails'])->name('orderdetails');
});


Route::as('bookstore.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('user', [AuthController::class, 'user'])->name('user');
    Route::post('resend-token', [AuthController::class, 'resendToken'])->name('resend-token');
    Route::any('logout', [AuthController::class, 'logout'])->name('logout');
    Route::put('{user}/email', [AuthController::class, 'updateEmail'])->name('updateEmail');
    Route::post('verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
    Route::post('change-password', [AuthController::class, 'changePassword'])->name('changePassword');


    //Users Route
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });


    //Books Route
    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::post('/', [BookController::class, 'store'])->name('store');
        Route::get('/{id}', [BookController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [BookController::class, 'update'])->name('update');
        Route::delete('/{id}', [BookController::class, 'destroy'])->name('delete');

    });



    //Orders Route
    Route::prefix('orders')->group(function (){
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [OrderController::class, 'update'])->name('update');
    });
});