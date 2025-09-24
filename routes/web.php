<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\API\AuthController;

// Main public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/auctions', [HomeController::class, 'auctions'])->name('auctions');
Route::get('/auctions/{id}', [HomeController::class, 'auctionDetail'])->name('auction.detail');
Route::get('/categories', [HomeController::class, 'categories'])->name('categories');
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/winners', [HomeController::class, 'winners'])->name('winners');

// Debug route (remove in production)
// Debug route removed for production

// Information pages
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/shipping', [HomeController::class, 'shipping'])->name('shipping');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/help', [HomeController::class, 'help'])->name('help');
Route::get('/help/{topic}', [HomeController::class, 'help'])->name('help.topic');
Route::get('/sitemap', [HomeController::class, 'sitemap'])->name('sitemap');

// Auction bidding routes
Route::post('/bid-now', [HomeController::class, 'placeBid'])->middleware('auth')->name('bid.now');
Route::post('/auto-bid', [HomeController::class, 'autoBid'])->middleware('auth')->name('auto.bid');

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Currency routes
Route::post('/currency/switch', [App\Http\Controllers\CurrencyController::class, 'switch'])->name('currency.switch');
Route::get('/currency/current', [App\Http\Controllers\CurrencyController::class, 'current'])->name('currency.current');
Route::post('/currency/convert', [App\Http\Controllers\CurrencyController::class, 'convert'])->name('currency.convert');

