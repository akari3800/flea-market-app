<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VerificationController;


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

Route::get('/', [ItemController::class, 'index'])->name('products.index');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/register', [RegisterController::class,'create']);

Route::post('/register',[RegisterController::class,'store']);

Route::get('/login',[LoginController::class,'login'])->name('login');

Route::post('/login', [LoginController::class, 'store']);

Route::get('/purchase/success', [PurchaseController::class, 'success'])->name('purchase.success');

Route::get('/purchase/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [RegisterController::class, 'verifyNotice'])->name('verification.notice');
    Route::post('/email/verification-notification', [VerificationController::class,'resend'])->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index']);
    Route::get('/mypage/profile',[ProfileController::class,'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::get('/purchase/address/{item_id}',[PurchaseController::class,'address']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'store']);
    Route::post('purchase/{product}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');
    Route::get('/sell', [SellController::class, 'create']);
    Route::post('/sell', [SellController::class, 'store']);
    Route::post('/like/{product}', [LikeController::class, 'toggle'])->name('like.toggle');
    Route::post('/comment/{product}', [CommentController::class, 'store'])->name('comment.store');
});





