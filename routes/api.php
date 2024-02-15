<?php

use Illuminate\Support\Facades\Route;
use Transave\ScolaBookstore\Http\Controllers\AddressController;
use Transave\ScolaBookstore\Http\Controllers\AuthController;
use Transave\ScolaBookstore\Http\Controllers\AuthorController;
use Transave\ScolaBookstore\Http\Controllers\OrderController;
use Transave\ScolaBookstore\Http\Controllers\ResourceController;
use Transave\ScolaBookstore\Http\Controllers\UserController;
use Transave\ScolaBookstore\Http\Controllers\ArticleController;


// use Transave\ScolaBookstore\Http\Controllers\RestfulAPIController;
// use Transave\ScolaBookstore\Http\Controllers\SearchController;

$prefix = !empty(config('endpoints.prefix')) ? config('endpoints.prefix') : 'general';
/**
 * |
 * | General routes for Resource Controller
 * | Examples: GET:/bookstore/general/categories, GET:/bookstore/general/categories/1, POST:/bookstore/general/categories,
 * | PATCH:/bookstore/general/categories/3, DELETE:/bookstore/general/categories/2
 * |
 */

Route::prefix($prefix)->as('bookstore.')->group(function () {
    Route::get('{endpoint}', [ResourceController::class, 'index']);
    Route::post('{endpoint}', [ResourceController::class, 'store']);
    Route::get('{endpoint}/{id}', [ResourceController::class, 'show']);
    Route::match(['post', 'put', 'patch'], '{endpoint}/{id}', [ResourceController::class, 'update']);
    Route::delete('{endpoint}/{id}', [ResourceController::class, 'destroy']);
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
        Route::patch('change-role/{id}',  [UserController::class, 'changeRole'])->name('change-role'); 
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });

//        //Carts Route
//    Route::prefix('carts')->group(function () {
//        Route::get('/', [ CartController::class, 'index']);
//        Route::post('/', [CartController::class, 'store']);
//        Route::get('/{userId}', [CartController::class, 'show']);
//        Route::delete('/{cartItemId}', [CartController::class, 'removeItem'])->name('removeItem');
//        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [CartController::class, 'update']);
//        Route::delete('clear/{userId}', [CartController::class, 'clearCart'])->name('clear');
//
//    });

    // Resources Route
    Route::prefix('resources')->group(function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::post('/', [ArticleController::class, 'store']);
        Route::get('/{id}', [ArticleController::class, 'show']);
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [ArticleController::class, 'update']);
        Route::delete('/{id}', [ArticleController::class, 'destroy']);

    });

    // Address Route
    Route::prefix('addresses')->group(function () {
        Route::get('/', [ AddressController::class, 'index']);
        Route::post('/', [ AddressController::class, 'store']);
        Route::get('/{id}', [AddressController::class, 'show']);
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [AddressController::class, 'update']);
        Route::delete('/{id}', [AddressController::class, 'destroy']);

    });

    // Author Route
    Route::prefix('authors')->group(function () {
        Route::get('/', [ AuthorController::class, 'index']);
        Route::post('/', [ AuthorController::class, 'store']);
        Route::get('/{id}', [AuthorController::class, 'show']);
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [AuthorController::class, 'update']);
        Route::delete('/{id}', [AuthorController::class, 'destroy']);

    });

    //Orders Route
    Route::prefix('orders')->group(function (){
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{invoiceNumber}', [OrderController::class, 'show']);
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [OrderController::class, 'update']); 
        Route::delete('delete_order/{id}', [OrderController::class, 'deleteOrder'])->name('delete_order');
        Route::delete('delete_item/{id}', [OrderController::class, 'deleteOrderItem'])->name('delete_item');    

    });

});