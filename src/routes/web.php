<?php

use App\Http\Controllers\AdminLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\AdminLogoutController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

Route::get('/attendance', [RecordController::class, 'attendance'])->name('attendance_home')->middleware(['auth', 'verified']);
Route::post('/attendance', [RecordController::class, 'attended'])->middleware(['auth', 'verified']);
Route::post('/rest', [RecordController::class, 'rest'])->middleware(['auth', 'verified']);
Route::get('/attendance/list', [RecordController::class, 'list'])->name('list_home')->middleware(['auth', 'verified']);
Route::post('/attendance/list', [RecordController::class, 'listed']);
Route::get('/attendance/{id}', [RecordController::class, 'detail'])->name('record.detail')->middleware(['auth', 'verified']);
Route::post('/attendance/{id}', [RecordController::class, 'detailed'])->name('record.modify');
Route::get('/stamp_correction_request/list', [RecordController::class, 'apply'])->middleware('auth', 'verified');

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin_login_show');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLogoutController::class, 'logout']);
Route::get('/admin/attendance/list', [ListController::class, 'list'])->name('admin_list_home');
Route::post('/admin/attendance/list', [ListController::class, 'listed']);
Route::get('/admin/staff/list', [ListController::class, 'staff_list']);
Route::get('/admin/attendance/staff/{id}', [ListController::class, 'staff_detail'])->name('staff.detail');
Route::post('/admin/attendance/staff/{id}', [ListController::class, 'staff_detail_post'])->name('staff.detail_post');
Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [ListController::class, 'approve'])->name('modify.approve');
Route::post('/stamp_correction_request/approve/{attendance_correct_request}', [ListController::class, 'approved'])->name('modify.approved');

Route::post('/admin/attendance/staff/{id}/export', [ListController::class, 'staff_detail_export'])->name('staff.detail_export');
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/attendance');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');