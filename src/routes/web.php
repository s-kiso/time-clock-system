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

Route::get('/attendance', [RecordController::class, 'attendance'])->name('attendance_home');
Route::post('/attendance', [RecordController::class, 'attended']);
Route::post('/rest', [RecordController::class, 'rest']);
Route::get('/attendance/list', [RecordController::class, 'list'])->name('list_home');
Route::post('/attendance/list', [RecordController::class, 'listed']);
Route::get('/attendance/{id}', [RecordController::class, 'detail'])->name('record.detail');

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin_login_show');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLogoutController::class, 'logout']);
Route::get('/admin/list', [ListController::class, 'list'])->middleware('auth')->name('admin_list_home');
Route::post('/admin/list', [ListController::class, 'listed']);
// Route::post('/admin/list', [AdminController::class, 'list']);

// Route::middleware('auth')->group(function () {
//     Route::get('/', [AuthController::class, 'index']);
// });
