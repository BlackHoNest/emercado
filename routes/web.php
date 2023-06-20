<?php

// Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\GlobalList;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CancelController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\Security\RolePermission;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\PermissionController;

use Illuminate\Support\Facades\Artisan;
// Packages
use Illuminate\Support\Facades\Route;

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

require __DIR__.'/auth.php';

Route::get('/storage', function () {
    Artisan::call('storage:link');
});

//sharing link
Route::group(['prefix' => 'share'], function() {
    Route::get('product/{id}', [ShareController::class, 'product'])->name('share.product');
});

//UI Pages Routs

Route::get('/', [HomeController::class, 'guest']);
Route::get('/dashboard', [HomeController::class, 'provincialadmin'])->name('dashboard.province');


Route::get('/', [HomeController::class, 'guest'])->name('guesthome');
Route::post('/modal-login', [AuthenticatedSessionController::class, 'modallogin']);

//registration
Route::group(['prefix' => 'register'], function() {
    Route::get('buyer', [BuyerController::class, 'buyer'])->name('buyer.signup');
    Route::get('seller', [GuestController::class, 'seller'])->name('seller.signup');
    Route::post('storeseller', [GuestController::class, 'storeseller'])->name('seller.store');
    Route::post('storebuyer', [BuyerController::class, 'storebuyer'])->name('buyer.store');

    Route::post('createadmin', [AdminController::class, 'createadmin']);
    Route::post('createfarm', [AdminController::class, 'createfarm']);
    Route::post('createfarmproduct', [AdminController::class, 'createfarmproduct']);
    Route::post('createaid', [AdminController::class, 'createaid']);

    Route::post('updateadmin', [AdminController::class, 'updateadmin']);
    Route::post('updatefarm', [AdminController::class, 'updatefarm']);
    Route::post('updatefarmproduct', [AdminController::class, 'updatefarmproduct']);
    Route::post('updateaid', [AdminController::class, 'updateaid']);
});

Route::group(['prefix' => 'delete'], function() {
    Route::post('deleteadmin', [DeleteController::class, 'deleteadmin']);
    Route::post('deletefarm', [DeleteController::class, 'deletefarm']);
    Route::post('deleteproduct', [DeleteController::class, 'deleteproduct']);
    Route::post('deleteaid', [DeleteController::class, 'deleteaid']);
    Route::post('deletebuyer', [DeleteController::class, 'deletebuyer']);
    Route::post('deleteseller', [DeleteController::class, 'deleteseller']);
});


//Admins
Route::group(['prefix' => 'admin'], function() {
    Route::get('/municipaladmins',[HomeController::class, 'municipaladmins'])->name('municipal.admin');
    Route::get('/verifiedsellers',[HomeController::class, 'verifiedsellers'])->name('verified.sellers');
    Route::get('/viewseller',[HomeController::class, 'viewseller'])->name('view.seller');
    Route::get('/pendingseller',[HomeController::class, 'pendingseller'])->name('pending.seller');
    Route::get('/pendingsellers',[HomeController::class, 'pendingsellers'])->name('pending.sellers');
    Route::get('/buyers',[HomeController::class, 'buyers'])->name('admin.buyer');
    Route::get('/viewbuyer',[HomeController::class, 'viewbuyer'])->name('view.buyer');

    Route::get('/farmtype',[HomeController::class, 'farmtype'])->name('farm.type');
    Route::get('/farmproduct',[HomeController::class, 'farmproduct'])->name('farm.product');
    Route::get('/farmaid',[HomeController::class, 'farmaid'])->name('farm.aid');
});
Route::group(['prefix' => 'view'], function() {
    Route::get('product/{id}', [GuestController::class, 'view'])->name('public.view');

});

//Lists
Route::group(['prefix' => 'list'], function() {
    Route::post('/barangays',[GlobalList::class, 'Barangay']);
    Route::post('/municipalities',[GlobalList::class, 'Municipality']);
    Route::post('/farmtypesub',[GlobalList::class, 'farmtypesub']);
});

//Verify
Route::group(['prefix' => 'verify'], function() {
    Route::post('/username',[VerifyController::class, 'verifyusername']);
    Route::post('/password',[VerifyController::class, 'verifypassword']);

    Route::post('/approveseller',[VerifyController::class, 'approveseller'])->name('approve.seller');
});

