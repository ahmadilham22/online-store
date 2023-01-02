<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductGalleryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardProductController;
use App\Http\Controllers\DashboardSettingController;
use App\Http\Controllers\DashboardTransactionController;
use App\Models\ProductGallery;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.detail');

Route::get('/detail/{id}', [DetailController::class, 'index'])->name('detail');
Route::post('/detail/{id}', [DetailController::class, 'add'])->name('detail.add');

Route::get('/success', [CartController::class, 'success'])->name('success');

Route::post('/checkout/callback', [CheckoutController::class, 'callback'])->name('midtrans-callback');

Route::get('/register/success', [RegisterController::class, 'success'])->name('register.success');



Route::group(['middleware' => ['auth']], function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout');

    Route::prefix('dashboard')->group(function () {
        Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');

        // product
        Route::get('/product', [DashboardProductController::class, 'index'])->name('dashboard.product');
        Route::get('/product/detail/{id}', [DashboardProductController::class, 'detail'])->name('dashboard.product.detail');
        Route::get('/product/create/ind', [DashboardProductController::class, 'create'])->name('dashboard.product.create');
        Route::post('/product/store', [DashboardProductController::class, 'store'])->name('dashboard.product.store');
        Route::post('/product/update/{id}', [DashboardProductController::class, 'update'])->name('dashboard.product.update');
        // Route::delete('/product/destroy/{id}', [DashboardProductController::class, 'destroy'])->name('dashboard.product.delete');

        Route::post('/product/gallery/upload', [DashboardProductController::class, 'uploadGallery'])->name('dashboard.product.gallery.upload');
        Route::get('/product/gallery/delete/{id}', [DashboardProductController::class, 'deleteGallery'])->name('dashboard.product.gallery.delete');



        // Transaction
        Route::get('/transaction', [DashboardTransactionController::class, 'index'])->name('dashboard.transaction');
        Route::get('/transaction/{id}', [DashboardTransactionController::class, 'detail'])->name('dashboard.transaction.detail');
        Route::post('/transaction/update/{id}', [DashboardTransactionController::class, 'update'])->name('dashboard.transaction.update');

        // Settings
        Route::get('/setting', [DashboardSettingController::class, 'store'])->name('dashboard.setting');
        Route::get('/account', [DashboardSettingController::class, 'account'])->name('dashboard.setting.account');
        Route::post('/account/{redirect}', [DashboardSettingController::class, 'update'])->name('dashboard.setting.redirect');
    });
});

// middleware(['auth','admin'])
Route::prefix('admin')->namespace('Admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // Category
    Route::get('/category', [AdminCategoryController::class, 'index'])->name('admin.category.index');
    Route::post('/category/store', [AdminCategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
    Route::put('/category/update/{id}', [AdminCategoryController::class, 'update'])->name('admin.category.update');
    Route::get('/category/edit/{id}', [AdminCategoryController::class, 'edit'])->name('admin.category.edit');
    Route::delete('/category/destroy/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.category.destroy');

    // User
    Route::get('user', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('admin.user.store');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('user/updata/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('user/destroy/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    // Product
    Route::get('product', [productController::class, 'index'])->name('admin.product.index');
    Route::get('product/create', [ProductController::class, 'create'])->name('admin.product.create');
    Route::post('product/store', [ProductController::class, 'store'])->name('admin.product.store');
    Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::put('product/updata/{id}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::delete('product/destroy/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');

    // Gallery
    Route::get('gallery', [ProductGalleryController::class, 'index'])->name('admin.product-gallery.index');
    Route::get('gallery/create', [ProductGalleryController::class, 'create'])->name('admin.product-gallery.create');
    Route::post('gallery/store', [ProductGalleryController::class, 'store'])->name('admin.product-gallery.store');
    Route::delete('gallery/destroy/{id}', [ProductGalleryController::class, 'destroy'])->name('admin.product-gallery.destroy');
});

Auth::routes();