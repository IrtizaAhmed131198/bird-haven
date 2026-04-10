<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BirdController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// ─── Home ────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Static Pages ─────────────────────────────────────────────────────────────
Route::get('/about', [AboutController::class, 'index'])->name('about');

// ─── Policy Pages (loaded from CMS) ──────────────────────────────────────────
Route::get('/privacy-policy',   [PolicyController::class, 'show'])->defaults('slug', 'privacy-policy')->name('privacy');
Route::get('/terms',            [PolicyController::class, 'show'])->defaults('slug', 'terms')->name('terms');
Route::get('/ethical-care',     [PolicyController::class, 'show'])->defaults('slug', 'ethical-care')->name('ethical.care');
Route::get('/ethical-sourcing', [PolicyController::class, 'show'])->defaults('slug', 'ethical-sourcing')->name('ethical.sourcing');

// ─── Shop / Birds ─────────────────────────────────────────────────────────────
Route::get('/shop',          [BirdController::class, 'index'])->name('shop');
Route::get('/accessories',            [AccessoryController::class, 'index'])->name('accessories');
Route::get('/accessory/{slug}',       [AccessoryController::class, 'show'])->name('accessory.show');
Route::get('/bird/{slug}',         [BirdController::class, 'show'])->name('bird.show');
Route::get('/bird/{slug}/guide',   [BirdController::class, 'downloadGuide'])->name('bird.guide');
Route::post('/bird/{bird}/review', [ReviewController::class, 'store'])->name('review.store')->middleware('auth');

// ─── Newsletter ───────────────────────────────────────────────────────────────
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// ─── Contact ──────────────────────────────────────────────────────────────────
Route::get('/contact',  [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// ─── Two-Factor Authentication ────────────────────────────────────────────────
Route::get('/2fa',        [TwoFactorController::class, 'show'])->name('2fa.show');
Route::post('/2fa',       [TwoFactorController::class, 'verify'])->name('2fa.verify');
Route::post('/2fa/resend',[TwoFactorController::class, 'resend'])->name('2fa.resend');
Route::post('/2fa/toggle',[TwoFactorController::class, 'toggle'])->name('2fa.toggle')->middleware('auth');

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);

    // Forgot / Reset Password
    Route::get('/forgot-password',  [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password',        [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ─── Cart (works for guests too) ──────────────────────────────────────────────
Route::get('/cart',              [CartController::class, 'index'])->name('cart');
Route::post('/cart',             [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{item}',     [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}',    [CartController::class, 'remove'])->name('cart.remove');

// ─── Checkout ─────────────────────────────────────────────────────────────────
Route::get('/checkout',  [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// ─── Authenticated Routes ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Wishlist
    Route::get('/wishlist',           [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist',          [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{item}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Orders
    Route::get('/orders',                      [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}',              [OrderController::class, 'show'])->name('order.show');
    Route::get('/order-confirmation/{order}',  [OrderController::class, 'confirmation'])->name('order.confirmation');

    // Profile
    Route::get('/profile',            [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    // Addresses
    Route::post('/addresses',                      [AddressController::class, 'store'])->name('addresses.store');
    Route::patch('/addresses/{address}',           [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}',          [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/addresses/{address}/default',   [AddressController::class, 'setDefault'])->name('addresses.default');

    // Shipping & Tracking
    Route::get('/tracking',           [ShippingController::class, 'index'])->name('shipping.tracking');
    Route::get('/tracking/{shipment}',[ShippingController::class, 'show'])->name('shipping.show');
});

// ─── Admin Panel ──────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin login (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    // Admin logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout.admin')->middleware('auth');

    // Protected admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users/data', [Admin\UserController::class, 'getData'])->name('users.data');
    Route::resource('/users', Admin\UserController::class);
    Route::get('/settings',  [Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    Route::get('/cms/pages/data', [Admin\CMSPageController::class, 'getData'])->name('cms.pages.data');
    Route::resource('/cms/pages', Admin\CMSPageController::class)->names('cms.pages');

    // Birds inventory
    Route::get('/birds/data',          [Admin\BirdController::class, 'getData'])->name('birds.data');
    Route::patch('/birds/{bird}/toggle', [Admin\BirdController::class, 'toggle'])->name('birds.toggle');
    Route::resource('/birds', Admin\BirdController::class);

    // Categories
    Route::get('/categories/data', [Admin\CategoryController::class, 'getData'])->name('categories.data');
    Route::resource('/categories', Admin\CategoryController::class);

    // Accessories
    Route::get('/accessories/data',             [Admin\AccessoryController::class, 'getData'])->name('accessories.data');
    Route::patch('/accessories/{accessory}/toggle', [Admin\AccessoryController::class, 'toggle'])->name('accessories.toggle');
    Route::resource('/accessories', Admin\AccessoryController::class);

    // Orders
    Route::get('/orders/data',                       [Admin\OrderController::class, 'getData'])->name('orders.data');
    Route::patch('/orders/{order}/status',           [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/payment',          [Admin\OrderController::class, 'updatePayment'])->name('orders.payment');
    Route::get('/orders/{order}',                    [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders',                            [Admin\OrderController::class, 'index'])->name('orders.index');

    // Contact Messages
    Route::get('/contacts',                    [Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/data',               [Admin\ContactController::class, 'getData'])->name('contacts.data');
    Route::post('/contacts/mark-all-read',     [Admin\ContactController::class, 'markAllRead'])->name('contacts.markAllRead');
    Route::get('/contacts/{contact}',          [Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::delete('/contacts/{contact}',       [Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

    // Shipments
    Route::get('/shipments/data',             [Admin\ShipmentController::class, 'getData'])->name('shipments.data');
    Route::resource('/shipments', Admin\ShipmentController::class)->except(['show']);

    // Newsletter
    Route::get('/newsletter',                          [Admin\NewsletterController::class, 'index'])->name('newsletter.index');
    Route::get('/newsletter/data',                     [Admin\NewsletterController::class, 'getData'])->name('newsletter.data');
    Route::patch('/newsletter/{newsletter}/toggle',    [Admin\NewsletterController::class, 'toggle'])->name('newsletter.toggle');
    Route::delete('/newsletter/{newsletter}',          [Admin\NewsletterController::class, 'destroy'])->name('newsletter.destroy');

    // Reviews
    Route::get('/reviews/data',               [Admin\ReviewController::class, 'getData'])->name('reviews.data');
    Route::patch('/reviews/{review}/approve', [Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}',        [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/reviews',                    [Admin\ReviewController::class, 'index'])->name('reviews.index');
    }); // end auth+admin middleware

}); // end admin prefix
