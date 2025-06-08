<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecordController;
use App\Models\Record;

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

// Route::middleware('auth')->group(function () {
//     Route::get('/', [AuthController::class, 'index']);
// });
