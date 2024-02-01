<?php

use Illuminate\Support\Facades\Route;
use Transave\ScolaBookstore\Http\Controllers\AuthController;
use Transave\ScolaBookstore\Http\Controllers\ReviewerRequestController;
use Transave\ScolaBookstore\Http\Controllers\BookController;
use Transave\ScolaBookstore\Http\Controllers\OrderController;
use Transave\ScolaBookstore\Http\Controllers\RestfulAPIController;
use Transave\ScolaBookstore\Http\Controllers\SearchController;
use Transave\ScolaBookstore\Http\Controllers\UserController;
use Transave\ScolaBookstore\Http\Controllers\ConferencePaperController;
use Transave\ScolaBookstore\Http\Controllers\ArticleController;
use Transave\ScolaBookstore\Http\Controllers\monographController;
use Transave\ScolaBookstore\Http\Controllers\JournalController;
use Transave\ScolaBookstore\Http\Controllers\ResearchResourceController;
use Transave\ScolaBookstore\Http\Controllers\ReportController;
use Transave\ScolaBookstore\Http\Controllers\FestchrisftController;
use Transave\ScolaBookstore\Http\Controllers\CartController;
use Transave\ScolaBookstore\Http\Controllers\SalesController;

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
    Route::get('schools', [SearchController::class, 'indexSchools'])->name('schools');
    Route::get('saves', [SearchController::class, 'indexSaves'])->name('saves');
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



    //Sales Route
    Route::prefix('sales')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/{id}', [SalesController::class, 'show'])->name('show');
        Route::get('purchase/{id}', [SalesController::class, 'userPurchase'])->name('purchase');
    });


 //Become a Reviewer Route
    Route::prefix('reviewer_requests')->group(function () {
        Route::get('/', [ReviewerRequestController::class, 'index'])->name('index');
        Route::post('/', [ReviewerRequestController::class, 'store'])->name('store');
        Route::get('/{id}', [ReviewerRequestController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [ReviewerRequestController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReviewerRequestController::class, 'destroy'])->name('delete');
    });


    //Books Route
    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::post('/', [BookController::class, 'store'])->name('store');
        Route::get('/{id}', [BookController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [BookController::class, 'update'])->name('update');
        Route::delete('/{id}', [BookController::class, 'destroy'])->name('delete');

    });


        //Carts Route
    Route::prefix('carts')->group(function () {
        Route::get('/', [ CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::get('/{userId}', [CartController::class, 'show'])->name('show');
        Route::delete('/{cartItemId}', [CartController::class, 'removeItem'])->name('removeItem');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('clear/{userId}', [CartController::class, 'clearCart'])->name('clear');

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
        Route::get('/', [FestchrisftController::class, 'index'])->name('index');
        Route::post('/', [FestchrisftController::class, 'store'])->name('store');
        Route::get('/{id}', [FestchrisftController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [FestchrisftController::class, 'update'])->name('update');
        Route::delete('/{id}', [FestchrisftController::class, 'destroy'])->name('delete');

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


    //Research Resource route
        Route::prefix('research_resources')->group(function () {
        Route::get('/', [ResearchResourceController::class, 'index'])->name('index');
        Route::post('/', [ResearchResourceController::class, 'store'])->name('store');
        Route::get('/{id}', [ResearchResourceController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [ResearchResourceController::class, 'update'])->name('update');
        Route::delete('/{id}', [ResearchResourceController::class, 'destroy'])->name('delete');

    });

 //Report route
        Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::get('/{id}', [ReportController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [ReportController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReportController::class, 'destroy'])->name('delete');

    });



    //Orders Route
    Route::prefix('orders')->group(function (){
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{invoiceNumber}', [OrderController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/{id}', [OrderController::class, 'update'])->name('update'); 
        Route::delete('delete_order/{id}', [OrderController::class, 'deleteOrder'])->name('delete_order');
        Route::delete('delete_item/{id}', [OrderController::class, 'deleteOrderItem'])->name('delete_item');    

    });

});