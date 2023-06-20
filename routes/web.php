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


//UI Pages Routs
Route::get('/', [HomeController::class, 'guest']);
Route::get('/dashboard', [HomeController::class, 'provincialadmin'])->name('dashboard.province');

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




Route::group(['middleware' => 'auth'], function () {
    // Permission Module
    Route::get('/role-permission',[RolePermission::class, 'index'])->name('role.permission.list');
    Route::resource('permission',PermissionController::class);
    Route::resource('role', RoleController::class);

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

//App Details Page => 'special-pages'], function() {
Route::group(['prefix' => 'special-pages'], function() {
    //Example Page Routs
    Route::get('billing', [HomeController::class, 'billing'])->name('special-pages.billing');
    Route::get('calender', [HomeController::class, 'calender'])->name('special-pages.calender');
    Route::get('kanban', [HomeController::class, 'kanban'])->name('special-pages.kanban');
    Route::get('pricing', [HomeController::class, 'pricing'])->name('special-pages.pricing');
    Route::get('rtl-support', [HomeController::class, 'rtlsupport'])->name('special-pages.rtlsupport');
    Route::get('timeline', [HomeController::class, 'timeline'])->name('special-pages.timeline');
});

//Widget Routs
Route::group(['prefix' => 'widget'], function() {
    Route::get('widget-basic', [HomeController::class, 'widgetbasic'])->name('widget.widgetbasic');
    Route::get('widget-chart', [HomeController::class, 'widgetchart'])->name('widget.widgetchart');
    Route::get('widget-card', [HomeController::class, 'widgetcard'])->name('widget.widgetcard');
});

//Maps Routs
Route::group(['prefix' => 'maps'], function() {
    Route::get('google', [HomeController::class, 'google'])->name('maps.google');
    Route::get('vector', [HomeController::class, 'vector'])->name('maps.vector');
});


//Error Page Route
Route::group(['prefix' => 'errors'], function() {
    Route::get('error404', [HomeController::class, 'error404'])->name('errors.error404');
    Route::get('error500', [HomeController::class, 'error500'])->name('errors.error500');
    Route::get('maintenance', [HomeController::class, 'maintenance'])->name('errors.maintenance');
});


//Forms Pages Routs
Route::group(['prefix' => 'forms'], function() {
    Route::get('element', [HomeController::class, 'element'])->name('forms.element');
    Route::get('wizard', [HomeController::class, 'wizard'])->name('forms.wizard');
    Route::get('validation', [HomeController::class, 'validation'])->name('forms.validation');
});


//Table Page Routs
Route::group(['prefix' => 'table'], function() {
    Route::get('bootstraptable', [HomeController::class, 'bootstraptable'])->name('table.bootstraptable');
    Route::get('datatable', [HomeController::class, 'datatable'])->name('table.datatable');
});

//Icons Page Routs
Route::group(['prefix' => 'icons'], function() {
    Route::get('solid', [HomeController::class, 'solid'])->name('icons.solid');
    Route::get('outline', [HomeController::class, 'outline'])->name('icons.outline');
    Route::get('dualtone', [HomeController::class, 'dualtone'])->name('icons.dualtone');
    Route::get('colored', [HomeController::class, 'colored'])->name('icons.colored');
});
//Extra Page Routs
Route::get('privacy-policy', [HomeController::class, 'privacypolicy'])->name('pages.privacy-policy');
Route::get('terms-of-use', [HomeController::class, 'termsofuse'])->name('pages.term-of-use');
