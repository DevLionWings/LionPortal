<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\MyticketController;
use App\Http\Controllers\AbsensipayrollController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CommentController;

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

// Route::get('/', function () {
//     return view('index');
// });

Route::get('/', [DashboardController::class,'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login-page');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/* Menu Attendance */
Route::get('/absensi', [AbsensiController::class,'absensi'])->name('absensi');
Route::get('/absensi/get', [AbsensiController::class,'getAbsensi'])->name('get-absensi');

/* Menu Tiket */
Route::get('/tiket', [TiketController::class,'tiket'])->name('tiket');
Route::get('/tiket/get', [TiketController::class,'tiketList'])->name('get-tiket');
Route::get('/tiket/filter/get', [TiketController::class,'tiketFilter'])->name('filter-tiket');
Route::post('/addtiket', [TiketController::class,'addTiket'])->name('add-tiket');
Route::post('/updatetiket', [TiketController::class,'updateTiket'])->name('update-tiket');

Route::get('/mytiket', [MyticketController::class,'myTiket'])->name('mytiket');
Route::get('/mytiket/get', [MyticketController::class,'myTiketList'])->name('my-tiket');

// Route::get('/get/comment', [CommentController::class,'displayComment'])->name('comment-get');
Route::post('/add/comment', [CommentController::class,'addComment'])->name('comment-add');

/* Upload */
Route::get('/download', [TiketController::class,'downloadFile'])->name('download.file');

/* Email */ 
Route::get('send-mail', [MailController::class, 'index'])->name('email');

/* Chart-Today */ 
Route::get('get/stattoday', [ChartController::class, 'getStatToday'])->name('get.stat');

/* Menu HRIS -> Attendance Payroll */
Route::get('/absensipayroll', [AbsensipayrollController::class,'absenpayroll'])->name('absensipayroll');