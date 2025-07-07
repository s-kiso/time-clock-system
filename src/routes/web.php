<?php

use App\Http\Controllers\AdminLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecordController;
use App\Models\Record;
use App\Http\Controllers\ListController;
use App\Http\Controllers\AdminLogoutController;

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

Route::get('/attendance', [RecordController::class, 'attendance'])->name('attendance_home')->middleware('auth');
Route::post('/attendance', [RecordController::class, 'attended'])->middleware('auth');
Route::post('/rest', [RecordController::class, 'rest'])->middleware('auth');
Route::get('/attendance/list', [RecordController::class, 'list'])->name('list_home')->middleware('auth');
Route::post('/attendance/list', [RecordController::class, 'listed']);
Route::get('/attendance/{id}', [RecordController::class, 'detail'])->name('record.detail')->middleware('auth');
Route::post('/attendance/{id}', [RecordController::class, 'detailed'])->name('record.modify');
Route::get('/stamp_correction_request/list', [RecordController::class, 'apply']);

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

// Route::get('/attendance/{admin_id}', [ListController::class, 'detail'])->name('admin_record.detail');

// Route::post('/admin/list', [AdminController::class, 'list']);

// Route::middleware('auth')->group(function () {
//     Route::get('/', [AuthController::class, 'index']);
// });
