<?php

use Illuminate\Support\Facades\Route;
use Transave\ScolaBookstore\Http\Controllers\AuthController;
use Transave\ScolaBookstore\Http\Controllers\BookController;
use Transave\ScolaBookstore\Http\Controllers\OrderController;
use Transave\ScolaBookstore\Http\Controllers\RestfulAPIController;
use Transave\ScolaBookstore\Http\Controllers\SearchController;
use Transave\ScolaBookstore\Http\Controllers\UserController;
use Transave\ScolaBookstore\Http\Controllers\ConferencePaperController;
use Transave\ScolaBookstore\Http\Controllers\ArticleController;
use Transave\ScolaBookstore\Http\Controllers\monographController;
use Transave\ScolaBookstore\Http\Controllers\JournalController;

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
        Route::patch('user-type/{id}',  [UserController::class, 'becomeReviewer'])->name('user-type');
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

      
    //Conference Paper Route
    Route::prefix('papers')->group(function () {
        Route::get('/', [ConferencePaperController::class, 'index'])->name('index');
        Route::post('/', [ConferencePaperController::class, 'store'])->name('store');
        Route::get('/{id}', [ConferencePaperController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [ConferencePaperController::class, 'update'])->name('update');
        Route::delete('/{id}', [ConferencePaperController::class, 'destroy'])->name('delete');

    });


        //Articles Route
    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{id}', [ArticleController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('delete');

    });


        //festchrisfts Route
    Route::prefix('festchrisfts')->group(function () {
        Route::get('/', [festchrisftController::class, 'index'])->name('index');
        Route::post('/', [festchrisftController::class, 'store'])->name('store');
        Route::get('/{id}', [festchrisftController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [festchrisftController::class, 'update'])->name('update');
        Route::delete('/{id}', [festchrisftController::class, 'destroy'])->name('delete');

    });


            //Journal Route
    Route::prefix('journals')->group(function () {
        Route::get('/', [JournalController::class, 'index'])->name('index');
        Route::post('/', [JournalController::class, 'store'])->name('store');
        Route::get('/{id}', [JournalController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [JournalController::class, 'update'])->name('update');
        Route::delete('/{id}', [JournalController::class, 'destroy'])->name('delete');

    });


//Monograph route
        Route::prefix('monographs')->group(function () {
        Route::get('/', [MonographController::class, 'index'])->name('index');
        Route::post('/', [MonographController::class, 'store'])->name('store');
        Route::get('/{id}', [MonographController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [MonographController::class, 'update'])->name('update');
        Route::delete('/{id}', [MonographController::class, 'destroy'])->name('delete');

    });



    //Orders Route
    Route::prefix('orders')->group(function (){
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [OrderController::class, 'update'])->name('update');
    });
});