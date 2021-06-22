<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Buyer\BuyerCategoryController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Buyer\BuyerProductController;
use App\Http\Controllers\Buyer\BuyerSellerController;
use App\Http\Controllers\Buyer\BuyerTransactionController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Category\CategoryProductController;
use App\Http\Controllers\Category\CategorySellerController;
use App\Http\Controllers\Category\CategoryTransactionController;
use App\Http\Controllers\Product\ProductBuyerController;
use App\Http\Controllers\Product\ProductBuyerTransactionController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductTransactionController;
use App\Http\Controllers\Seller\SellerBuyerController;
use App\Http\Controllers\Seller\SellerCategoryController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerTransactionController;
use App\Http\Controllers\Transaction\TransactionCategoryController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Transaction\TransactionSellerController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
// http://127.0.0.1:8000/api/verify-email/4/4be5a1201077d10124962f66f2bc872d6a8cd771?expires=1624165394&signature=6cf770fcfb0653044cfa38e05156d6885a51511c3b9a994a5584f29b8ebbde2c
// http://127.0.0.1:8000?email_verify_url=http://127.0.0.1:8000/api/verify-email/4/4be5a1201077d10124962f66f2bc872d6a8cd771?expires=1624165294&signature=3b3f0f0ceda9545126d6c64676fae5af5751dbd3e9ca22bd162d210759b70185
// Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

/**
 *Buyers
*/
Route::apiResource('buyers', BuyerController::class)->only(['index', 'show']);
Route::apiResource('buyers.transactions', BuyerTransactionController::class)->only(['index']);
Route::apiResource('buyers.products', BuyerProductController::class)->only(['index']);
Route::apiResource('buyers.sellers', BuyerSellerController::class)->only(['index']);
Route::apiResource('buyers.categories', BuyerCategoryController::class)->only(['index']);
/**
 *
*/
Route::apiResource('categories', CategoryController::class);
Route::apiResource('categories.products', CategoryProductController::class)->only(['index']);
Route::apiResource('categories.sellers', CategorySellerController::class)->only(['index']);
Route::apiResource('categories.transactions', CategoryTransactionController::class)->only(['index']);
Route::apiResource('categories.buyers', CategoryBuyerController::class)->only(['index']);
/**
 *
 */
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('products.transactions', ProductTransactionController::class);
Route::apiResource('products.buyers', ProductBuyerController::class)->only(['index']);
Route::apiResource('products.categories', ProductCategoryController::class)->only(['index', 'update', 'destroy']);
Route::apiResource('products.buyers.transactions', ProductBuyerTransactionController::class)->only(['store']);
/**
 *
 */
Route::apiResource('transactions', TransactionController::class)->only(['index', 'show']);
Route::apiResource('transactions.categories', TransactionCategoryController::class)->only(['index']);
Route::apiResource('transactions.sellers', TransactionSellerController::class)->only(['index']);
/**
 *
 */
Route::apiResource('sellers', SellerController::class)->only(['index', 'show']);
Route::apiResource('sellers.transactions', SellerTransactionController::class)->only(['index']);
Route::apiResource('sellers.categories', SellerCategoryController::class)->only(['index']);
Route::apiResource('sellers.buyers', SellerBuyerController::class)->only(['index']);
Route::apiResource('sellers.products', SellerProductController::class)->except(['show']);
/**
 *
 */
Route::apiResource('users', UserController::class)->middleware('auth:api');
/**
 *
 */
Route::get('/login', [AuthController::class, 'login']);
