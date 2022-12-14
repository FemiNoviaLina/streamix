<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SharingGroupController;
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
Route::get('/', [SharingGroupController::class, 'showDashboard']);

Route::get('/dashboard', [SharingGroupController::class, 'showDashboard'])->name('dashboard');

Route::post('/payment/notification', [SharingGroupController::class, 'getPaymentNotification']);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/sharing-group/new', [SharingGroupController::class, 'createSharingGroup']);
    Route::get('/sharing-group/new', [SharingGroupController::class, 'showCreateSharingGroupForm'])->name('create-sharing-group');
    Route::get('/sharing-group/detail/{id}', [SharingGroupController::class, 'showSharingGroupDetails'])->name('detail-sharing-group');
    Route::get('/sharing-group/my', [SharingGroupController::class, 'showMyGroup'])->name('my-group');
    Route::get('/sharing-group/join/{id}', [SharingGroupController::class, 'showJoinConfirmation'])->name('join-temporary');
    Route::get('/sharing-group/payment/{id}', [SharingGroupController::class, 'showPaymentMethod'])->name('pick-payment');
    Route::post('/sharing-group/payment/{id}', [SharingGroupController::class, 'getPaymentCredentials'])->name('payment');
    Route::get('/payment/instruction/{id}', [SharingGroupController::class, 'getPaymentInstruction'])->name('payment-instruction');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