// Dashboard routes (protected with auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/auctions', [App\Http\Controllers\DashboardController::class, 'activeAuctions'])->name('dashboard.auctions');
    Route::get('/dashboard/watchlist', [App\Http\Controllers\DashboardController::class, 'watchlist'])->name('dashboard.watchlist');
    Route::get('/dashboard/wins', [App\Http\Controllers\DashboardController::class, 'wins'])->name('dashboard.wins');
    Route::post('/dashboard/review/submit', [App\Http\Controllers\DashboardController::class, 'submitReview'])->name('dashboard.review.submit');
    Route::get('/dashboard/history', [App\Http\Controllers\DashboardController::class, 'bidHistory'])->name('dashboard.history');
    Route::get('/dashboard/orders', [App\Http\Controllers\DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/orders/{order}', [App\Http\Controllers\DashboardController::class, 'showOrder'])->name('dashboard.order.show');
    Route::post('/dashboard/orders/{order}/payment', [App\Http\Controllers\DashboardController::class, 'processOrderPayment'])->name('dashboard.order.payment');
    Route::get('/dashboard/orders/payment/success', [App\Http\Controllers\DashboardController::class, 'orderPaymentSuccess'])->name('dashboard.order.payment.success');
    Route::get('/dashboard/orders/{order}/payment/cancel', [App\Http\Controllers\DashboardController::class, 'orderPaymentCancel'])->name('dashboard.order.payment.cancel');
    Route::get('/dashboard/settings', [App\Http\Controllers\DashboardController::class, 'settings'])->name('dashboard.settings');
    Route::get('/dashboard/buy-bids', [App\Http\Controllers\DashboardController::class, 'buyBids'])->name('dashboard.buy-bids');
    Route::get('/dashboard/purchase-bids', [App\Http\Controllers\DashboardController::class, 'purchaseBids'])->name('dashboard.purchase-bids');
    Route::post('/dashboard/process-purchase', [App\Http\Controllers\DashboardController::class, 'processPurchase'])->name('dashboard.process-purchase');
    Route::get('/dashboard/checkout/{auction}', [App\Http\Controllers\DashboardController::class, 'checkout'])->name('dashboard.checkout');
    Route::post('/dashboard/checkout/{auction}', [App\Http\Controllers\DashboardController::class, 'processCheckout'])->name('dashboard.checkout.process');

    // Stripe payment routes
    Route::post('/stripe/checkout', [App\Http\Controllers\StripeController::class, 'createCheckoutSession'])->name('stripe.checkout');
    Route::get('/stripe/success', [App\Http\Controllers\StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [App\Http\Controllers\StripeController::class, 'cancel'])->name('stripe.cancel');

    // Dashboard actions
    Route::post('/dashboard/settings/profile', [App\Http\Controllers\DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::post('/dashboard/settings/password', [App\Http\Controllers\DashboardController::class, 'updatePassword'])->name('dashboard.password.update');
    Route::post('/dashboard/settings/notifications', [App\Http\Controllers\DashboardController::class, 'updateNotifications'])->name('dashboard.notifications.update');
    Route::post('/dashboard/update-avatar', [App\Http\Controllers\DashboardController::class, 'updateAvatar'])->name('dashboard.update-avatar');
    Route::post('/dashboard/watchlist/add', [App\Http\Controllers\DashboardController::class, 'addToWatchlist'])->name('dashboard.watchlist.add');
    Route::post('/dashboard/watchlist/remove', [App\Http\Controllers\DashboardController::class, 'removeFromWatchlist'])->name('dashboard.watchlist.remove');
});

// Admin routes (protected with auth and admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');

    // Users Management
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', function() { return view('admin.users.create'); })->name('admin.users.create');
    Route::post('/users', [App\Http\Controllers\Admin\AdminController::class, 'createUser'])->name('admin.users.store');
    Route::get('/users/{id}', [App\Http\Controllers\Admin\AdminController::class, 'showUser'])->name('admin.users.show');
    Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Auctions Management
    Route::get('/auctions', [App\Http\Controllers\Admin\AdminAuctionController::class, 'index'])->name('admin.auctions');
    Route::get('/auctions/create', function() {
        $categories = \App\Models\Category::all();
        return view('admin.auctions.create', compact('categories'));
    })->name('admin.auctions.create');
    Route::post('/auctions', [App\Http\Controllers\Admin\AdminAuctionController::class, 'store'])->name('admin.auctions.store');
    Route::get('/auctions/{id}', [App\Http\Controllers\Admin\AdminAuctionController::class, 'show'])->name('admin.auctions.show');
    Route::get('/auctions/{id}/edit', [App\Http\Controllers\Admin\AdminAuctionController::class, 'edit'])->name('admin.auctions.edit');
    Route::put('/auctions/{id}', [App\Http\Controllers\Admin\AdminAuctionController::class, 'update'])->name('admin.auctions.update');
    Route::delete('/auctions/{id}', [App\Http\Controllers\Admin\AdminAuctionController::class, 'destroy'])->name('admin.auctions.delete');
    Route::patch('/auctions/{id}/status', [App\Http\Controllers\Admin\AdminAuctionController::class, 'updateStatus'])->name('admin.auctions.update-status');
    Route::get('/auctions/{id}/images', function($id) {
        return redirect()->route('admin.auctions.edit', $id);
    })->name('admin.auctions.images');
    Route::post('/auctions/{id}/images', [App\Http\Controllers\Admin\AdminAuctionController::class, 'uploadImage'])->name('admin.auctions.upload-image');
    Route::delete('/auctions/{id}/delete-image/{imageIndex}', [App\Http\Controllers\Admin\AdminAuctionController::class, 'deleteImage'])->name('admin.auctions.delete-image');

    // Orders Management
    Route::get('/orders', [App\Http\Controllers\Admin\AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{id}', [App\Http\Controllers\Admin\AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::patch('/orders/{id}/status', [App\Http\Controllers\Admin\AdminController::class, 'updateOrderStatus'])->name('admin.orders.update-status');
    Route::post('/orders/{id}/notes', [App\Http\Controllers\Admin\AdminController::class, 'addOrderNote'])->name('admin.orders.add-note');

    // Statistics and Reports
    Route::get('/statistics', [App\Http\Controllers\Admin\AdminController::class, 'statistics'])->name('admin.statistics');
    Route::get('/reports/users', [App\Http\Controllers\Admin\AdminController::class, 'usersReport'])->name('admin.reports.users');
    Route::get('/reports/auctions', [App\Http\Controllers\Admin\AdminController::class, 'auctionsReport'])->name('admin.reports.auctions');
    Route::get('/reports/sales', [App\Http\Controllers\Admin\AdminController::class, 'salesReport'])->name('admin.reports.sales');

    // Categories Management
    Route::get('/categories', [App\Http\Controllers\Admin\AdminCategoryController::class, 'index'])->name('admin.categories');
    Route::post('/categories', [App\Http\Controllers\Admin\AdminCategoryController::class, 'storeWeb'])->name('admin.categories.store');
    Route::put('/categories/{id}', [App\Http\Controllers\Admin\AdminCategoryController::class, 'updateWeb'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [App\Http\Controllers\Admin\AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::put('/categories/{id}/reassign-auctions', [App\Http\Controllers\Admin\AdminCategoryController::class, 'reassignAuctions'])->name('admin.categories.reassign-auctions');

    // Marketing Management
    Route::get('/marketing', [App\Http\Controllers\Admin\AdminMarketingController::class, 'index'])->name('admin.marketing');
    Route::get('/marketing/slider', [App\Http\Controllers\Admin\AdminMarketingController::class, 'slider'])->name('admin.marketing.slider');
    Route::post('/marketing/slider/upload', [App\Http\Controllers\Admin\AdminMarketingController::class, 'uploadSliderImage'])->name('admin.marketing.slider.upload');
    Route::put('/marketing/slider/{index}', [App\Http\Controllers\Admin\AdminMarketingController::class, 'updateSliderImage'])->name('admin.marketing.slider.update');
    Route::delete('/marketing/slider/{index}', [App\Http\Controllers\Admin\AdminMarketingController::class, 'deleteSliderImage'])->name('admin.marketing.slider.delete');
    Route::post('/marketing/slider/reorder', [App\Http\Controllers\Admin\AdminMarketingController::class, 'reorderSliderImages'])->name('admin.marketing.slider.reorder');

    // Admin Settings
    Route::get('/settings', [App\Http\Controllers\Admin\AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings/profile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('admin.settings.profile');
    Route::post('/settings/password', [App\Http\Controllers\Admin\AdminController::class, 'updatePassword'])->name('admin.settings.password');
    Route::post('/settings/site', [App\Http\Controllers\Admin\AdminController::class, 'updateSiteSettings'])->name('admin.settings.site');
    
    // Currency Management
    Route::get('/settings/currencies', [App\Http\Controllers\Admin\AdminCurrencyController::class, 'index'])->name('admin.settings.currencies');
    Route::post('/settings/currencies/rates', [App\Http\Controllers\Admin\AdminCurrencyController::class, 'updateExchangeRates'])->name('admin.settings.currencies.rates');
    Route::post('/settings/currencies/settings', [App\Http\Controllers\Admin\AdminCurrencyController::class, 'updateSettings'])->name('admin.settings.currencies.settings');
    Route::post('/settings/currencies/add', [App\Http\Controllers\Admin\AdminCurrencyController::class, 'addCurrency'])->name('admin.settings.currencies.add');
    Route::delete('/settings/currencies/{currency}', [App\Http\Controllers\Admin\AdminCurrencyController::class, 'removeCurrency'])->name('admin.settings.currencies.remove');
});