//Auth pages Routs
Route::group(['prefix' => 'auth'], function() {
    Route::get('provincial', [HomeController::class, 'provincial'])->name('auth.superadmin');
    Route::get('signin', [HomeController::class, 'signin'])->name('auth.signin');
    Route::get('confirmmail', [HomeController::class, 'confirmmail'])->name('auth.confirmmail');
    Route::get('lockscreen', [HomeController::class, 'lockscreen'])->name('auth.lockscreen');
    Route::get('recoverpw', [HomeController::class, 'recoverpw'])->name('auth.recoverpw');
    Route::get('userprivacysetting', [HomeController::class, 'userprivacysetting'])->name('auth.userprivacysetting');
});

Route::group(['prefix' => 'post','middleware' => ['auth','authseller']], function () {
    Route::get('/', [PostController::class, 'index'])->name('post.index');
    Route::get('/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/store', [PostController::class, 'store'])->name('post.store');
    Route::get('/drafts', [PostController::class, 'drafts'])->name('post.drafts');
    Route::post('/publish', [PostController::class, 'publish'])->name('post.publish');
    Route::post('/unpublish', [PostController::class, 'unpublish'])->name('post.unpublish');
    Route::post('/favorite', [PostController::class, 'favorite'])->name('post.favorite');
    // Route::post('/remove-file', [MOUController::class, 'removefile']);
});


Route::group(['prefix' => 'post','middleware' => ['auth','authbuyer']], function () {
    Route::post('/favorite', [PostController::class, 'favorite'])->name('post.favorite');
    // Route::post('/remove-file', [MOUController::class, 'removefile']);
});


Route::group(['prefix' => 'image','middleware' => ['auth','authseller']], function () {
    Route::post('/store', [ImageController::class, 'store'])->name('image.store');
    // Route::post('/remove-file', [MOUController::class, 'removefile']);
});

Route::group(['prefix' => 'buyer','middleware' => ['auth','authbuyer']], function () {
    Route::post('/addtocart', [BuyerController::class, 'cart'])->name('buyer.cart');
    // Route::post('/remove-file', [MOUController::class, 'removefile']);
});

Route::group(['prefix' => 'cart','middleware' => ['auth','authbuyer']], function () {
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/view', [CartController::class, 'view'])->name('cart.view');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
});

Route::group(['prefix' => 'order','middleware' => ['auth']], function () {
    Route::get('/list', [OrderController::class, 'list'])->name('order.list')->middleware('authseller');
    Route::get('/place', [OrderController::class, 'place'])->name('order.place')->middleware('authbuyer');
    Route::get('/view', [OrderController::class, 'view'])->name('order.view')->middleware('authbuyer');
    Route::post('/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::post('/cancelseller', [OrderController::class, 'cancelseller'])->name('order.cancelseller');
    Route::post('/confirm', [OrderController::class, 'confirm'])->name('order.confirm');
    Route::get('/myconfirm', [OrderController::class, 'confirmed'])->name('order.myconfirm')->middleware('authbuyer');
});

Route::group(['prefix' => 'cancel','middleware' => ['auth']], function () {
    Route::get('/buyer', [CancelController::class, 'buyer'])->name('cancel.buyer')->middleware('authbuyer');
   
});

Route::group(['middleware' => 'auth'], function () {
    // Dashboard Routes
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Users Module
    Route::resource('users', UserController::class);
});


//App Details Page => 'Dashboard'], function() {
Route::group(['prefix' => 'menu-style'], function() {
    //MenuStyle Page Routs
    Route::get('horizontal', [HomeController::class, 'horizontal'])->name('menu-style.horizontal');
    Route::get('dual-horizontal', [HomeController::class, 'dualhorizontal'])->name('menu-style.dualhorizontal');
    Route::get('dual-compact', [HomeController::class, 'dualcompact'])->name('menu-style.dualcompact');
    Route::get('boxed', [HomeController::class, 'boxed'])->name('menu-style.boxed');
    Route::get('boxed-fancy', [HomeController::class, 'boxedfancy'])->name('menu-style.boxedfancy');
});

//Error Page Route
Route::group(['prefix' => 'errors'], function() {
    Route::get('error404', [HomeController::class, 'error404'])->name('errors.error404');
    Route::get('error500', [HomeController::class, 'error500'])->name('errors.error500');
    Route::get('maintenance', [HomeController::class, 'maintenance'])->name('errors.maintenance');
});

