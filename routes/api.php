<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuctionController;
use App\Http\Controllers\API\BidController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminAuctionController;
use App\Http\Controllers\Admin\AdminBidPackageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::patch('/auth/update-password', [AuthController::class, 'updatePassword'])->middleware('auth:sanctum');

// User routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/profile', [UserController::class, 'getProfile']);
    Route::patch('/users/profile', [UserController::class, 'updateProfile']);
    Route::patch('/users/addresses', [UserController::class, 'updateAddresses']);
    Route::patch('/users/payment-methods', [UserController::class, 'updatePaymentMethods']);
});

// Auction routes
Route::get('/auctions', [AuctionController::class, 'index']);
Route::get('/auctions/{id}', [AuctionController::class, 'show']);
Route::get('/auctions/featured', [AuctionController::class, 'featured']);
Route::get('/auctions/ending-soon', [AuctionController::class, 'endingSoon']);
Route::get('/auctions/recently-ended', [AuctionController::class, 'recentlyEnded']);
Route::post('/auctions/{id}/bid', [AuctionController::class, 'placeBid'])->middleware('auth:sanctum');

// Bid routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bids', [BidController::class, 'index']);
    Route::get('/bids/{id}', [BidController::class, 'show']);
    Route::post('/bids/autobid', [BidController::class, 'autobid']);
});

// Order routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::patch('/orders/{id}', [OrderController::class, 'update']);
});

// Payment routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payments/bid-package', [PaymentController::class, 'purchaseBidPackage']);
    Route::post('/payments/auction', [PaymentController::class, 'payForWonAuction']);
    Route::get('/payments/methods', [PaymentController::class, 'getPaymentMethods']);
    Route::post('/payments/methods', [PaymentController::class, 'addPaymentMethod']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // User Management
    Route::get('/users', [AdminController::class, 'users']);
    Route::post('/users', [AdminController::class, 'createUser']);
    Route::get('/users/{id}', [AdminController::class, 'showUser']);
    Route::patch('/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);

    // Category Management
    Route::apiResource('categories', AdminCategoryController::class);

    // Auction Management
    Route::apiResource('auctions', AdminAuctionController::class);
    Route::patch('/auctions/{id}/status', [AdminAuctionController::class, 'updateStatus']);
    Route::post('/auctions/{id}/images', [AdminAuctionController::class, 'uploadImage']);
    Route::delete('/auctions/{id}/images/{imageIndex}', [AdminAuctionController::class, 'deleteImage']);

    // Bid Package Management
    Route::apiResource('bid-packages', AdminBidPackageController::class);

    // Order Management
    Route::get('/orders', [AdminController::class, 'orders']);
    Route::get('/orders/{id}', [AdminController::class, 'showOrder']);
    Route::patch('/orders/{id}/status', [AdminController::class, 'updateOrderStatus']);

    // Statistics & Reports
    Route::get('/statistics', [AdminController::class, 'statistics']);
    Route::get('/reports/sales', [AdminController::class, 'salesReport']);
    Route::get('/reports/users', [AdminController::class, 'usersReport']);
    Route::get('/reports/auctions', [AdminController::class, 'auctionsReport']);
});