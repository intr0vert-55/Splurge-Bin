<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => 'auth'],function(){
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'products'], function(){

        Route::group(['middleware' => 'is_consumer'], function(){
            Route::get('/view/{id}', [ConsumerController::class, 'view'])->name('products.consumer.view');
            Route::get('/add/wishlist', [ProductController::class, 'addToWishList'])->name('product.wishlist.add');
            Route::get('/remove/wishlist', [ProductController::class, 'removeFromWishList'])->name('product.wishlist.remove');
            Route::get('/add/bin', [ProductController::class, 'addToBin'])->name('product.bin.add');
            Route::get('/remove/bin', [ProductController::class, 'removeFromBin'])->name('product.bin.remove');
            Route::get('/wishlist', [ConsumerController::class, 'wishList'])->name('wishlist');
            Route::get('/bin', [ConsumerController::class, 'bin'])->name('bin');
            Route::get('/{id}/buy', [ConsumerController::class, 'buy'])->name('product.buy');
            Route::post('/address/add', [ConsumerController::class, 'addAddress'])->name('address.add');
            Route::post('/address/update', [ConsumerController::class, 'updateAddress'])->name('address.update');
            Route::delete('/address/delete', [ConsumerController::class, 'deleteAddress'])->name('address.delete');
            Route::post('/{id}/purchase', [ConsumerController::class, 'purchase'])->name('product.purchase');
            Route::get('/orders', [ConsumerController::class, 'orders'])->name('orders');
            Route::get('/orders/view/{id}', [ConsumerController::class, 'viewOrder'])->name('orders.view');
            Route::delete('/order/cancel', [ConsumerController::class, 'cancelOrder'])->name('order.cancel');
            Route::post('/bin/buyAll', [ConsumerController::class, 'buyAll'])->name('products.bin.buy');
            Route::post('/{id}/review/add', [ConsumerController::class, 'addReview'])->name('review.add');
            Route::post('/{id}/review/update', [ConsumerController::class, 'updateReview'])->name('review.update');
            Route::delete('/{id}/review/delete', [ConsumerController::class, 'deleteReview'])->name('review.delete');
            Route::get('/user/profile', [ConsumerController::class, 'profile'])->name('user.profile');
        });


        Route::group(['middleware' => 'is_seller', 'prefix' => 'seller'], function(){
            Route::get('/add',[ProductController::class, 'add'])->name('products.add');
            Route::post('/store',[ProductController::class, 'store'])->name('products.store');
            Route::get('/{id}/edit',[ProductController::class, 'edit'])->name('products.edit');
            Route::get('/delete',[ProductController::class, 'delete'])->name('products.delete');
            Route::post('/update/{id}', [ProductController::class, 'update'])->name('products.update');
            Route::get('/view/{id}', [SellerController::class, 'view'])->name('products.seller.view');
            Route::get('/orders', [SellerController::class, 'orders'])->name('seller.orders');
            Route::get('/order/{id}', [SellerController::class, 'viewOrder'])->name('seller.order.view');
            Route::get('order/seller/accept', [SellerController::class, 'acceptOrder'])->name('order.accept');
            Route::get('/orders/acceptall', [SellerController::class, 'acceptAllOrders'])->name('orders.acceptall');
            Route::get('/seller/profile', [SellerController::class, 'profile'])->name('seller.profile');
        });
    });
});
