<?php

use Illuminate\Support\Facades\Route;
use Transave\ScolaBookstore\Http\Controllers\AddressController;
use Transave\ScolaBookstore\Http\Controllers\AuthController;
use Transave\ScolaBookstore\Http\Controllers\AuthorController;
use Transave\ScolaBookstore\Http\Controllers\ConfigController;
use Transave\ScolaBookstore\Http\Controllers\DashboardController;
use Transave\ScolaBookstore\Http\Controllers\OrderController;
use Transave\ScolaBookstore\Http\Controllers\PageController;
use Transave\ScolaBookstore\Http\Controllers\PaymentDetailController;
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
        Route::get('/{id}', [PageController::class, 'singleResource'])->name('show');
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

    // Payment Details Route
    Route::prefix('payment-details')->as('payment-details.')->group(function () {
        Route::get('/', [ PaymentDetailController::class, 'index'])->name('index');
        Route::post('/', [ PaymentDetailController::class, 'store'])->name('store');
        Route::get('/{id}', [PaymentDetailController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [PaymentDetailController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaymentDetailController::class, 'destroy'])->name('delete');

    });

    // Author Route
//    Route::prefix('authors')->as('authors.')->group(function () {
//        Route::get('/', [ AuthorController::class, 'index'])->name('index');
//        Route::post('/', [ AuthorController::class, 'store'])->name('store');
//        Route::get('/{id}', [PageController::class, 'singleAuthor'])->name('show');
//        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [AuthorController::class, 'update'])->name('update');
//        Route::delete('/{id}', [AuthorController::class, 'destroy'])->name('delete');
//
//    });

    //Orders Route
    Route::prefix('orders')->as('orders.')->group(function (){
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/{reference}/verify', [OrderController::class, 'verify'])->name('verify');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [OrderController::class, 'update']); 
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('delete');
    });

    //Pages Route
    Route::prefix('pages')->as('pages.')->group(function() {
        Route::get('/home', [ PageController::class, 'homePage'])->name('home');
        Route::get('/resources/{id}', [ PageController::class, 'singleResource'])->name('resources.show');
        Route::get('/authors/{id}', [ PageController::class, 'singleAuthor'])->name('authors.show');
    });

    Route::prefix('config')->as('config.')->group(function() {
        Route::get('paystack', [ ConfigController::class, 'paystack'])->name('paystack');
    });

    Route::prefix('paystack')->as('paystack.')->group(function () {
        Route::get('bank-list', function () {
            return (new \RaadaaPartners\RaadaaBase\Actions\Paystack\BankList())->execute();
        });
    });

    Route::prefix('dashboard')->as('dashboard.')->group(function() {
        Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
            Route::get('metrics', [ DashboardController::class, 'adminAnalytics'])->name('metrics');
            Route::get('graph', [ DashboardController::class, 'adminGraph'])->name('graph');
        });
        Route::group(['prefix' => 'authors', 'as' => 'authors.'], function() {
            Route::get('books-ordered', [DashboardController::class, 'authorOrderItems'])->name('orders');
            Route::get('{id}/metrics', [DashboardController::class, 'authorRevenue'])->name('metrics');
        });
    });

});